<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionPayment;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
          //set konfigurasi midtrans
          Config::$serverKey = config('services.midtrans.serverKey');
          Config::$isProduction = config('services.midtrans.isProduction');
          Config::$isSanitized = config('services.midtrans.isSanitized');
          Config::$is3ds = config('services.midtrans.is3ds');

          //buat instance midtrans
          $notification = new Notification();

          //assign ke variable untuk memudahkan coding

          $status = $notification->transaction_status;

          $payment = TransactionPayment::where('code', $notification->order_id)->first();
          // handler notification status midtrans
          if ($status == "settlement") {
              $payment->status = 'PAID';

              $transaction = Transaction::where('id', $payment->transaction_id)->first();

              $totalTransactionPaymentPaid = TransactionPayment::where('transaction_id', $transaction->id)->where('status','PAID')->count();

              if($totalTransactionPaymentPaid == 0) {
                $transaction->status = 'DOWN PAYMENT';
              }else {
                $transaction->status = 'PAID';
              }
              $transaction->save();
              $payment->save();

              return response()->json([
                  'meta' => [
                      'code' => 200,
                      'message' => 'Midtrans Payment Success'
                  ]
              ]);
          } else if ($status == "pending") {
              $payment->status = 'PENDING';
              $payment->save();
              return response()->json([
                  'meta' => [
                      'code' => 200,
                      'message' => 'Midtrans Payment Pending'
                  ]
              ]);
          } else if ($status == 'deny') {
              $payment->status = 'CANCEL';
              $payment->save();
              return response()->json([
                  'meta' => [
                      'code' => 200,
                      'message' => 'Midtrans Payment Deny'
                  ]
              ]);
          } else if ($status == 'expired') {
              $payment->status = 'CANCEL';
              $payment->save();
              return response()->json([
                  'meta' => [
                      'code' => 200,
                      'message' => 'Midtrans Payment Expired'
                  ]
              ]);
          } else if ($status == 'cancel') {
              $payment->status = 'CANCEL';
              $payment->save();
              return response()->json([
                  'meta' => [
                      'code' => 200,
                      'message' => 'Midtrans Payment Cancel'
                  ]
              ]);
          } else {
              $payment->status = 'CANCEL';
              $payment->save();
              return response()->json([
                  'meta' => [
                      'code' => 500,
                      'message' => 'Midtrans Payment Gagal'
                  ]
              ]);
          }
    }
}
