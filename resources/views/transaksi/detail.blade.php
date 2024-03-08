@extends('layouts.admin')

@section('title', 'Halaman Data Transaksi Detail')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Transaksi</h3>

                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Detail Data Transaksi
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">

                <div class="card-header">Detail Transaksi</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 400px">Tanggal Transaksi</th>
                                <td>{{ $transaction->created_at ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Tanggal Sewa</th>
                                <td>{{ $transaction->booking_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Tanggal Selesai</th>
                                <td>{{ $transaction->end_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Kode Transaksi</th>
                                <td>{{ $transaction->code ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Nama Penyewa</th>
                                <td>{{ $transaction->name_order ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Nomor Telepon</th>
                                <td>{{ $transaction->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Nama Sales</th>
                                <td>{{ $transaction->sales->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Jam Selesai</th>
                                <td>{{ $transaction->start_time ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Jam Mulai</th>
                                <td>{{ $transaction->end_time ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th style="width: 400px">Status Transaksi</th>
                                @if ($transaction->status == 'PAID')
                                    <td>
                                        <span class="badge bg-success">PAID</span>
                                    </td>
                                @elseif($transaction->status == 'PENDING')
                                    <td>
                                        <span class="badge bg-warning">PENDING</span>

                                    </td>
                                @elseif($transaction->status == 'DOWN PAYMENT')
                                    <td>
                                        <span class="badge bg-success">DOWN PAYMENT</span>

                                    </td>
                                @else
                                    <td>
                                        <span class="badge bg-danger">CANCEL</span>

                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th style="width: 400px">Total Harga</th>
                                <td>Rp.{{ number_format($transaction->total_price) ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Confirmation Rental</th>
                                <td>
                                    <a href="{{ route('confirmationOfRental', $transaction->id) }}" target="_blank" class="btn btn-success btn-sm">Unduh COR</a>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Invoice</th>
                                <td>
                                    <a href="{{ route('invoice', $transaction->id) }}" target="_blank" class="btn btn-success btn-sm">Unduh Invoice</a>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Surat Jalan</th>
                                <td>
                                    <a href="{{ route('surat-jalan', $transaction->id) }}" target="_blank" class="btn btn-success btn-sm">Unduh Surat Jalan</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">

                <div class="card-header">Informasi Pembayaran</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>Nama Pembayaran</th>
                            <th>Kode Transaksi</th>
                            <th>Status</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @foreach ($transaction->payment as $payment)
                                <tr>
                                    <td>Pemayaran Ke - {{ $loop->iteration }}</td>
                                    <td>{{ $payment->code }}</td>
                                    <td>
                                        @if ($payment->status == 'PAID')
                                            <span class="badge bg-success">
                                                PAID
                                            </span>
                                        @elseif($payment->status == 'PENDING')
                                            <span class="badge bg-warning">
                                                PENDING
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                FAILED
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($payment->total_price) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <a href="{{ route('transaction.index') }}" class="btn btn-primary">Kembali</a>

                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>


@endsection
