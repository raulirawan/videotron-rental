<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Videotron;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                if ($request->from_date === $request->to_date) {
                    $query  = Transaction::query();
                    if ($request->status != 'SEMUA') {
                        $query->with(['user', 'sales'])
                            ->whereDate('created_at', $request->from_date)
                            ->where('status', $request->status);
                    } else {
                        $query->with(['user', 'sales'])
                            ->whereDate('created_at', $request->from_date);
                    }
                } else {
                    $query  = Transaction::query();
                    if ($request->status != 'SEMUA') {
                        $query->with(['user', 'sales'])
                            ->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59'])
                            ->where('status', $request->status);
                    } else {
                        $query->with(['user', 'sales'])
                            ->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                    }
                }
            } else {
                $today = date('Y-m-d');
                $query  = Transaction::query();
                if ($request->status != 'SEMUA') {
                    $query->with(['user', 'sales'])
                        ->whereDate('created_at', $today)
                        ->where('status', $request->status);
                } else {
                    $query->with(['user', 'sales'])
                        ->whereDate('created_at', $today);
                }
            }

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '<a href="' . route('transaksi.detail', $item->id) . '" class="btn btn-sm btn-primary"
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
                    return Carbon::parse($item->created_at)->format('d F Y');
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

    public function store(Request $request)
    {
        
    }
}
