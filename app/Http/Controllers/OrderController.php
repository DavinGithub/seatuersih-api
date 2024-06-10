<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addOrder(AddOrderRequest $request)
    {
        $user = auth()->user();
        $date = date('YmdHis');
        $nomor_pemesanan = $user->id. $date;

        $order = Order::create([
            'order_type' => $request->order_type,
            'order_number' => $nomor_pemesanan,
            'address' => $request->address,
            'phone' => $request->phone,
            'total_price' => $request->total_price,
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes,
            'user_id' => $user->id,

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
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|integer',
            'total_price' => 'sometimes|required|numeric',
            'pickup_date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string|max:255',
        ]);

        $order = Order::find($request['id']);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $order->update($request);

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
}
