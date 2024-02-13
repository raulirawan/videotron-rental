<?php

namespace App\Http\Controllers;

use Pdf;
use Exception;
use Carbon\Carbon;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Videotron;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\TransactionPayment;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = Transaction::query();
            $query->with(['user', 'sales']);
            return DataTables::of($query)
                ->filterColumn('created_at', function ($query, $keyword) {
                    $dates = explode('|', $keyword);
                    if (count($dates) > 0) {
                        $start = Carbon::parse($dates[0])->format('Y-m-d H:i:s');
                        $end = Carbon::parse($dates[1])->addDays(1)->format('Y-m-d H:i:s');
                        $query->whereRaw('created_at >=  ? and created_at <= ?', [$start, $end]);
                    } else {
                        $query->whereRaw('created_at like ?', ["%{$dates[0]}%"]);
                    }
                })
                ->addColumn('action', function ($item) {
                    return '<a href="' . route('transaction.detail', $item->id) . '" class="btn btn-sm btn-primary"
                            >Detail</a>';
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == 'PENDING') {
                        return '<span class="badge bg-warning">PENDING</span>';
                    } elseif ($item->status == 'DOWN PAYMENT') {
                        return '<span class="badge bg-success">DOWN PAYMENT</span>';
                    } elseif ($item->status == 'PAID') {
                        return '<span class="badge bg-success">PAID</span>';
                    } else {
                        return '<span class="badge bg-danger">CANCELLED</span>';
                    }
                })
                ->editColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->format('d F Y H:i:s');
                })
                ->editColumn('booking_date', function ($item) {
                    return Carbon::parse($item->booking_date)->format('d F Y');
                })
                ->editColumn('width', function ($item) {
                    return $item->width . ' X ' . $item->height . ' M';
                })
                ->rawColumns(['action', 'status'])
                ->make();
        }
        return view('transaksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function detail($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transaksi.detail', compact('transaction'));
    }

    public function confirmationOfRental(Transaction $transaction)
    {
        $pdf = SnappyPdf::loadView('pdf.cor', compact('transaction'))->setOption('page-width', '90')
            ->setOption('page-height', '130');
        $pdf->setOption('margin-top', 0);
        $pdf->setOption('margin-bottom', 0);
        $pdf->setOption('margin-left', 0);
        $pdf->setOption('margin-right', 0);
        return $pdf->download('confirmation-of-rental.pdf');
    }

    public function invoice(Transaction $transaction)
    {
        $pdf = SnappyPdf::loadView('pdf.invoice', compact('transaction'))->setOption('page-width', '90')
        ->setOption('page-height', '130');
        $pdf->setOption('margin-top', 0);
        $pdf->setOption('margin-bottom', 0);
        $pdf->setOption('margin-left', 0);
        $pdf->setOption('margin-right', 0);
        return $pdf->download('invoices.pdf');
    }
}
