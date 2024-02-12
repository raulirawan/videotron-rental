@extends('layouts.admin')

@section('title', 'Halaman Data Transaksi')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Transaksi</h3>

                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Data Transaksi
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">

                <div class="card-header">Tabel Transaksi</div>
                <div class="card-body">
                    <!--Basic Modal -->
                    <form>
                        <div class="row input-daterange ml-2 my-2">
                            <div class="col-md-3">
                                <input type="date" name="from_date" id="from_date"
                                    value="{{ date('Y-m-d', strtotime('-7 days')) }}" class="form-control datatable-input"
                                    placeholder="From Date" data-col-index="0"  />
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" id="to_date" value="{{ date('Y-m-d') }}"
                                    class="form-control datatable-input" placeholder="To Date" data-col-index="0"  />
                            </div>
                            <div class="col-md-3">
                                <select name="status" id="status" class="form-control datatable-input" data-col-index="6" >
                                    <option value="">Pilih Status</option>
                                    <option value="PAID">PAID</option>
                                    <option value="PENDING">PENDING</option>
                                    <option value="DOWN PAYMENT">DOWN PAYMENT</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="refresh" id="reset" class="btn btn-default">Refresh</button>
                            </div>

                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table" id="table-data">
                            <thead>
                                <tr>
                                    <th style="width: 25%">Tanggal Transaksi</th>
                                    <th class="none">Tanggal Sewa</th>
                                    <th>Kode</th>
                                    <th>Nama Penyewa</th>
                                    <th>Nama Sales</th>
                                    <th class="none">Ukuran</th>
                                    <th>Status</th>
                                    <th>Total Harga</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>

    @push('down-style')
        <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/fontawesome.css" />
        <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
    @endpush
    @push('down-script')
        <script src="{{ asset('assets/custom/datatables.bundle.js') }}"></script>
        <script>
            $(document).ready(function() {
                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                var datatable = $('#table-data').DataTable({
                    responsive: true,
                    paging: true,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: '{!! url()->current() !!}',
                        type: 'GET',
                    },
                    columns: [

                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'booking_date',
                            name: 'booking_date'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },

                        {
                            data: 'sales.name',
                            name: 'sales.name'
                        },

                        {
                            data: 'width',
                            name: 'width'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'total_price',
                            name: 'total_price',
                            render: $.fn.dataTable.render.number(',', '.', 0, 'Rp ')
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searcable: false,
                            width: '10%',
                        }
                    ],

                    columnDefs: [{
                        defaultContent: "-",
                        targets: "_all",
                    }, ],

                    "footerCallback": function(row, data) {
                        var api = this.api(),
                            data;

                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        total = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        price = api
                            .column(7, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(7).footer()).html(
                            'Rp' + price
                        );

                        var numFormat = $.fn.dataTable.render.number('\,', 'Rp').display;
                        $(api.column(7).footer()).html(
                            'Rp ' + numFormat(price)
                        );
                    }

                });


            });
        </script>
    @endpush

@endsection
