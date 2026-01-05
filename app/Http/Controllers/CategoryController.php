<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()
            ->withCount('transactions')
            ->get()
            ->groupBy('type');

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7'
        ]);

        $validated['user_id'] = auth()->id();

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete category with existing transactions. Please reassign or delete transactions first.'
            ]);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $transactions = $category->transactions()
            ->latest('transaction_date')
            ->paginate(20);

        $totalSpent = $category->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $totalEarned = $category->transactions()
            ->where('type', 'income')
            ->sum('amount');

        return view('categories.show', compact('category', 'transactions', 'totalSpent', 'totalEarned'));
    }
}
