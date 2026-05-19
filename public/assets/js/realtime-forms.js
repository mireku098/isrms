/**
 * ISRMS Real-Time Form Handler
 * Enhanced form management with real-time database data and AJAX
 */

// Get CSRF token for AJAX requests
const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.content || "";

// Real-time Data Management
const RealtimeData = {
    cache: {},
    apiBase: "/api",

    // Fetch items from database
    async fetchItems() {
        if (this.cache.items) return this.cache.items;

        try {
            const response = await fetch(`${this.apiBase}/items`, {
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
            });
            const data = await response.json();
            this.cache.items = data.data || [];
            return this.cache.items;
        } catch (error) {
            console.error("Failed to fetch items:", error);
            return [];
        }
    },

    // Fetch specific item stock
    async fetchItemStock(itemId) {
        try {
            const response = await fetch(
                `${this.apiBase}/items/${itemId}/stock`,
                {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                },
            );
            return await response.json();
        } catch (error) {
            console.error("Failed to fetch item stock:", error);
            return null;
        }
    },

    // Fetch requisitions
    async fetchRequisitions(status = "approved") {
        const cacheKey = `requisitions_${status}`;
        if (this.cache[cacheKey]) return this.cache[cacheKey];

        try {
            const response = await fetch(
                `${this.apiBase}/requisitions?status=${status}`,
                {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                },
            );
            const data = await response.json();
            this.cache[cacheKey] = data.data || [];
            return this.cache[cacheKey];
        } catch (error) {
            console.error("Failed to fetch requisitions:", error);
            return [];
        }
    },

    // Search items by name
    async searchItems(query) {
        try {
            const response = await fetch(
                `${this.apiBase}/items/search?q=${encodeURIComponent(query)}`,
                {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                },
            );
            return await response.json();
        } catch (error) {
            console.error("Failed to search items:", error);
            return { results: [] };
        }
    },

    // Clear cache
    clearCache() {
        this.cache = {};
    },
};

// Dynamic Items Management
function initDynamicItems(containerId, buttonId, minItems = 1) {
    const container = document.getElementById(containerId);
    const addBtn = document.getElementById(buttonId);

    if (!container || !addBtn) return;

    addBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const rows = container.getElementsByClassName("item-row");
        const index = rows.length;

        if (rows.length === 0) return;

        const newRow = rows[0].cloneNode(true);

        // Update all input names
        const inputs = newRow.querySelectorAll("[name]");
        inputs.forEach((input) => {
            const currentName = input.getAttribute("name");
            if (currentName && currentName.includes("[")) {
                const newName = currentName.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute("name", newName);
                input.value = "";
                input.classList.remove("is-invalid");
            }
        });

        // Clear error messages
        newRow
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => el.remove());

        // Enable remove button
        const removeBtn = newRow.querySelector('button[type="button"]');
        if (removeBtn) {
            removeBtn.removeAttribute("disabled");
            removeBtn.addEventListener("click", function () {
                if (
                    container.getElementsByClassName("item-row").length >
                    minItems
                ) {
                    newRow.remove();
                } else {
                    showToast(
                        "Validation",
                        `Minimum ${minItems} item(s) required`,
                        "warning",
                    );
                }
            });
        }

        // Re-initialize stock display for new select
        const newSelect = newRow.querySelector("[data-stock-display]");
        if (newSelect) {
            initStockDisplay(`[name="items[${index}][item_id]"]`);
        }

        container.appendChild(newRow);
        container.dispatchEvent(new Event("items-added"));
    });

    // Set up remove buttons on existing rows
    const removeButtons = container.querySelectorAll('button[type="button"]');
    removeButtons.forEach((btn, index) => {
        if (
            container.getElementsByClassName("item-row").length > minItems ||
            index > 0
        ) {
            btn.removeAttribute("disabled");
            btn.addEventListener("click", function () {
                if (
                    container.getElementsByClassName("item-row").length >
                    minItems
                ) {
                    btn.closest(".item-row").remove();
                } else {
                    showToast(
                        "Validation",
                        `Minimum ${minItems} item(s) required`,
                        "warning",
                    );
                }
            });
        }
    });
}

