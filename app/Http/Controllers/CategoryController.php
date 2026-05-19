<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display categories list.
     */
    public function index()
    {
        $this->authorize('isStorekeeper');

        $categories = Category::withCount('items')
            ->orderBy('name')
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show category create form.
     */
    public function create()
    {
        $this->authorize('isStorekeeper');

        return view('categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $this->authorize('isStorekeeper');

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        $category = Category::create($validated);
        $this->logAudit('CREATE', 'categories', $category->id);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show category edit form.
     */
    public function edit(Category $category)
    {
        $this->authorize('isStorekeeper');

        return view('categories.edit', compact('category'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('isStorekeeper');

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $oldName = $category->name;
        $category->update($validated);

        // Keep legacy category text in sync for old records.
        Item::where('category_id', $category->id)->update(['category' => $category->name]);
        Item::whereNull('category_id')->where('category', $oldName)->update(['category' => $category->name]);

        $this->logAudit('UPDATE', 'categories', $category->id);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category if unused.
     */
    public function destroy(Category $category)
    {
        $this->authorize('isStorekeeper');

        $isUsed = Item::where('category_id', $category->id)->exists();

        if ($isUsed) {
            return back()->with('error', 'Cannot delete category because it is still used by items.');
        }

        $categoryId = $category->id;
        $category->delete();
        $this->logAudit('DELETE', 'categories', $categoryId);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Log action to audit trail.
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
}
