<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLedger;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     */
    public function index()
    {
        $this->authorize('isStorekeeper');
        $this->syncLegacyCategories();

        $selectedCategory = request('category_id');
        $categories = Category::orderBy('name')->get(['id', 'name']);

        $itemsQuery = Item::query()->orderBy('name');

        if (!empty($selectedCategory)) {
            $itemsQuery->where('category_id', $selectedCategory);
        }

        $items = $itemsQuery->paginate(20)->withQueryString();
        
        // Calculate stats
        $allItems = Item::all();
        $stats = [
            'total_items' => $allItems->count(),
            'low_stock' => $allItems->filter(function($item) {
                return $item->getCurrentStock() < $item->min_stock && $item->getCurrentStock() > 0;
            })->count(),
            'out_of_stock' => $allItems->filter(function($item) {
                return $item->getCurrentStock() <= 0;
            })->count(),
            'categories' => $allItems->pluck('category')->unique()->filter()->count(),
        ];

        return view('items.index', compact('items', 'stats', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $this->authorize('isStorekeeper');
        $this->syncLegacyCategories();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item in database.
     */
    public function store(Request $request)
    {
        $this->authorize('isStorekeeper');
        $this->syncLegacyCategories();
        
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'nullable|string|max:100|exists:categories,name',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:min_stock',
            'opening_stock' => 'nullable|integer|min:0',
        ]);

        $categoryId = $this->resolveCategoryId($validated['category'] ?? null);
        $itemData = collect($validated)->except(['opening_stock'])->toArray();
        $itemData['category_id'] = $categoryId;

        $item = Item::create($itemData);

        if (($validated['opening_stock'] ?? 0) > 0) {
            InventoryLedger::create([
                'item_id' => $item->id,
                'transaction_type' => 'RECEIVE',
                'quantity' => $validated['opening_stock'],
                'balance_after' => $validated['opening_stock'],
                'reference_type' => 'SRA',
                'reference_id' => null,
            ]);
        }

        // Log to audit trail
        $this->logAudit('CREATE', 'items', $item->id);

        return redirect()->route('items.index')
            ->with('success', "Item '{$item->name}' created successfully.");
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $this->authorize('isStorekeeper');
        
        $stock = $item->getCurrentStock();
        $ledger = $item->ledgerEntries()->latest()->paginate(10);
        
        return view('items.show', compact('item', 'stock', 'ledger'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $this->authorize('isStorekeeper');
        $this->syncLegacyCategories();

        $lastLedgerEntry = $item->ledgerEntries()->latest()->first();
        $currentStock = $lastLedgerEntry ? $lastLedgerEntry->balance_after : 0;
        $lastStockUpdate = $lastLedgerEntry ? $lastLedgerEntry->created_at : null;
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('items.edit', compact('item', 'currentStock', 'lastStockUpdate', 'categories'));
    }

    /**
     * Update the specified item in database.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('isStorekeeper');
        $this->syncLegacyCategories();
        
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'nullable|string|max:100|exists:categories,name',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:min_stock',
            'current_stock' => 'nullable|integer|min:0',
        ]);

        $currentStock = $item->getCurrentStock();
        $newStock = $validated['current_stock'] ?? $currentStock;
        $categoryId = $this->resolveCategoryId($validated['category'] ?? null);
        $itemData = collect($validated)->except(['current_stock'])->toArray();
        $itemData['category_id'] = $categoryId;

        $item->update($itemData);

        if ($newStock !== $currentStock) {
            $this->recordStockAdjustment($item->id, $currentStock, $newStock);
        }

        // Log to audit trail
        $this->logAudit('UPDATE', 'items', $item->id);

        return redirect()->route('items.show', $item)
            ->with('success', "Item '{$item->name}' updated successfully.");
    }

    /**
     * Remove the specified item from database.
     */
    public function destroy(Item $item)
    {
        $this->authorize('isStorekeeper');
        
        $name = $item->name;
        $item->delete();

        // Log to audit trail
        $this->logAudit('DELETE', 'items', $item->id);

        return redirect()->route('items.index')
            ->with('success', "Item '{$name}' deleted successfully.");
    }

    /**
     * Log action to audit trail
     */
    private function logAudit($action, $table, $recordId)
    {
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
        ]);
    }

    /**
     * Ensure category record exists and return its ID
     */
    private function resolveCategoryId($categoryName)
    {
        if (!$categoryName) {
            return null;
        }

        $category = Category::firstOrCreate(['name' => $categoryName]);

        return $category->id;
    }

    /**
     * Record manual stock adjustment through inventory ledger
     */
    private function recordStockAdjustment($itemId, $fromStock, $toStock)
    {
        $difference = $toStock - $fromStock;

        if ($difference === 0) {
            return;
        }

        InventoryLedger::create([
            'item_id' => $itemId,
            'transaction_type' => $difference > 0 ? 'RECEIVE' : 'ISSUE',
            'quantity' => abs($difference),
            'balance_after' => $toStock,
            'reference_type' => $difference > 0 ? 'SRA' : 'ISSUE',
            'reference_id' => null,
        ]);
    }

    /**
     * Sync existing text categories into categories table.
     */
    private function syncLegacyCategories()
    {
        $legacyNames = Item::query()
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->pluck('category')
            ->unique();

        foreach ($legacyNames as $name) {
            $category = Category::firstOrCreate(['name' => $name]);

            Item::where('category', $name)
                ->whereNull('category_id')
                ->update(['category_id' => $category->id]);
        }
    }

    /**
     * API: Get all items (JSON)
     */
    public function apiList()
    {
        $items = Item::orderBy('name')
            ->get()
            ->map(function ($item) {
                $stock = $item->getCurrentStock();
                $stockStatus = $stock > $item->max_stock ? 'high' : ($stock < $item->min_stock ? 'low' : 'normal');

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'category_id' => $item->category_id,
                    'unit' => $item->unit,
                    'stock' => $stock,
                    'min_stock' => $item->min_stock,
                    'max_stock' => $item->max_stock,
                    'stock_status' => $stockStatus,
                    'available' => $stock > 0,
                ];
            });

        return response()->json(['data' => $items]);
    }

    /**
     * API: Get stock for specific item
     */
    public function apiStock(Item $item)
    {
        $stock = $item->getCurrentStock();
        $stockStatus = $stock > $item->max_stock ? 'high' : ($stock < $item->min_stock ? 'low' : 'normal');

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category,
            'category_id' => $item->category_id,
            'stock' => $stock,
            'min_stock' => $item->min_stock,
            'max_stock' => $item->max_stock,
            'stock_status' => $stockStatus,
            'available' => $stock > 0,
        ]);
    }

    /**
     * API: Search items by name/category
     */
    public function apiSearch(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');

        $items = Item::query();

        if ($query) {
            $items->where('name', 'LIKE', "%$query%")
                ->orWhere('category', 'LIKE', "%$query%");
        }

        if ($category) {
            $items->where('category', $category);
        }

        $results = $items->select('id', 'name', 'category', 'unit')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                $stock = $item->getCurrentStock();
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'category_id' => $item->category_id,
                    'unit' => $item->unit,
                    'stock' => $stock,
                    'text' => "{$item->name} ({$item->category}) - Stock: {$stock}",
                ];
            });

        return response()->json(['results' => $results]);
    }
}
