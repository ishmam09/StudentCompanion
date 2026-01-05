@extends('layout.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="margin-bottom: 0.5rem;">Expense Tracker</h1>
                <p class="text-muted">Manage your finances and track your spending habits.</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('expense.transactions.index') }}" class="btn"
                    style="background: var(--surface); color: var(--primary-color); border: 1px solid var(--border-color);">
                    View Transactions
                </a>
                <a href="{{ route('expense.transactions.create') }}" class="btn btn-primary">
                    + Add Transaction
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="card-grid" style="margin-bottom: 3rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <!-- Monthly Expenses -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div
                        style="width: 40px; height: 40px; background: #FEF2F2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                            <path d="M12 19V5M5 12l7-7 7 7" />
                        </svg>
                    </div>
                    <span
                        style="font-size: 0.8rem; font-weight: 600; color: #DC2626; background: #FEF2F2; padding: 0.2rem 0.6rem; border-radius: 99px;">THIS
                        MONTH</span>
                </div>
                <div class="card-value" style="color: #DC2626;">${{ number_format($monthlyExpenses, 2) }}</div>
                <div class="card-title">Total Expenses</div>
            </div>

            <!-- Monthly Income -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div
                        style="width: 40px; height: 40px; background: #ECFDF5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                            <path d="M12 5v14M5 12l7 7 7-7" />
                        </svg>
                    </div>
                    <span
                        style="font-size: 0.8rem; font-weight: 600; color: #059669; background: #ECFDF5; padding: 0.2rem 0.6rem; border-radius: 99px;">THIS
                        MONTH</span>
                </div>
                <div class="card-value" style="color: #059669;">${{ number_format($monthlyIncome, 2) }}</div>
                <div class="card-title">Total Income</div>
            </div>

            <!-- Balance -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div
                        style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                </div>
                <div class="card-value">${{ number_format($monthlyIncome - $monthlyExpenses, 2) }}</div>
                <div class="card-title">Net Balance</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
            <!-- Recent Transactions -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.25rem;">Recent Transactions</h3>
                    <a href="{{ route('expense.transactions.index') }}" class="btn-link">View All</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($recentTransactions as $transaction)
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div
                                    style="width: 40px; height: 40px; background: {{ $transaction->type == 'expense' ? '#FEF2F2' : '#ECFDF5' }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                    {{ $transaction->type == 'expense' ? '💸' : '💰' }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->description }}
                                    </div>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                        {{ $transaction->transaction_date->format('M d, Y') }} •
                                        {{ optional($transaction->category)->name ?? 'Uncategorized' }}
                                    </div>
                                </div>
                            </div>
                            <div
                                style="font-weight: 700; color: {{ $transaction->type == 'expense' ? '#DC2626' : '#059669' }};">
                                {{ $transaction->type == 'expense' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted" style="text-align: center; padding: 2rem;">No recent transactions.</p>
                    @endforelse
                </div>
            </div>

            <!-- Spending by Category -->
            <div class="card">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem;">Top Spending Categories</h3>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @forelse($spendingByCategory as $category)
                        @php
                            $percentage = $monthlyExpenses > 0 ? ($category->total / $monthlyExpenses) * 100 : 0;
                        @endphp
                        <div>
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                <span style="font-weight: 600; color: var(--primary-color);">{{ $category->name }}</span>
                                <span style="color: var(--text-secondary);">${{ number_format($category->total, 2) }}
                                    ({{ round($percentage) }}%)</span>
                            </div>
                            <div
                                style="width: 100%; height: 8px; background: var(--border-color); border-radius: 4px; overflow: hidden;">
                                <div
                                    style="width: {{ $percentage }}%; height: 100%; background: var(--accent-color); border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted" style="text-align: center; padding: 2rem;">No spending data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection