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
                                <table id="tableDataSupplier" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Supplier</th>
                                            <th scope="col">No HP</th>
                                            <th scope="col">Alamat</th>
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

    <section class="section">
        <div class="modal fade" id="modal_add" tabindex="-1">
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
                            <input type="hidden" name="idSupplier" id="idSupplier">
                            <label style="font-size: 14px; font-weight: 600;">Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control nama_supplier" id="nama_supplier" placeholder="Input Nama Unit">
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-size: 14px; font-weight: 600;">No HP</label>
                            <input type="number" name="no_hp" class="form-control no_hp" id="no_hp">
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-size: 14px; font-weight: 600;">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control alamat" rows="4"></textarea>
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
    </section>
@endsection

@section('script-down')
  @include('admin.master-data.supplier.script')
@endsection