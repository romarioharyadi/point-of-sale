<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MasterData\MStok;
use App\Models\MasterData\MProduk;
use App\Models\MasterData\MSupplier;
use App\Models\MasterData\MUnit;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $dataProduk = MProduk::with('getUnit')->get();
        return view('admin.master-data.stok-masuk.index', [
            'title'         => 'Stok Masuk',
            'dataProduk'    => $dataProduk,
            'dropdown'      => '1',
        ]);
    }

    public function getSupplier(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $data = MSupplier::all();
        }else{
            $data = MSupplier::where('nama_supplier', 'like', '%'.$search.'%')->get();
        }

        $response = array();
        foreach($data as $namaSupplier) {
            $response[] = array(
                "id" => $namaSupplier->idSupplier,
                "text" => $namaSupplier->nama_supplier
            );
        }

        echo json_encode($response);
        exit;
    }

    public function apiData(){
        $data = MStok::with('getProduk', 'getSupplier')
            ->orderBy('idStok', 'DESC')
            ->where('tipe', 'masuk')
            ->get();

        return DataTables::of($data)
        ->addColumn('getProduk', function ($data){
            return $data->getProduk->nama_produk;
        })
        ->addColumn('changeDate', function ($data){
            return Carbon::parse($data->date)->format('d/m/Y');
        })
        ->addColumn('action', function ($data) {
            $buttonEdit = "<button style='margin-right:10px;' onclick='detailData(".$data->idStok.")' class='btn btn-sm btn-secondary' data-toggle='tooltip' data-placement='top' title='Detail Data ".$data->idStok."'><i class='bi bi-eye'></i></button>";
            $buttonDelete = "<button onclick='deleteData(".$data->idStok.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idStok."'><i class='bi bi-trash'></i></button>";
            return $buttonEdit.$buttonDelete;
        })
        ->rawColumns(['getProduk','changeDate','action'])
        ->make(true);
    }

    public function saveOrUpdate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'date'       => ['required'],
        ],[
            'date.required'    => 'Inputan Tanggal Wajib Diisi!',
        ]);

        try {

            if ($validator->fails()) {
                $pesan = '';
                foreach ($validator->messages()->get('*') as $error) {
                    foreach ($error as $q => $a) {
                        $pesan .= '<b>- '.$a. '</b><br>';
                    }
                }
                $solusi = substr($pesan, 0, -1);

                $data['title'] = "Gagal";
                $data['status'] = "error";
                $data['timer'] = 5000;
                $data['message'] = 'Tambah Data Stok Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            $dataStokMasuk                       = new MStok;
            $dataStokMasuk->idProduk             = $req->idProduk;
            $dataStokMasuk->idSupplier           = $req->supplier;
            $dataStokMasuk->idUser               = 1;
            $dataStokMasuk->tipe                 = 'masuk';
            $dataStokMasuk->qty                  = $req->qty;
            $dataStokMasuk->date                 = $req->date;
            $dataStokMasuk->save();

            $updateStokProduk                    = MProduk::find($req->idProduk);
            $updateStokProduk->stock             = $updateStokProduk->stock + $req->qty;
            $updateStokProduk->save();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = "Tambah Data Stok Berhasil!";

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Stok Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function detail(Request $req)
    {
        $dataStok             = MStok::find($req->id);
        $dataSupplier         = MSupplier::find($dataStok->idSupplier);
        $dataProduk           = MProduk::find($dataStok->idProduk);
        $dataUnit             = MUnit::find($dataProduk->idUnit);
        $data = [
            'dataStok'        => $dataStok,
            'dataSupplier'    => $dataSupplier,
            'dataProduk'      => $dataProduk,
            'dataUnit'        => $dataUnit,
        ];

        return response()->json($data);
    }

    public function delete(Request $req)
    {
        try {
            $dataStok   = MStok::find($req->id);

            $dataProduk         = MProduk::find($dataStok->idProduk);
            $dataProduk->stock  = $dataProduk->stock - $dataStok->qty;
            
            $dataProduk->update();
            $dataStok->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Stok Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Stok Gagal! karna '. $th;
        }

        return response()->json($data);
    }


    // --------------------------- Bagian Stok Keluar-----------------------
    public function indexStokKeluar()
    {
        $dataProduk = MProduk::with('getUnit')->get();
        return view('admin.master-data.stok-keluar.index', [
            'title'         => 'Stok Keluar',
            'dataProduk'    => $dataProduk,
            'dropdown'      => '1',
        ]);
    }

    public function apiDataStokKeluar(){
        $data = MStok::with('getProduk', 'getSupplier')
            ->orderBy('idStok', 'DESC')
            ->where('tipe', 'keluar')
            ->get();

        return DataTables::of($data)
        ->addColumn('getProduk', function ($data){
            return $data->getProduk->nama_produk;
        })
        ->addColumn('changeDate', function ($data){
            return Carbon::parse($data->date)->format('d/m/Y');
        })
        ->addColumn('action', function ($data) {
            $buttonDelete = "<button onclick='deleteData(".$data->idStok.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idStok."'><i class='bi bi-trash'></i></button>";
            return $buttonDelete;
        })
        ->rawColumns(['getProduk','changeDate','action'])
        ->make(true);
    }
    

    public function saveStokKeluar(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'date'       => ['required'],
        ],[
            'date.required'    => 'Inputan Tanggal Wajib Diisi!',
        ]);

        try {

            if ($validator->fails()) {
                $pesan = '';
                foreach ($validator->messages()->get('*') as $error) {
                    foreach ($error as $q => $a) {
                        $pesan .= '<b>- '.$a. '</b><br>';
                    }
                }
                $solusi = substr($pesan, 0, -1);

                $data['title'] = "Gagal";
                $data['status'] = "error";
                $data['timer'] = 5000;
                $data['message'] = 'Tambah Data Stok Keluar Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            $dataStokMasuk                       = new MStok;
            $dataStokMasuk->idProduk             = $req->idProduk;
            $dataStokMasuk->idSupplier           = null;
            $dataStokMasuk->idUser               = 1;
            $dataStokMasuk->tipe                 = 'keluar';
            $dataStokMasuk->qty                  = $req->qty;
            $dataStokMasuk->date                 = $req->date;

            $updateStokProduk                    = MProduk::find($req->idProduk);
            if($req->qty > $updateStokProduk->stock){
                $data['title']      = "Gagal";
                $data['status']     = "error";
                $data['timer']      = 5000;
                $data['message']    = 'QTY melebihi stok barang!';
                return response()->json($data);
            }else{
                $updateStokProduk->stock             = $updateStokProduk->stock - $req->qty;

                $dataStokMasuk->save();
                $updateStokProduk->save();
    
                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Tambah Data Stok Keluar Berhasil!";
                return response()->json($data);
            }

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Stok Keluar Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function deleteStokKeluar(Request $req)
    {
        try {
            $dataStok   = MStok::find($req->id);

            $dataProduk         = MProduk::find($dataStok->idProduk);
            $dataProduk->stock  = $dataProduk->stock + $dataStok->qty;
            
            $dataProduk->update();
            $dataStok->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Stok Keluar Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Stok Keluar Gagal! karna '. $th;
        }

        return response()->json($data);
    }
}
