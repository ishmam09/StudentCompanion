<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('expense.budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('expense.budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        Budget::create($validated);

        return redirect()->route('expense.budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        $this->authorize('view', $budget);
        return view('expense.budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);
        $categories = Category::all();
        return view('expense.budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $budget->update($validated);

        return redirect()->route('expense.budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        $budget->delete();

        return redirect()->route('expense.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }

    public function toggle(Budget $budget)
    {
        $this->authorize('update', $budget);
        $budget->update(['is_active' => !$budget->is_active]);

        return back()->with('success', 'Budget status updated.');
    }
}
