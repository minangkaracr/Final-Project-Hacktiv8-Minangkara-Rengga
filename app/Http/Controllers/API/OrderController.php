<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $userName = Auth::user()->name;

        $orders = Order::where("user_id", $userId)->with('product')->get();
        $totalPrice = $orders->sum('total_price');

        $response =[
            'Nama'=> $userName,
            'Total_Price'=> $totalPrice,
            'Data_order' => $orders->map(function ($order) {
                return [
                    'id'=> $order->id,
                    'user_id'=>(int)$order->user_id,
                    'product_id'=>(int)$order->product_id,
                    'product_name'=>$order->product->name,
                    'quantity'=>(int)$order->quantity,
                    'total_price'=>(int)$order->total_price,
                    'order_date'=>$order->order_date,
                    'created_at'=>$order->created_at,
                    'updated_at'=>$order->updated_at
                ];
            }),
        ];

        return response()->json($response);
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
            "product_id"=> "required|exists:products,id",
            "quantity"=>'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'message'=>'Product not found'
            ], 404);
        }

        $userId = Auth::user()->id;

        $totalPrice = $product->price * $request->quantity;

        $order = Order::create([
            'user_id'=> $userId,
            'product_id'=> $request->product_id,
            'quantity'=> $request->quantity,
            'total_price'=> $totalPrice,
        ]);

        return response()->json([
            'status'=>'Success',
            'message'=>'Order created succesfully',
            'order'=> $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message'=> 'Order not found'
            ], 404);
        }

        return response()->json($order);
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
            "product_id"=> "required|exists:products,id",
            "quantity"=>'required|integer|min:1'
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message'=> 'Order not found'
            ], 404);
        }

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'message'=> 'Product not found'
            ], 404);
        }

        $userId = Auth::user()->id;

        $totalPrice = $product->price * $request->quantity;

        $order->update([
            'user_id'=> $userId,
            'product_id'=> $request->product_id,
            'quantity'=> $request->quantity,
            'total_price'=> $totalPrice
        ]);

        return response()->json([
            'status'=>'Success',
            'message'=> 'Order Created Successfully',
            'order'=> $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message'=> 'Order not Found'
            ], 404);
        }

        $order->delete();
        return response()->json([
            'status'=> 'Success',
            'message'=> 'Order Deleted Successfully'
        ]);
    }

    public function report(){
        // return "aa";
        $orders = Order::with(['product.category', 'user'])->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_price');

        $orderData = $orders->map(function($order){
            return [
                'id'=> $order->id,
                'product_name' => $order->product->name,
                'category_name'=>$order->quantity,
                'total_price'=>$order->total_price,
                'customer_name'=>$order->user->name,
                'order_date'=> $order->order_date,
            ];
        });

        $response = [
            'status' => 'success',
            'message' => 'Order Report Generated Successfully',
            'data' => [
                'total_orders' => $totalOrders,
                'total_revenue'=> $totalRevenue,
                'orders' => $orderData
            ]
        ];

        return response()->json($response);
    }
}
