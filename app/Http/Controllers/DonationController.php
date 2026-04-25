<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::whereIn('status', ['settlement', 'capture'])
            ->latest()
            ->take(6)
            ->get();

        $totalDonation = Donation::whereIn('status', ['settlement', 'capture'])
            ->sum('amount');

        return view('donations.index', compact('donations', 'totalDonation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'integer', 'min:10000'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $this->configureMidtrans();

        $orderId = 'DONASI-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));

        $donation = Donation::create([
            'order_id' => $orderId,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'amount' => $validated['amount'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $donation->order_id,
                'gross_amount' => (int) $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->name,
                'email' => $donation->email,
                'phone' => $donation->phone,
            ],
            'item_details' => [
                [
                    'id' => 'DONASI-HEWAN',
                    'price' => (int) $donation->amount,
                    'quantity' => 1,
                    'name' => 'Donasi untuk Hewan',
                ],
            ],
            'callbacks' => [
                'finish' => route('donations.success'),
                'unfinish' => route('donations.pending'),
                'error' => route('donations.failed'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $donation->update([
                'snap_token' => $snapToken,
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $donation->order_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());

            $donation->update([
                'status' => 'failed_create_snap',
                'midtrans_response' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi Midtrans.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Notification', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json([
                'message' => 'Invalid notification payload',
            ], 400);
        }

        $serverKey = config('midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            return response()->json([
                'message' => 'Invalid signature key',
            ], 403);
        }

        $donation = Donation::where('order_id', $orderId)->first();

        if (!$donation) {
            return response()->json([
                'message' => 'Donation not found',
            ], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $status = 'capture';
            } else {
                $status = 'challenge';
            }
        } elseif ($transactionStatus === 'settlement') {
            $status = 'settlement';
        } elseif ($transactionStatus === 'pending') {
            $status = 'pending';
        } elseif ($transactionStatus === 'deny') {
            $status = 'deny';
        } elseif ($transactionStatus === 'expire') {
            $status = 'expire';
        } elseif ($transactionStatus === 'cancel') {
            $status = 'cancel';
        } else {
            $status = $donation->status;
        }

        $donation->update([
            'status' => $status,
            'payment_type' => $payload['payment_type'] ?? null,
            'midtrans_response' => $payload,
        ]);

        return response()->json([
            'message' => 'Notification processed successfully',
        ]);
    }

    public function success()
    {
        return view('donations.success');
    }

    public function pending()
    {
        return view('donations.pending');
    }

    public function failed()
    {
        return view('donations.failed');
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }
}