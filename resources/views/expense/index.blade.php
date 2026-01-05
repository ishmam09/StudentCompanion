@extends('layout.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="margin-bottom: 0.5rem;">All Transactions</h1>
                <p class="text-muted">History of your income and expenses.</p>
            </div>
            <a href="{{ route('expense.transactions.create') }}" class="btn btn-primary">
                + Add Transaction
            </a>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 2rem; padding: 1.5rem;">
            <form action="{{ route('expense.transactions.index') }}" method="GET"
                style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-input">
                        <option value="">All Types</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-input">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input"
                        value="{{ request('start_date') }}">
                </div>

                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input" value="{{ request('end_date') }}">
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="height: 48px;">Filter</button>
                    <a href="{{ route('expense.transactions.index') }}" class="btn"
                        style="background: var(--background); height: 48px; display: flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <style>
                        .transaction-row:hover { background: rgba(0,0,0,0.02); }
                    </style>
                    <thead style="background: var(--background); border-bottom: 1px solid var(--border-color);">
                        <tr>
                            <th
                                style="padding: 1rem 1.5rem; text-align: left; font-weight: 600; color: var(--text-secondary);">
                                Date</th>
                            <th
                                style="padding: 1rem 1.5rem; text-align: left; font-weight: 600; color: var(--text-secondary);">
                                Description</th>
                            <th
                                style="padding: 1rem 1.5rem; text-align: left; font-weight: 600; color: var(--text-secondary);">
                                Category</th>
                            <th
                                style="padding: 1rem 1.5rem; text-align: right; font-weight: 600; color: var(--text-secondary);">
                                Amount</th>
                            <th
                                style="padding: 1rem 1.5rem; text-align: right; font-weight: 600; color: var(--text-secondary);">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="transaction-row" style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                                <td style="padding: 1rem 1.5rem; color: var(--text-secondary);">
                                    {{ $transaction->transaction_date->format('M d, Y') }}</td>
                                <td style="padding: 1rem 1.5rem;">
                                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->description }}
                                    </div>
                                    @if($transaction->notes)
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                            {{ Str::limit($transaction->notes, 30) }}</div>
                                    @endif
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <span
                                        style="background: var(--background); padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.85rem; color: var(--primary-color);">
                                        {{ optional($transaction->category)->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right;">
                                    <span
                                        style="font-weight: 700; color: {{ $transaction->type == 'expense' ? '#DC2626' : '#059669' }};">
                                        {{ $transaction->type == 'expense' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <a href="{{ route('expense.transactions.edit', $transaction) }}" class="btn-link"
                                            style="color: var(--primary-color); padding: 0.25rem;">
                                            ✏️
                                        </a>
                                        <form action="{{ route('expense.transactions.destroy', $transaction) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-link"
                                                style="color: #DC2626; border: none; cursor: pointer; padding: 0.25rem;">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                                    No transactions found.
                                    <br>
                                    <a href="{{ route('expense.transactions.create') }}"
                                        style="color: var(--accent-color); font-weight: 600; text-decoration: none; margin-top: 0.5rem; display: inline-block;">Add
                                        your first transaction</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding: 1rem 1.5rem;">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection