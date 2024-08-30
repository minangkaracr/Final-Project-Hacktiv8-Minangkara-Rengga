<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
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
            "name"=> "required|string|max:255|unique:categories",
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'status' => 'success',
            'message'=> 'Categories Succesfully Created',
            'category'=>$category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json($category);
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
            'name' => 'required|string|max:255'
        ]);

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $category->update($request->all());
        return response()->json([
            'status'=> 'success',
            'message'=> 'Category updated successfully',
            'category'=>$category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $category = Category::find($id);
    //     if (!$category) {
    //         return response()->json([
    //             'message'=> 'Category not found'
    //         ], 404);
    //     }

    //     $category->delete();
    //     return response()->json([
    //         'status'=> 'success',
    //         'message'=> 'Category deleted successfully'
    //     ]);
    // }

    public function destroy(string $id){
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message'=> 'Category not found'
            ], 404);
        }

        $productsCount = $category->products()->count();

        if ($productsCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category cannot be deleted because it has associated products'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'status'=> 'success',
            'message'=> 'Category deleted successfully'
        ]);
    }
}
