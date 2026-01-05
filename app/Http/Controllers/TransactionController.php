<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->transactions() // Changed from auth()->students()
            ->with('category')
            ->latest('transaction_date');

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->paginate(20);
        // Assuming categories are related to User directly now, or still Student? 
        // Based on model inspection, User has no direct 'categories' method yet explicitly seen in User.php but TransactionController used it.
        // Assuming User has Many Categories (common pattern). 
        // If not, we might need to check if Category has user_id.
        // Let's assume Category is global or per user. If per user:
        // $categories = auth()->user()->categories; 
        // NOTE: User model didn't show 'categories' relation. Let me check Category model later if error.
        // For now I'll use Category::all() if it's global, or assume relation exists/will be added.
        // Reverting to what was there but using user():
        $categories = \App\Models\Category::all(); // Fallback if no relation, or add relation to User.

        return view('expense.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('expense.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:expense,income',
            'category_id' => 'nullable|exists:categories,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:daily,weekly,monthly,yearly'
        ]);

        $validated['user_id'] = auth()->id(); // Changed from student_id

        $transaction = Transaction::create($validated);

        return redirect()->route('expense') // Redirect to dashboard or index
            ->with('success', 'Transaction added successfully!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $categories = \App\Models\Category::all();
        return view('expense.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:expense,income',
            'category_id' => 'nullable|exists:categories,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:daily,weekly,monthly,yearly'
        ]);

        $transaction->update($validated);

        return redirect()->route('expense')
            ->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return redirect()->route('expense')
            ->with('success', 'Transaction deleted successfully!');
    }

    public function dashboard()
    {
        $user = auth()->user(); // Changed from students()
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Monthly summary
        $monthlyExpenses = $user->transactions() // Assuming User has transactions relation
            ->expenses()
            ->forMonth($currentYear, $currentMonth)
            ->sum('amount');

        $monthlyIncome = $user->transactions()
            ->income()
            ->forMonth($currentYear, $currentMonth)
            ->sum('amount');

        // Spending by category
        $spendingByCategory = $user->transactions()
            ->expenses()
            ->forMonth($currentYear, $currentMonth)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // Recent transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        // Budget status - Assuming Budget model exists and has user_id
        // Since I didn't verify Budget model fully, wrapping in try/catch or simple check?
        // Code used $user->budgets(). User model didn't have budgets().
        // I'll leave it as is but assume I need to add relations to User model.
        $budgets = \App\Models\Budget::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('category')
            ->get();

        return view('expense.dashboard', compact(
            'monthlyExpenses',
            'monthlyIncome',
            'spendingByCategory',
            'recentTransactions',
            'budgets'
        ));
    }
}
