@extends('layout.app')

@section('content')
    <style>
        .type-card {
            padding: 0.75rem;
            text-align: center;
            border: 2px solid var(--border-color);
            background: var(--background);
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all 0.2s;
        }

        /* Active State for Expense */
        input[value="expense"]:checked+.type-card {
            border-color: #DC2626;
            background: #FEF2F2;
            color: #DC2626;
        }

        /* Active State for Income */
        input[value="income"]:checked+.type-card {
            border-color: #059669;
            background: #ECFDF5;
            color: #059669;
        }
    </style>

    <div style="max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('expense.transactions.index') }}"
                style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                ← Back to Transactions
            </a>
            <h1>Edit Transaction</h1>
        </div>

        <div class="card">
            <form action="{{ route('expense.transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Type Selection -->
                <div class="form-group">
                    <label class="form-label">Transaction Type</label>
                    <div style="display: flex; gap: 1rem;">
                        <label style="flex: 1; cursor: pointer;">
                            <input type="radio" name="type" value="expense" {{ $transaction->type == 'expense' ? 'checked' : '' }} style="display: none;">
                            <div class="type-card">
                                Expense 💸
                            </div>
                        </label>
                        <label style="flex: 1; cursor: pointer;">
                            <input type="radio" name="type" value="income" {{ $transaction->type == 'income' ? 'checked' : '' }} style="display: none;">
                            <div class="type-card">
                                Income 💰
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount" class="form-label">Amount</label>
                    <div style="position: relative;">
                        <span
                            style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary);">$</span>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-input"
                            style="padding-left: 2rem; font-size: 1.25rem; font-weight: 600;"
                            value="{{ old('amount', $transaction->amount) }}" required>
                    </div>
                    @error('amount') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-input"
                        value="{{ old('description', $transaction->description) }}" required>
                    @error('description') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-input">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="transaction_date" class="form-label">Date</label>
                    <input type="date" name="transaction_date" id="transaction_date" class="form-input"
                        value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
                    @error('transaction_date') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea name="notes" id="notes" class="form-input"
                        rows="3">{{ old('notes', $transaction->notes) }}</textarea>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Transaction</button>
                </div>
            </form>
        </div>
    </div>


@endsection