// Real-time Stock Display from Database
function initStockDisplay(selectSelector) {
    const selects = document.querySelectorAll(selectSelector);

    selects.forEach((select) => {
        select.addEventListener("change", async function () {
            const itemId = this.value;
            const itemRow = this.closest(".item-row");
            const stockLabel = itemRow?.querySelector(".item-stock");

            if (!itemId || !stockLabel) return;

            // Fetch real stock data
            const stockData = await RealtimeData.fetchItemStock(itemId);

            if (stockData) {
                let badgeClass = "bg-success";
                let badgeText = `In Stock: ${stockData.stock}`;

                if (stockData.stock === 0) {
                    badgeClass = "bg-danger";
                    badgeText = "Out of Stock";
                } else if (stockData.stock < stockData.min_stock) {
                    badgeClass = "bg-warning text-dark";
                    badgeText = `Low Stock: ${stockData.stock}`;
                }

                stockLabel.innerHTML = `<span class="badge ${badgeClass} ms-2">${badgeText}</span>`;
            }
        });
    });
}

// Populate select with real items
async function populateItemSelect(selectSelector) {
    const selects = document.querySelectorAll(selectSelector);
    const items = await RealtimeData.fetchItems();

    selects.forEach((select) => {
        // Clear existing options (except first placeholder)
        while (select.options.length > 1) {
            select.remove(1);
        }

        // Add items
        items.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.id;
            option.text = `${item.name} (${item.category || "Uncategorized"}) - Stock: ${item.stock}`;
            option.dataset.stock = item.stock;
            option.dataset.stockStatus = item.stock_status;
            select.appendChild(option);
        });
    });
}

// Populate requisitions select
async function populateRequisitionSelect(selectSelector, status = "approved") {
    const selects = document.querySelectorAll(selectSelector);
    const requisitions = await RealtimeData.fetchRequisitions(status);

    selects.forEach((select) => {
        // Clear existing options (except first placeholder)
        while (select.options.length > 1) {
            select.remove(1);
        }

        // Add requisitions
        requisitions.forEach((req) => {
            const option = document.createElement("option");
            option.value = req.id;
            option.text = `REQ-${req.id} - ${req.department} (${req.items_count} items)`;
            option.dataset.reqId = req.id;
            select.appendChild(option);
        });
    });
}

// Real-time Search/Autocomplete
function initAutocomplete(inputSelector, resultsContainer) {
    const input = document.querySelector(inputSelector);
    const container = document.querySelector(resultsContainer);

    if (!input || !container) return;

    input.addEventListener("input", async (e) => {
        const query = e.target.value.trim();

        if (query.length < 2) {
            container.style.display = "none";
            return;
        }

        const results = await RealtimeData.searchItems(query);

        if (results.results && results.results.length > 0) {
            container.innerHTML = results.results
                .map(
                    (item) => `
                    <div class="autocomplete-item p-2 border-bottom cursor-pointer" data-id="${item.id}" data-name="${item.name}">
                        <strong>${item.name}</strong>
                        <small class="text-secondary d-block">${item.category} - Stock: ${item.stock}</small>
                    </div>
                `,
                )
                .join("");
            container.style.display = "block";

            // Handle item selection
            container.querySelectorAll(".autocomplete-item").forEach((item) => {
                item.addEventListener("click", () => {
                    input.value = item.dataset.name;
                    input.dataset.itemId = item.dataset.id;
                    container.style.display = "none";
                });
            });
        } else {
            container.innerHTML =
                '<div class="p-3 text-muted">No items found</div>';
            container.style.display = "block";
        }
    });

    // Close on blur
    document.addEventListener("click", (e) => {
        if (e.target !== input) {
            container.style.display = "none";
        }
    });
}

// Form Validation (Client-side)
function validateForm(formId, rules = {}) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let isValid = true;
    const inputs = form.querySelectorAll("[required]");

    inputs.forEach((input) => {
        if (!input.value.trim()) {
            input.classList.add("is-invalid");
            isValid = false;
        } else {
            input.classList.remove("is-invalid");
        }
    });

    return isValid;
}

// Auto-save form to localStorage
function initAutoSave(formId, storageKey) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Restore from localStorage
    const savedData = localStorage.getItem(storageKey);
    if (savedData) {
        const data = JSON.parse(savedData);
        Object.keys(data).forEach((key) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) input.value = data[key];
        });
    }

    // Auto-save on input
    const inputs = form.querySelectorAll("input, textarea, select");
    inputs.forEach((input) => {
        input.addEventListener("change", () => {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (key.includes("[")) return; // Skip array fields for simple storage
                data[key] = value;
            });
            localStorage.setItem(storageKey, JSON.stringify(data));
        });
    });

    // Clear on successful submission
    form.addEventListener("submit", () => {
        localStorage.removeItem(storageKey);
    });
}

// Show loading state on button
function showLoadingState(buttonId) {
    const btn = document.getElementById(buttonId);
    if (!btn) return;

    btn.addEventListener("click", () => {
        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Reset after 30 seconds in case of error
        setTimeout(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        }, 30000);
    });
}

// Reset button state
function resetButtonState(buttonId) {
    const btn = document.getElementById(buttonId);
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = btn.getAttribute("data-original-text") || "Submit";
    }
}

// Toast Notifications
function showToast(title, message, type = "info") {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === "danger" ? "danger" : type === "success" ? "success" : type === "warning" ? "warning" : "info"} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong>: ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    const container =
        document.getElementById("toast-container") || createToastContainer();
    const toastEl = document.createElement("div");
    toastEl.innerHTML = toastHtml;
    container.appendChild(toastEl.firstElementChild);

    const toast = new bootstrap.Toast(toastEl.firstElementChild);
    toast.show();

    toastEl.firstElementChild.addEventListener("hidden.bs.toast", function () {
        this.remove();
    });
}

function createToastContainer() {
    const container = document.createElement("div");
    container.id = "toast-container";
    container.className = "toast-container position-fixed bottom-0 end-0 p-3";
    document.body.appendChild(container);
    return container;
}

// AJAX Form Submission (No page reload)
function initAjaxSubmit(formSelector, options = {}) {
    const forms = document.querySelectorAll(formSelector);

    forms.forEach((form) => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn) showLoadingState(submitBtn.id || "submitBtn");

            try {
                const formData = new FormData(form);
                const method = form.method.toUpperCase();
                const action = form.action;

                const response = await fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    showToast(
                        "Success",
                        data.message || "Operation completed successfully",
                        "success",
                    );

                    if (options.onSuccess) {
                        options.onSuccess(data);
                    } else {
                        // Default: redirect after 2 seconds
                        setTimeout(() => {
                            window.location.href =
                                form.getAttribute("data-redirect") ||
                                window.location.href;
                        }, 2000);
                    }
                } else {
                    const data = await response.json();
                    showToast(
                        "Error",
                        data.message || "An error occurred",
                        "danger",
                    );

                    if (data.errors) {
                        Object.keys(data.errors).forEach((field) => {
                            const input = form.querySelector(
                                `[name="${field}"]`,
                            );
                            if (input) {
                                input.classList.add("is-invalid");
                            }
                        });
                    }
                }
            } catch (error) {
                showToast(
                    "Error",
                    error.message || "Network error occurred",
                    "danger",
                );
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText =
                        submitBtn.getAttribute("data-original-text") ||
                        "Submit";
                }
            }
        });
    });
}

// Initialize all real-time features on DOM load
document.addEventListener("DOMContentLoaded", async () => {
    // Populate item selects with real data
    const itemSelects = document.querySelectorAll("[data-stock-display]");
    if (itemSelects.length > 0) {
        await populateItemSelect("[data-stock-display]");
        initStockDisplay("[data-stock-display]");
    }

    // Populate requisition selects
    const reqSelects = document.querySelectorAll("[data-requisition-select]");
    if (reqSelects.length > 0) {
        await populateRequisitionSelect("[data-requisition-select]");
    }

    // Initialize autocomplete where needed
    const autocompleteInputs = document.querySelectorAll("[data-autocomplete]");
    autocompleteInputs.forEach((input) => {
        const resultContainer = input.getAttribute("data-autocomplete");
        initAutocomplete(input, resultContainer);
    });

    // Initialize auto-save on forms
    document.querySelectorAll("[data-auto-save]").forEach((form) => {
        const storageKey = form.getAttribute("data-auto-save");
        initAutoSave(form.id, storageKey);
    });

    // Initialize loading state
    document.querySelectorAll("[data-loading-btn]").forEach((btn) => {
        showLoadingState(btn.id);
    });

    // Initialize AJAX submit
    document.querySelectorAll("[data-ajax-submit]").forEach((form) => {
        initAjaxSubmit(form);
    });
});
