@extends('layout-admin/app')

@section('content')
    <style>
        .select2-container .select2-selection--single{
            height: 40px!important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder{
            position: absolute;
            top: 5px;
        }
        span.select2-container {
            z-index:10050;
        }
        *::placeholder {
            color: #999!important;
        }
    </style>
    <main id="main" class="main">
        <div class="row">
            <div class="col-md-6">
                <div class="pagetitle">
                    <h1>Data {{ $title }}</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dasbor</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6">
                <button style="float: right;" class="btn btn-primary text-button-admin" data-bs-toggle="modal" data-bs-target="#modal_add" onclick="addData()"><i class="bi bi-plus-circle"></i> Tambah {{ $title }}</button>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="table-responsive">
                                <table id="tableDataStokKeluar" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Produk</th>
                                            <th scope="col">QTY</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <div class="modal fade" aria-hidden="true" tabindex="-1" id="modal_add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="form-save">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="hidden" name="idStok" id="idStok">
                        <label style="font-size: 14px; font-weight: 600;">Tanggal</label>
                        <input type="date" name="date" class="form-control date" id="date">
                    </div>
                    <div>
                        <label for="barcode">Barcode</label>
                    </div>
                    <div class="input-group inpt-group mb-2">
                        <input type="hidden" name="idProduk" id="idProduk">
                        <input type="text" name="barcode" id="barcode" value="-" class="form-control" disabled>
                        <span class="input-group-text">
                            <button type="button" class="btn btn-primary" id="btnProduk" onclick="modalProduk()">
                                <i class="bi bi-search"></i>
                            </button>
                        </span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control nama_produk" value="-" id="nama_produk" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="unit">Unit Produk</label>
                                <input type="text" name="unit" class="form-control unit" value="-" id="unit" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="stok">Stok Awal</label>
                                <input type="text" name="stok" class="form-control stok" value="-" id="stok" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-size: 14px; font-weight: 600;">QTY</label>
                        <input type="number" name="qty" class="form-control qty" id="qty">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="close-button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" id="save" class="btn btn-primary save"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" aria-hidden="true" tabindex="-1" id="modal_produk" style="z-index:1061!important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Data Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table" id="tableProduk">
                        <thead>
                            <tr>
                                <th scope="col">Barcode</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataProduk as $val)
                                <tr>
                                    <td>{{ $val->barcode }}</td>
                                    <td>{{ $val->nama_produk }}</td>
                                    <td>{{ $val->getUnit->unit }}</td>
                                    <td>Rp {{ str_replace(',','.',number_format($val->harga_produk)) }}</td>
                                    <td>{{ $val->stock }}</td>
                                    <td>
                                        <button class="badge bg-primary border-0" id="select" data-id="{{ $val->idProduk }}" data-barcode="{{ $val->barcode }}" data-name="{{ $val->nama_produk }}" data-unit="{{ $val->getUnit->unit }}" data-stock="{{ $val->stock }}">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-down')
  @include('admin.master-data.stok-keluar.script')
@endsection