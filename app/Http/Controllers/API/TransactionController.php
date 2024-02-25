<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\TransactionPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;

class TransactionController extends Controller
{

    public function fetch()
    {
        $transactions = Transaction::with('payment')->where('user_id', Auth::user()->id)->get();
        return ResponseFormatter::success($transactions, 'List Data Transaksi');
    }

    public function fetchDetail($id)
    {
        $transaction = Transaction::with('payment')->where('user_id', Auth::user()->id)->where('id', $id)->first();
        if ($transaction) {
            return ResponseFormatter::success($transaction, 'Detail Data Transaksi');
        }
        return ResponseFormatter::error(null, 'Tidak Data Transaksi', 404);
    }

    public function invoice($id)
    {
        $transaction = Transaction::findOrFail($id);
        $pdf = SnappyPdf::loadView('pdf.invoice', compact('transaction'))->setOption('page-width', '90')
            ->setOption('page-height', '130');
        $pdf->setOption('margin-top', 0);
        $pdf->setOption('margin-bottom', 0);
        $pdf->setOption('margin-left', 0);
        $pdf->setOption('margin-right', 0);


        $pdfBytes = $pdf->output();

        // Return the PDF bytes as a response
        return response()->streamDownload(function () use ($pdfBytes) {
            echo $pdfBytes;
        }, 'document.pdf');

        // return ResponseFormatter::success($pdf, 'Generate Invoice');
    }
    public function store(Request $request)
    {
        $code = 'COR.INV.' . mt_rand(0000, 9999);

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'sales_id' => Auth::user()->roles == 'SALES' ? Auth::user()->id : NULL,
            'code' => $code,
            'booking_date'    => Carbon::parse($request->booking_date)->format('Y-m-d'),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'address' => $request->address,
            'width' => $request->width,
            'height' => $request->height,
            'total_price' => $request->total_price,
            'status' => 'PENDING',
        ]);

        // down payment 10 %;
        $downPaymentPrice = $request->total_price / 100 * 10;
        $secondPayment = $request->total_price - $downPaymentPrice;

        // create payment
        $codePaymentFirst = 'TRX.1.' . Auth::user()->id . '.' . mt_rand(0000, 9999);
        $paymentFirst = TransactionPayment::create(
            [
                'transaction_id' => $transaction->id,
                'code' => $codePaymentFirst,
                'status' => 'PENDING',
                'payment_url' => NULL,
                'total_price' => $downPaymentPrice,
            ]
        );

        $secondPayment = TransactionPayment::create(
            [
                'transaction_id' => $transaction->id,
                'code' => 'TRX.2.' . Auth::user()->id . '.' . mt_rand(0000, 9999),
                'status' => 'PENDING',
                'payment_url' => NULL,
                'total_price' => $secondPayment,
            ]
        );

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');


        //Membuat Transaksi Midtrans

        $midtrans = [
            'transaction_details' => [
                'order_id' => $codePaymentFirst,
                'gross_amount' => (int) $downPaymentPrice,
            ],

            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],

            'enable_payments' => ['bca_va', 'permata_va', 'bni_va', 'bri_va', 'gopay'],
            'vtweb' => [],
        ];

        //Memanggil Midtrans ke API
        try {
            //Ambil Halaman Payment Midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $paymentFirst->payment_url = $paymentUrl;
            $paymentFirst->save();
            //mengembalikan data ke api
            return ResponseFormatter::success($paymentFirst, 'Transaksi Berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }
    }

    public function payment($transactionPaymentId)
    {
        $payment = TransactionPayment::find($transactionPaymentId);

        if ($payment->status == 'PENDING' && $payment->payment_url != NULL) {
            return ResponseFormatter::success($payment, 'Transaksi Berhasil');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Membuat Transaksi Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $payment->code,
                'gross_amount' => (int) $payment->total_price,
            ],

            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],

            'enable_payments' => ['bca_va', 'permata_va', 'bni_va', 'bri_va', 'gopay'],
            'vtweb' => [],
        ];

        try {
            //Ambil Halaman Payment Midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $payment->payment_url = $paymentUrl;
            $payment->save();
            //mengembalikan data ke api
            return ResponseFormatter::success($payment, 'Transaksi Berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }
    }
}
