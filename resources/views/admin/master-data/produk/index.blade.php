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
                <button style="float: right;" class="btn btn-primary text-button-admin" onclick="addData()"><i class="bi bi-plus-circle"></i> Tambah {{ $title }}</button>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="table-responsive">
                                <table id="tableDataProduk" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Barcode</th>
                                            <th scope="col">Nama Produk</th>
                                            <th scope="col">Harga Produk</th>
                                            <th scope="col">Stock</th>
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

    <div class="modal fade" id="modal_add">
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
                    <input type="hidden" name="idProduk" id="idProduk">
                    <label>Kategori Produk</label>
                    <select name="kategori" id="kategori" class="form-control kategori" style="width:100%;">
                    
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Unit Produk</label>
                    <select name="unit" id="unit" class="form-control unit" style="width:100%;">
                    
                    </select> 
                </div>
                <div class="form-group mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control nama_produk" id="nama_produk" placeholder="Input Nama Produk">
                </div>
                <div class="form-group mb-3">
                    <label>Harga Produk</label>
                    <input type="number" name="harga_produk" class="form-control harga_produk" id="harga_produk">
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control stock" id="stock">
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
@endsection

@section('script-down')
  @include('admin.master-data.produk.script')
@endsection