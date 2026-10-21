<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.wishlist', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $data = $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'added' => false,
                'message' => 'Removed from wishlist',
            ]);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $data['product_id'],
        ]);

        return response()->json([
            'success' => true,
            'added' => true,
            'message' => 'Added to wishlist',
        ]);
    }

    public function count()
    {
        return response()->json([
            'count' => Wishlist::where('user_id', auth()->id())->count(),
        ]);
    }
}
