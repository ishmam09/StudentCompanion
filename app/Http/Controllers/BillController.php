<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillController extends Controller
{
    public function index()
    {
        $upcomingBills = auth()->user()->bills()
            ->with('category')
            ->upcoming()
            ->get();

        $overdueBills = auth()->user()->bills()
            ->with('category')
            ->overdue()
            ->get();

        $paidBills = auth()->user()->bills()
            ->with('category')
            ->where('status', 'paid')
            ->latest('due_date')
            ->take(10)
            ->get();

        // Calculate total monthly bills
        $monthlyTotal = auth()->user()->bills()
            ->where('status', 'pending')
            ->where('frequency', 'monthly')
            ->sum('amount');

        return view('bills.index', compact('upcomingBills', 'overdueBills', 'paidBills', 'monthlyTotal'));
    }

    public function create()
    {
        $categories = auth()->user()->categories()
            ->where('type', 'expense')
            ->get();

        return view('bills.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'frequency' => 'required|in:weekly,monthly,quarterly,yearly',
            'category_id' => 'nullable|exists:categories,id',
            'is_automatic' => 'boolean',
            'reminder_enabled' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1|max:30'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        // Verify category belongs to user if provided
        if ($request->category_id) {
            $category = auth()->user()->categories()->find($request->category_id);
            if (!$category) {
                return back()->withErrors(['category_id' => 'Invalid category selected.']);
            }
        }

        Bill::create($validated);

        return redirect()->route('bills.index')
            ->with('success', 'Bill added successfully!');
    }

    public function show(Bill $bill)
    {
        $this->authorize('view', $bill);
        
        $bill->load('category');

        // Get payment history (transactions related to this bill)
        $paymentHistory = auth()->user()->transactions()
            ->where('description', 'like', '%' . $bill->name . '%')
            ->latest('transaction_date')
            ->take(12)
            ->get();

        // Calculate next due date
        $nextDueDate = $this->calculateNextDueDate($bill);

        return view('bills.show', compact('bill', 'paymentHistory', 'nextDueDate'));
    }

    public function edit(Bill $bill)
    {
        $this->authorize('update', $bill);

        $categories = auth()->user()->categories()
            ->where('type', 'expense')
            ->get();

        return view('bills.edit', compact('bill', 'categories'));
    }

    public function update(Request $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'frequency' => 'required|in:weekly,monthly,quarterly,yearly',
            'category_id' => 'nullable|exists:categories,id',
            'is_automatic' => 'boolean',
            'reminder_enabled' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1|max:30',
            'status' => 'in:pending,paid,overdue'
        ]);

        // Verify category belongs to user if provided
        if ($request->category_id) {
            $category = auth()->user()->categories()->find($request->category_id);
            if (!$category) {
                return back()->withErrors(['category_id' => 'Invalid category selected.']);
            }
        }

        $bill->update($validated);

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully!');
    }

    public function destroy(Bill $bill)
    {
        $this->authorize('delete', $bill);
        $bill->delete();

        return redirect()->route('bills.index')
            ->with('success', 'Bill deleted successfully!');
    }

    public function markAsPaid(Request $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        // Create a transaction for this payment
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $bill->category_id,
            'description' => $bill->name . ' - Bill Payment',
            'amount' => $bill->amount,
            'type' => 'expense',
            'transaction_date' => now(),
            'notes' => 'Automatic bill payment entry'
        ]);

        // Update bill status
        $bill->update([
            'status' => 'paid'
        ]);

        // Calculate and set next due date
        $nextDueDate = $this->calculateNextDueDate($bill);
        
        // Create a new bill instance for next period or update existing
        if ($request->create_next) {
            Bill::create([
                'user_id' => $bill->user_id,
                'category_id' => $bill->category_id,
                'name' => $bill->name,
                'amount' => $bill->amount,
                'due_date' => $nextDueDate,
                'frequency' => $bill->frequency,
                'is_automatic' => $bill->is_automatic,
                'reminder_enabled' => $bill->reminder_enabled,
                'reminder_days_before' => $bill->reminder_days_before,
                'status' => 'pending'
            ]);
        } else {
            // Just update the current bill's due date
            $bill->update([
                'due_date' => $nextDueDate,
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Bill marked as paid and transaction created!');
    }

    private function calculateNextDueDate(Bill $bill): Carbon
    {
        $dueDate = Carbon::parse($bill->due_date);

        return match($bill->frequency) {
            'weekly' => $dueDate->addWeek(),
            'monthly' => $dueDate->addMonth(),
            'quarterly' => $dueDate->addMonths(3),
            'yearly' => $dueDate->addYear(),
            default => $dueDate->addMonth()
        };
    }
}
