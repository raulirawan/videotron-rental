@extends('layouts.admin')

@section('title', 'Halaman Data Category')

@section('content')
<div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3>Data Category</h3>

        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav
            aria-label="breadcrumb"
            class="breadcrumb-header float-start float-lg-end"
          >
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Data Category
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <!-- Basic Tables start -->
    <section class="section">
      <div class="card">

        <div class="card-header">Tabel Category</div>
        <div class="card-body">
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
        data-bs-target="#modal-create">
        Tambah Category
    </button>

    <!--Basic Modal -->

         <div class="table-responsive">
            <table class="table" id="table1">
                <thead>
                  <tr>
                    <th style="width: 5%">No</th>
                    <th>Nama</th>
                    <th style="width: 15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                 @foreach ($category as $item)
                 <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                      <button href="" class="btn btn-info btn-sm" id="edit"
                      data-id="{{ $item->id }}"
                      data-name="{{ $item->name }}"
                      data-bs-toggle="modal"
                      data-bs-target="#modal-edit">Edit</button>
                      <a href="{{ route('category.delete', $item->id) }}"
                        onclick="return confirm('Yakin ?')"
                        class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                  </tr>
                 @endforeach

                </tbody>
              </table>
         </div>
        </div>
      </div>
    </section>
    <!-- Basic Tables end -->
  </div>

  <div class="modal fade text-left" id="modal-create" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Form Tambah Data Category</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="basicInput">Nama</label>
                              <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Masukan Nama" required>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </div>
        </form>
  </div>
</div>

<div class="modal fade text-left" id="modal-edit" tabindex="-1" role="dialog"
aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-dialog modal-dialog-scrollable" role="document">
      <form action="#" id="form-edit" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myModalLabel1">Form Edit Data Category</h5>
                  <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                      aria-label="Close">
                      <i data-feather="x"></i>
                  </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="basicInput">Nama</label>
                            <input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name" placeholder="Masukan Nama" required>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn" data-bs-dismiss="modal">
                      <i class="bx bx-x d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Close</span>
                  </button>
                  <button type="submit" class="btn btn-primary ml-1">
                      <i class="bx bx-check d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Simpan</span>
                  </button>
              </div>
          </div>
      </form>
</div>
</div>

  @push('down-style')
  <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/datatables.css" />
  @endpush
  @push('down-script')
    <script src="{{ asset('assets') }}/js/extensions/datatables.js"></script>
    @if (count($errors) > 0)
        <script type="text/javascript">
            $(document).ready(function () {
                $('#modal-create').modal('show');
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').val(name);
                $('#form-edit').attr('action','/category/update/' + id);
            });
        });
    </script>
  @endpush

@endsection
