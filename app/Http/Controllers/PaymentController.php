<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        $user = auth()->user();
        $order = Order::find($request->order_id);
        $external_id = (string) date('YmdHis');
        $description = 'Membayar Laundry';
        $amount = $order->total_price;

        $transaction = Payment::where('order_id', $order->id)->first();
        if ($transaction != null) {
            if ($transaction->status == 'pending') {
                return response([
                    'status' => 'success',
                    'message' => 'Payment created successfully',
                    'checkout_link' => $transaction->checkout_link,
                ], 201);
            } elseif ($transaction->status == 'paid') {
                return response([
                    'status' => 'failed',
                    'message' => 'Payment already paid',
                ], 400);
            }
        }

        $options = [
            'external_id' => $external_id,
            'description' => $description,
            'amount' => $amount,
            'currency' => 'IDR',
            'payment_methods' => ["OVO", "DANA", "SHOPEEPAY", "LINKAJA", "JENIUSPAY", "QRIS"]
        ];

        $response = $this->xenditService->createInvoice($options);
        dd($response->json());
        if (isset($response['id'])) {
            $payment = new Payment();
            $payment->status = 'pending';
            $payment->invoice_id = $response['id'];
            $payment->checkout_link = $response['invoice_url'];
            $payment->external_id = $external_id;
            $payment->user_id = $user->id;
            $payment->order_id = $order->id;
            $payment->save();

            return response([
                'status' => 'success',
                'message' => 'Payment created successfully',
                'checkout_link' => $response['invoice_url'],
                'description' => $description,
            ], 201);
        } else {
            return response([
                'status' => 'error',
                'message' => 'Failed to create payment. Invalid response from payment gateway.',
            ], 500);
        }
    }

    public function expirePayment($id)
    {
        $payment = Payment::where('order_id', $id)->first();
        if ($payment == null) {
            return response([
                'status' => 'failed',
                'message' => 'Payment not found',
            ], 404);
        }

        if ($payment->status == 'expired') {
            return response([
                'status' => 'failed',
                'message' => 'Payment already expired',
            ], 400);
        }

        $this->xenditService->expireInvoice($payment->invoice_id);
        $payment->status = 'expired';
        $payment->save();

        return response([
            'status' => 'success',
            'message' => 'Payment expired',
        ], 200);
    }

    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $payment = Payment::where('order_id', $request->order_id)->first();
        if ($payment == null) {
            return response([
                'status' => 'failed',
                'message' => 'Payment not found',
            ], 404);
        }

        $response = $this->xenditService->getInvoice($payment->invoice_id);
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status'])) {
                $payment->status = strtolower($data['status']);
                $payment->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment status updated',
                    'payment_status' => $payment->status
                ], 200);
            } else {
                return response()->json(['error' => 'Status tidak ditemukan dalam respons'], 400);
            }
        } else {
            return response()->json(['error' => 'Gagal mendapatkan status invoice'], 500);
        }
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

        return response([
            'status' => 'success',
            'message' => 'Payment status updated',
            'payment_status' => $payment->status,
        ], 200);
    }

    public function getInvoiceUser(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $payment = Payment::where('order_id', $request->order_id)->first();
        if ($payment == null) {
            return response([
                'status' => 'failed',
                'message' => 'Payment not found. You can create payment first',
            ], 404);
        }

        return response([
            'status' => 'success',
            'message' => 'Payment for order ' . $payment->order->nama_pemesan,
            'payment' => $payment->only([
                'status',
                'checkout_link',
                'external_id',
                'user_id',
                'order_id'
            ]),
        ], 200);
    }
}

