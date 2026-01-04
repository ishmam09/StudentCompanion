<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::available();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $products = $query->latest()->get();
        
        // Fetch history for dropdowns
        $bought = Transaction::with('product')->where('buyer_id', Auth::id())->latest()->get();
        $sold = Transaction::with('product')->where('seller_id', Auth::id())->latest()->get();

        return view('marketplace.index', compact('products', 'bought', 'sold'));
    }

    public function create()
    {
        return view('marketplace.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048', // Max 2MB
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('products'), $filename);
                $imagePath = 'products/' . $filename;
            }

            Product::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'price' => $request->price,
                'image_path' => $imagePath,
                'status' => 'available',
            ]);

            return redirect()->route('marketplace.index')->with('success', 'Product listed successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Marketplace Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function buy($id)
    {
        $product = Product::available()->findOrFail($id);

        if ($product->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot buy your own product.');
        }

        // Simulate purchase
        $product->update(['status' => 'sold']);

        Transaction::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'amount' => $product->price,
        ]);

        return redirect()->route('marketplace.index')->with('success', 'Product purchased successfully!');
    }

    public function history()
    {
        $products = Product::where('user_id', Auth::id())->latest()->get();
        // Separate sold and bought items
        $sold = Transaction::where('seller_id', Auth::id())->with('product')->get();
        $bought = Transaction::where('buyer_id', Auth::id())->with('product')->get();
        
        // Active listings (available)
        $listed = Product::where('user_id', Auth::id())->where('status', 'available')->get();

        return view('marketplace.history', compact('sold', 'bought', 'listed'));
    }

    public function undoPurchase($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->buyer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $product = $transaction->product;
        $product->status = 'available';
        $product->save();

        $transaction->delete();

        return redirect()->route('marketplace.index')->with('success', 'Purchase undone. Item is available again.');
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        if ($product->status === 'sold') {
             return redirect()->back()->with('error', 'Cannot remove sold products.');
        }

        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('marketplace.index')->with('success', 'Product removed.');
    }
}
