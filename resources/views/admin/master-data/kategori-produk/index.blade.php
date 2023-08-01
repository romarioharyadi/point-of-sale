@extends('layout-admin/app')

@section('content')
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
                                <table id="tableDataKategori" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Kategori</th>
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


    <div class="modal fade" tabindex="-1" id="modal_add">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="form-save">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="idKategori" id="idKategori">
                    <label style="font-size: 14px; font-weight: 600;">Kategori Produk</label>
                    <input type="text" name="nama_kategori" class="form-control nama_kategori" id="nama_kategori" placeholder="Input Nama Kategori">
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
  @include('admin.master-data.kategori-produk.script')
@endsection