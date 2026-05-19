<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head-meta')
    <title>Welcome - ISRMS</title>
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="row mb-6 g-6">
            <div class="col-12">
                <div class="text-center py-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <span class="text-primary">ISRMS</span>
                    </h1>
                    <p class="fs-5 text-secondary mb-4">
                        Integrated Store & Requisition Management System
                    </p>
                    <p class="mb-6">Frontend implementation successfully loaded! ✅</p>

                    <div class="row justify-content-center g-3">
                        <div class="col-auto">
                            <a href="{{ route('dashboard.storekeeper') }}" class="btn btn-primary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="me-2">
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                </svg>
                                View Storekeeper Dashboard
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="me-2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="3" x2="9" y2="21"></line>
                                    <line x1="15" y1="3" x2="15" y2="21"></line>
                                </svg>
                                Browse Modules
                            </a>
                        </div>
                    </div>

                    <div class="row mt-6 pt-6 border-top">
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">📦 Inventory</h6>
                                <p class="text-secondary small"><a href="{{ route('items.index') }}">Manage Items</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">📥 Receiving (SRA)</h6>
                                <p class="text-secondary small"><a href="{{ route('sra.index') }}">Track Receipts</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">📤 Requisitions</h6>
                                <p class="text-secondary small"><a href="{{ route('requisitions.index') }}">View
                                        Requests</a></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">⬆️ Issues</h6>
                                <p class="text-secondary small"><a href="{{ route('issues.index') }}">Track Issues</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">📊 Ledger</h6>
                                <p class="text-secondary small"><a href="{{ route('ledger.index') }}">View
                                        Transactions</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="fw-bold">📈 Reports</h6>
                                <p class="text-secondary small"><a href="{{ route('reports.index') }}">Analytics</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.scripts')
</body>

</html>