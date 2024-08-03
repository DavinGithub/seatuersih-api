<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderRequest;
use App\Models\Order;
use App\Models\Shoe;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addOrder(AddOrderRequest $request)
    {
        $date = date('YmdHis');
        $nomor_pemesanan = $request->user()->id . $date;

        $orderStatus = $request->order_status ?? 'pending';

        $order = Order::create([
            'order_type' => $request->order_type,
            'order_number' => $nomor_pemesanan,
            'detail_address' => $request->detail_address,
            'phone' => $request->phone,
            'total_price' => $request->total_price,
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes,
            'order_status' => $orderStatus,
            'laundry_id' => $request->laundry_id,
            'user_id' => $request->user_id,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
        ]);

        return response()->json([
            'message' => 'Order added successfully',
            'order' => $order,
        ], 201);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'detail_address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'total_price' => 'sometimes|required|numeric',
            'pickup_date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string|max:255',
            'order_status' => 'sometimes|nullable|string|in:pending,driver on the way to location,shoe being cleaned,completed,decline',
            'kabupaten' => 'sometimes|required|string|max:255',
            'kecamatan' => 'sometimes|required|string|max:255',
        ]);

        $order = Order::find($request->id);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $order->update($request->only([
            'detail_address',
            'phone',
            'total_price',
            'pickup_date',
            'notes',
            'order_status',
            'kabupaten',
            'kecamatan',
        ]));

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order,
        ], 200);
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
        ], 200);
    }

    public function getOrders()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();
        if ($orders->isEmpty()) {   
            return response()->json([
                'message' => 'No orders found',
            ], 200);
        }

        return response()->json([
            'message' => 'Orders list',
            'data' => $orders,
        ], 200);
    }

    public function getOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Order details',
            'data' => $order,
        ], 200);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);
    
        $order = Order::find($request->order_id);
        $shoes = Shoe::where('order_id', $request->order_id)->get();
        if ($shoes->isEmpty()) {
            return response()->json([
                'message' => 'No shoes found',
            ], 200);
        }
    
        $totalPrice = $shoes->sum('price');
        $order->total_price = $totalPrice;
        $order->save();
    
        return response()->json([
            'message' => 'Checkout success',
            'order' => $order,
        ]);
    }

    public function getOrdersByStatus($status)
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
                       ->where('order_status', $status)
                       ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No orders found with the specified status',
            ], 404);
        }

        return response()->json([
            'message' => 'Orders with status: ' . $status,
            'data' => $orders,
        ], 200);
    }
}
