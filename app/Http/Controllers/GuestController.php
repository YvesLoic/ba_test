<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Lecture des produits
     *
     * @param \Illuminate\Http\Request $request requette
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $products = Product::where('published', 1)->get();
        // return view('welcome', compact('products'));
        return redirect()->route('home');
    }

    /**
     * Lecture d'un produit
     *
     * @param \Illuminate\Http\Request $request requette
     * @param int                      $id      identifiant
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $product = Product::where(['published' => 1, 'id' => $id])->first();
        // dd($product->user_id);
        $autor = User::find($product->user_id);
        return view('details', compact('product', 'autor'));
    }
}
