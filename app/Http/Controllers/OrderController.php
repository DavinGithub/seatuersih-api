<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addOrder(AddOrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create([
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'total_price' => $validated['total_price'],
            'pickup_date' => $validated['pickup_date'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => $validated['user_id'], // Adjust accordingly
            'shoes_id' => $validated['shoes_id'], // Adjust accordingly
        ]);

        return response()->json([
            'message' => 'Order added successfully',
            'order' => $order,
        ], 201);
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|integer',
            'total_price' => 'sometimes|required|numeric',
            'pickup_date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string|max:255',
            'user_id' => 'sometimes|required|integer|exists:users,id', // Adjust accordingly
            'shoes_id' => 'sometimes|required|integer|exists:shoes,id', // Adjust accordingly
        ]);

        $order = Order::find($validated['id']);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $order->update($validated);

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
        $orders = Order::all();
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
