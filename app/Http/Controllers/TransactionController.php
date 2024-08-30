<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function invoiceStatus(Request $request)
{
    $payment = Payment::where('external_id', $request->external_id)->first();
    if ($payment == null) {
        return response([
            'status' => 'failed',
            'message' => 'Payment not found',
        ], 404);
    }

    $payment->status = strtolower($request->status);
    $payment->save();

    // Update or create a transaction record
    $transaction = Transaction::where('order_id', $payment->order_id)->first();
    if ($transaction == null) {
        $transaction = Transaction::create([
            'external_id' => $request->external_id,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'amount' => $request->amount,
            'payment_id' => $request->payment_id,
            'payment_channel' => $request->payment_channel,
            'description' => $request->description,
            'paid_at' => Carbon::parse($request->paid_at),
            'order_id' => $payment->order_id,
        ]);
    } else {
        $transaction->update([
            'external_id' => $request->external_id,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'amount' => $request->amount,
            'payment_id' => $request->payment_id,
            'payment_channel' => $request->payment_channel,
            'description' => $request->description,
            'paid_at' => Carbon::parse($request->paid_at),
            'order_id' => $payment->order_id,
        ]);
    }

    // Check if the payment status is 'paid'
    if (strtolower($request->status) == 'paid') {
        $order = Order::where('id', $payment->order_id)->first();
        if ($order != null && $order->order_status == 'waiting_for_payment') {
            $order->order_status = 'in-progress';
            $order->save();

            // Send notifications
            $this->firebaseService->sendNotification(
                $payment->user->notification_token,
                'Pembayaran Berhasil',
                'Pembayaran untuk Order ID ' . $transaction->order_id . ' telah terbayarkan',
                ''
            );

            $admins = User::where('role', 'admin')->get(); // Sesuaikan dengan struktur tabel Anda
            foreach ($admins as $admin) {
                $this->firebaseService->sendNotification(
                    $admin->notification_token,
                    'Pembayaran Berhasil',
                    'Pembayaran untuk Order ID ' . $transaction->order_id . ' telah terbayarkan',
                    ''
                );
            }
        }
    }

    return response([
        'status' => 'success',
        'message' => 'Payment status updated and order moved to in-progress',
        'payment_status' => $payment->status,
    ], 200);
}


    public function deleteTransaction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $transaction = Transaction::where('id', $request->id)->first();

        if ($transaction != null) {
            $transaction->delete();
            return response([
                'status' => 'success',
                'message' => 'Transaction deleted successfully',
            ], 200);
        }

        return response([
            'status' => 'failed',
            'message' => 'Transaction not found',
        ], 404);
    }
    public function getAllTransaction(Request $request)
{
    // Ambil semua transaksi bersama dengan informasi pengguna
    $transactions = Transaction::with('payment.user')->get();

    // Cek apakah ada transaksi
    if ($transactions->isEmpty()) {
        return response([
            'status' => 'failed',
            'message' => 'No transactions found',
        ], 404);
    }

    // Menyusun data transaksi dengan informasi user yang melakukan payment
    $transactionData = $transactions->map(function ($transaction) {
        $payment = $transaction->payment;
        $user = $payment ? $payment->user : null; // Cek apakah payment dan user tidak null

        return [
            'transaction_id' => $transaction->id,
            'order_id' => $transaction->order_id,
            'external_id' => $transaction->external_id,
            'payment_method' => $transaction->payment_method,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'paid_at' => $transaction->paid_at,
            'user' => $user ? [ // Pastikan user ada sebelum diakses
                'user_id' => $user->id,
                'name' => $user->name,
            ] : null,
        ];
    });

    return response([
        'status' => 'success',
        'message' => 'Transactions retrieved successfully',
        'transactions' => $transactionData,
    ], 200);
}

}
