@extends('layout.app')

@section('content')
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
        <!-- Header & Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 0.5rem;">Marketplace</h1>
                <p style="color: rgba(255,255,255,0.8);">Buy and sell items within the community.</p>
            </div>
            <div style="display: flex; gap: 1rem; position: relative;">

                <!-- Purchased Dropdown -->
                <div class="dropdown-container">
                    <button onclick="toggleDropdown('purchasedDropdown')"
                        style="padding: 0.75rem 1.5rem; background: #FFD700; color: black; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                        Purchased Items <span style="font-size: 0.8rem;">▼</span>
                    </button>
                    <div id="purchasedDropdown" class="dropdown-content"
                        style="display: none; position: absolute; top: 110%; right: 0; width: 320px; background: #1e1e1e; border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 50; max-height: 400px; overflow-y: auto;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <h3 style="margin: 0; color: #60a5fa; font-size: 1rem;">Purchased Items</h3>
                        </div>
                        <div style="padding: 0.5rem;">
                            @forelse($bought as $transaction)
                                <div
                                    style="display: flex; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05); align-items: center;">
                                    <img src="{{ asset($transaction->product->image_path) }}"
                                        style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                    <div style="flex: 1; overflow: hidden;">
                                        <div
                                            style="color: white; font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $transaction->product->title }}</div>
                                        <div style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">
                                            -৳{{ number_format($transaction->amount, 2) }}</div>
                                    </div>
                                    <form action="{{ route('marketplace.undo', $transaction->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Undo Purchase"
                                            style="background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; font-weight: bold; padding: 0; line-height: 1;">✕</button>
                                    </form>
                                </div>
                            @empty
                                <div
                                    style="padding: 1.5rem; text-align: center; color: rgba(255,255,255,0.4); font-size: 0.9rem;">
                                    No items purchased</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sold Dropdown -->
                <div class="dropdown-container">
                    <button onclick="toggleDropdown('soldDropdown')"
                        style="padding: 0.75rem 1.5rem; background: #FFD700; color: black; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                        Sold Items <span style="font-size: 0.8rem;">▼</span>
                    </button>
                    <div id="soldDropdown" class="dropdown-content"
                        style="display: none; position: absolute; top: 110%; right: 0; width: 320px; background: #1e1e1e; border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 50; max-height: 400px; overflow-y: auto;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <h3 style="margin: 0; color: #4ade80; font-size: 1rem;">Sold Items</h3>
                        </div>
                        <div style="padding: 0.5rem;">
                            @forelse($sold as $transaction)
                                <div
                                    style="display: flex; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <img src="{{ asset($transaction->product->image_path) }}"
                                        style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                    <div style="flex: 1; overflow: hidden;">
                                        <div
                                            style="color: white; font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $transaction->product->title }}</div>
                                        <div style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">
                                            +৳{{ number_format($transaction->amount, 2) }}</div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    style="padding: 1.5rem; text-align: center; color: rgba(255,255,255,0.4); font-size: 0.9rem;">
                                    No items sold</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <a href="{{ route('marketplace.create') }}"
                    style="padding: 0.75rem 1.5rem; background: #FFD700; color: black; border: none; border-radius: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                    + Sell Item
                </a>
            </div>
        </div>

        <!-- Search -->
        <form action="{{ route('marketplace.index') }}" method="GET" style="margin-bottom: 2rem;">
            <div style="position: relative; max-width: 100%;">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                    style="width: 100%; padding: 1rem 1rem 1rem 3rem; background: #1e1e1e; border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; outline: none; font-size: 1rem;">
                <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #FFD700;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </form>

        <!-- Product Table -->
        @if($products->count() > 0)
            <div
                style="background: #1e1e1e; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.1); overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #2d2d2d; border-bottom: 2px solid rgba(255,255,255,0.1);">
                                <th style="padding: 1.25rem; text-align: left; color: #FFD700; font-weight: 700;">Image</th>
                                <th style="padding: 1.25rem; text-align: left; color: #FFD700; font-weight: 700; width: 35%;">
                                    Product Details</th>
                                <th style="padding: 1.25rem; text-align: left; color: #FFD700; font-weight: 700;">Price</th>
                                <th style="padding: 1.25rem; text-align: left; color: #FFD700; font-weight: 700;">Seller</th>
                                <th style="padding: 1.25rem; text-align: right; color: #FFD700; font-weight: 700;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s;">
                                    <td style="padding: 1.25rem;">
                                        <div
                                            style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                                            @if($product->image_path)
                                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->title }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div
                                                    style="width: 100%; height: 100%; background: #333; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 0.8rem;">
                                                    No Img</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <h3 style="font-size: 1.1rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">
                                            {{ $product->title }}</h3>
                                        <div
                                            style="display: inline-block; background: rgba(255, 215, 0, 0.1); color: #FFD700; padding: 0.1rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem; border: 1px solid rgba(255, 215, 0, 0.2);">
                                            {{ $product->category }}
                                        </div>
                                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.4;">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <span
                                            style="font-size: 1.2rem; font-weight: 700; color: #4ade80;">৳{{ number_format($product->price, 2) }}</span>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div
                                                style="width: 24px; height: 24px; background: #FFD700; border-radius: 50%; color: black; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.7rem;">
                                                {{ substr($product->user->name, 0, 1) }}
                                            </div>
                                            <span style="font-size: 0.95rem; color: white;">{{ $product->user->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem; text-align: right;">
                                        @if($product->user_id !== Auth::id())
                                            <form action="{{ route('marketplace.buy', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    style="padding: 0.6rem 1.25rem; background: white; color: black; border: 2px solid white; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.9rem; transition: all 0.2s;">
                                                    Buy Now
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('marketplace.destroy', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="padding: 0.6rem 1.25rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.9rem; transition: all 0.2s;">
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div
                style="text-align: center; padding: 4rem; color: rgba(255,255,255,0.6); background: #1e1e1e; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1);">
                <p style="font-size: 1.1rem;">No products found.</p>
            </div>
        @endif
    </div>

    <style>
        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        button[type="submit"]:hover {
            background: #FFD700;
            border-color: #FFD700;
        }
    </style>

    <script>
        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            var allDropdowns = document.getElementsByClassName("dropdown-content");

            // Close others
            for (var i = 0; i < allDropdowns.length; i++) {
                if (allDropdowns[i].id !== id) {
                    allDropdowns[i].style.display = "none";
                }
            }

            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            } else {
                dropdown.style.display = "block";
            }
        }

        // Close on outside click
        window.onclick = function (event) {
            if (!event.target.matches('button') && !event.target.matches('button *')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection