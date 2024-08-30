<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=> "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "price"=> "required|numeric|min:0",
        ]);

        $product = Product::create($request->all());

        return response()->json([
            "message"=> "Product created Successfully",
            "product"=> $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "message"=> "Product not Found"
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name"=> "required|string|max:255",
            "category_id" => "required|exists:categories,id",
            "price"=> "required|numeric|min:0",
        ]);

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message"=> "Product not Found"
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            "message"=> "Product updated successfully",
            "product"=> $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "message"=> "Product not Found"
            ], 404);
        }

        $product->delete();
        return response()->json([
            "message"=> "Product deleted successfully"
        ]);
    }
}
