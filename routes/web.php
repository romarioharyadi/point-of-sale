<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

use App\Http\Controllers\Admin\MasterData\SupplierController;
use App\Http\Controllers\Admin\MasterData\KategoriController;
use App\Http\Controllers\Admin\MasterData\UnitController;
use App\Http\Controllers\Admin\MasterData\ProdukController;
use App\Http\Controllers\Admin\MasterData\StokController;


Route::get('/', function () {
    return view('layout-admin/app');
});

Route::get('/login-admin',[AuthController::class,'login'])->name('login');

Route::prefix('masterdata')->name('masterdata.')->group(function() {

    // Supplier
    Route::prefix('supplier')->name('supplier.')->group(function() {
        Route::get('/', [SupplierController::class,'index'])->name('index');
        Route::get('/apiData', [SupplierController::class, 'apiData'])->name('apiData');
        Route::get('/getData', [SupplierController::class, 'edit'])->name('edit');
        Route::post('/save', [SupplierController::class, 'saveOrUpdate'])->name('save');
        Route::post('/delete', [SupplierController::class, 'delete'])->name('delete');
    });
    
    // Kategori
    Route::prefix('kategoriProduk')->name('kategoriProduk.')->group(function() {
        Route::get('/', [KategoriController::class,'index'])->name('index');
        Route::get('/apiData', [KategoriController::class, 'apiData'])->name('apiData');
        Route::get('/getData', [KategoriController::class, 'edit'])->name('edit');
        Route::post('/save', [KategoriController::class, 'saveOrUpdate'])->name('save');
        Route::post('/delete', [KategoriController::class, 'delete'])->name('delete');
    });

    // Unit
    Route::prefix('unitProduk')->name('unitProduk.')->group(function() {
        Route::get('/', [UnitController::class,'index'])->name('index');
        Route::get('/apiData', [UnitController::class, 'apiData'])->name('apiData');
        Route::get('/getData', [UnitController::class, 'edit'])->name('edit');
        Route::post('/save', [UnitController::class, 'saveOrUpdate'])->name('save');
        Route::post('/delete', [UnitController::class, 'delete'])->name('delete');
    });

    // Unit
    Route::prefix('itemProduk')->name('itemProduk.')->group(function() {
        Route::get('/', [ProdukController::class,'index'])->name('index');
        Route::get('/apiData', [ProdukController::class, 'apiData'])->name('apiData');
        Route::get('/getData', [ProdukController::class, 'edit'])->name('edit');
        Route::post('/save', [ProdukController::class, 'saveOrUpdate'])->name('save');
        Route::post('/delete', [ProdukController::class, 'delete'])->name('delete');

        Route::get('/getKategoriProduk', [ProdukController::class, 'getKategoriProduk'])->name('apiKategoriProduk');
        Route::get('/getUnitProduk', [ProdukController::class, 'getUnitProduk'])->name('apiUnitProduk');
    });

    // Stok Masuk
    Route::prefix('stokMasuk')->name('stokMasuk.')->group(function() {
        Route::get('/', [StokController::class,'index'])->name('index');
        Route::get('/apiData', [StokController::class, 'apiData'])->name('apiData');
        Route::get('/getData', [StokController::class, 'detail'])->name('detail');
        Route::post('/save', [StokController::class, 'saveOrUpdate'])->name('save');
        Route::post('/delete', [StokController::class, 'delete'])->name('delete');

        Route::get('/getSupplier', [StokController::class, 'getSupplier'])->name('apiSupplier');

    });

    // Stok Keluar
    Route::prefix('stokKeluar')->name('stokKeluar.')->group(function() {
        Route::get('/', [StokController::class,'indexStokKeluar'])->name('indexStokKeluar');
        Route::get('/apiDataStokKeluar', [StokController::class, 'apiDataStokKeluar'])->name('apiDataStokKeluar');
        Route::post('/saveStokKeluar', [StokController::class, 'saveStokKeluar'])->name('saveStokKeluar');
        Route::post('/deleteStokKeluar', [StokController::class, 'deleteStokKeluar'])->name('deleteStokKeluar');

    });
});
