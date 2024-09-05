<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderRequest;
use App\Services\FirebaseService;

use App\Models\Order;
use Carbon\Carbon;
use App\Models\Shoe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $firebaseService;

      public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function addOrder(AddOrderRequest $request)
    {
        $date = date('YmdHis');
        $nomor_pemesanan = $request->user()->id . $date;

        $orderStatus = $request->order_status ?? 'pending';

        $order = Order::create([
            'order_type' => $request->order_type,
            'order_number' => $nomor_pemesanan,
            'detail_address' => $request->detail_address,
            'total_price' => $request->total_price,
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes,
            'order_status' => $orderStatus,
            'laundry_id' => $request->laundry_id,
            'user_id' => $request->user_id,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
        ]);

        $order->load('user');

        $this->firebaseService->sendToAdmin(
            'Pesanan Baru Masuk',
            'Seseorang Baru Saja Menambahkan Order',
            '',
            ['route' => '/transaction_page.screen', 'data' => $order->id]
        );

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
            'total_price' => 'sometimes|required|numeric',
            'pickup_date' => 'sometimes|required|date',
            'notes' => 'sometimes|nullable|string|max:255',
            'order_status' => 'sometimes|nullable|string|in:pending,waiting_for_payment,in-progress,completed,decline,reviewed',
            'kabupaten' => 'sometimes|required|string|max:255',
            'kecamatan' => 'sometimes|required|string|max:255',
            'decline_note' => 'sometimes|nullable|string|max:255',
        ]);
    
        $order = Order::find($request->id);
        $previousStatus = $order->order_status;
    
        $order->update($request->only([
            'detail_address',
            'total_price',
            'pickup_date',
            'notes',
            'order_status',
            'kabupaten',
            'kecamatan',
            'decline_note',
        ]));
    
        if ($order->wasChanged('order_status')) {
            $user = $order->user;
            $title = 'Pembaruan Pesanan';
            $body = ''; 
            $imageUrl = 'https://example.com/image.jpg';
    
            if ($previousStatus === 'pending' && $order->order_status === 'waiting_for_payment') {
                $body = 'Pesanan Anda sudah diterima. Harap lakukan pembayaran!';
            } elseif ($previousStatus === 'waiting_for_payment' && $order->order_status === 'in-progress') {
                $body = 'Pembayaran telah diterima. Pesanan Anda sedang diproses!';
                
                // Kirim notifikasi ke admin
                $this->firebaseService->sendToAdmin(
                    'Pesanan Telah Dibayar',
                    'Pesanan Dengan ID ' . $order->id .  'telah dibayar, tolong di proses',
                    '',
                    ['route' => '/transaction_page.screen', 'data' => $order->id]
                );
            } elseif ($previousStatus === 'in-progress' && $order->order_status === 'completed') {
                $body = 'Pesanan Anda sudah selesai!';
            } elseif ($previousStatus === 'completed' && $order->order_status === 'reviewed') {
                $this->firebaseService->sendToAdmin(
                    'Pesanan Direview',
                    'Pesanan telah diberi review oleh seseorang',
                    '',
                    ['route' => '/transaction_page.screen', 'data' => $order->id]
                );
            } elseif ($previousStatus === 'pending' && $order->order_status === 'decline') {
                $body = 'Maaf, pesanan Anda ditolak!';
                if (!empty($order->decline_note)) {
                    $body .= ' ' . $order->decline_note; 
                }
            }
            
            
    
            if (!empty($body)) { 
                $this->firebaseService->sendNotification($user->notification_token, $title, $body, $imageUrl);
            }
        }
    
        $order->load('user');
    
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
        $orders = Order::with('user')->where('user_id', $user->id)->get();
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
        $order = Order::with('user')->find($id);
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
    
        $order->load('user');
    
        return response()->json([
            'message' => 'Checkout success',
            'order' => $order,
        ]);
    }

   public function getOrdersByStatus($status)
{
    $orders = Order::with('user')
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

public function getOrdersByStatusUser($status)
{
    $user = Auth::user(); 

    $orders = Order::with('user')
                   ->where('order_status', $status)
                   ->where('user_id', $user->id)
                   ->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'message' => 'No orders found with the specified status for the logged-in user',
        ], 404);
    }

    return response()->json([
        'message' => 'Orders with status: ' . $status . ' for the logged-in user',
        'data' => $orders,
    ], 200);
}

      
    public function getChart()
{
    $today = Carbon::today();
    $lastMonth = $today->copy()->subDays(30);

    $data = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereBetween('created_at', [$lastMonth->format('Y-m-d 00:00:00'), $today->format('Y-m-d 23:59:59')])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    $dates = [];
    $currentDate = $lastMonth->copy();
    while ($currentDate->lte($today)) {
        $dates[$currentDate->format('Y-m-d')] = 0;
        $currentDate->addDay();
    }

    foreach ($data as $entry) {
        $dates[$entry->date] = $entry->total;
    }

    $formattedData = collect($dates)->map(function ($total, $date) {
        return [
            'date' => $date,
            'total' => $total
        ];
    })->values();

    return response()->json($formattedData);
}

public function getChartByOrderType($orderType)
{
    $today = Carbon::today();
    $lastMonth = $today->copy()->subDays(30);

    $data = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->where('order_type', $orderType)
        ->whereBetween('created_at', [$lastMonth->format('Y-m-d 00:00:00'), $today->format('Y-m-d 23:59:59')])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    $dates = [];
    $currentDate = $lastMonth->copy();
    while ($currentDate->lte($today)) {
        $dates[$currentDate->format('Y-m-d')] = 0;
        $currentDate->addDay();
    }

    foreach ($data as $entry) {
        $dates[$entry->date] = $entry->total;
    }

    $formattedData = collect($dates)->map(function ($total, $date) {
        return [
            'date' => $date,
            'total' => $total
        ];
    })->values();

    return response()->json($formattedData);
}

    public function getTotalOrdersByType($orderType)
    {
        // Validasi input yang diterima hanya 'deep_clean' atau 'regular_clean'
        if (!in_array($orderType, ['deep_clean', 'regular_clean'])) {
            return response()->json([
                'message' => 'Invalid order type',
            ], 400);
        }

        // Hitung total berdasarkan tipe order yang diinput dari URL
        $totalOrders = Order::where('order_type', $orderType)->count();

        // Menentukan pesan output berdasarkan tipe yang diinput
        $message = 'Total orders for ' . str_replace('_', ' ', $orderType);

        return response()->json([
            'message' => $message,
            'data' => [
                $orderType => $totalOrders,
            ],
        ], 200);
    }



    }
