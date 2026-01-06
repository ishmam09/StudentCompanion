@extends('layout.app')

@section('content')
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('marketplace.index') }}"
                style="color: rgba(255,255,255,0.5); text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                &larr; Back to Marketplace
            </a>
            <h1 style="font-size: 2rem; font-weight: 700; color: white;">Transaction History</h1>
        </div>

        <!-- Active Listings -->
        <div style="margin-bottom: 3rem;">
            <h2
                style="color: #FFD700; font-size: 1.25rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem;">
                My Active Listings</h2>
            @if($listed->count() > 0)
                <div style="display: grid; gap: 1rem;">
                    @foreach($listed as $product)
                        <div
                            style="background: #1e1e1e; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1.5rem; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                            <img src="{{ asset($product->image_path) }}"
                                style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="flex: 1;">
                                <h3 style="color: white; font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $product->title }}</h3>
                                <span style="color: #FFD700; font-weight: 700;">৳{{ number_format($product->price, 2) }}</span>
                                <span
                                    style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-left: 0.5rem;">{{ $product->created_at->format('M d, Y') }}</span>
                            </div>
                            <form action="{{ route('marketplace.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Remove this listing?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: rgba(239, 68, 68, 0.1); padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.2); cursor: pointer; transition: background 0.2s;">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: rgba(255,255,255,0.5);">No active listings.</p>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">

            <!-- Sold Items -->
            <div id="sold">
                <h2
                    style="color: #4ade80; font-size: 1.25rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem;">
                    Sold Items</h2>
                @if($sold->count() > 0)
                    <div style="display: grid; gap: 1rem;">
                        @foreach($sold as $transaction)
                            <div
                                style="background: #1e1e1e; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(255,255,255,0.1);">
                                <div style="flex: 1;">
                                    <h3 style="color: white; font-size: 1rem; margin-bottom: 0.25rem;">
                                        {{ $transaction->product->title }}</h3>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Sold to: <span
                                            style="color: white; font-weight: 600;">{{ $transaction->buyer->name }}</span></div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="color: #4ade80; font-weight: 700;">+৳{{ number_format($transaction->amount, 2) }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.5);">
                                        {{ $transaction->created_at->format('M d') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: rgba(255,255,255,0.5);">No items sold yet.</p>
                @endif
            </div>

            <!-- Bought Items -->
            <div id="bought">
                <h2
                    style="color: #60a5fa; font-size: 1.25rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem;">
                    Purchased Items</h2>
                @if($bought->count() > 0)
                    <div style="display: grid; gap: 1rem;">
                        @foreach($bought as $transaction)
                            <div
                                style="background: #1e1e1e; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(255,255,255,0.1);">
                                <img src="{{ asset($transaction->product->image_path) }}"
                                    style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                <div style="flex: 1;">
                                    <h3 style="color: white; font-size: 1rem; margin-bottom: 0.25rem;">
                                        {{ $transaction->product->title }}</h3>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Seller: <span
                                            style="color: white; font-weight: 600;">{{ $transaction->seller->name }}</span></div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="color: #60a5fa; font-weight: 700;">-৳{{ number_format($transaction->amount, 2) }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.5);">
                                        {{ $transaction->created_at->format('M d') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: rgba(255,255,255,0.5);">No items purchased.</p>
                @endif
            </div>

        </div>
    </div>
@endsection