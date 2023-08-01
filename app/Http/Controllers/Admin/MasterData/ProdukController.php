<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MProduk;
use App\Models\MasterData\MKategori;
use App\Models\MasterData\MUnit;
use Yajra\DataTables\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        return view('admin.master-data.produk.index', [
            'title' => 'Produk',
            'dropdown' => '1',
        ]);
    }

    public function apiData(){
        $data = MProduk::with('getKategori','getUnit')
            ->orderBy('tb_produk.idProduk', 'DESC')
            ->get();

        return DataTables::of($data)
        ->addColumn('getKategori', function ($data){
            return $data->getKategori->nama_kategori;
        })
        ->addColumn('getUnit', function ($data){
            return $data->getUnit->unit;
        })
        ->addColumn('getBarcode', function ($data){
            return $barcode = QrCode::size(50)->generate($data->barcode);
        })
        ->addColumn('action', function ($data) {
            $buttonEdit = "<button style='margin-right:10px;' onclick='editData(".$data->idProduk.")' class='btn btn-sm btn-warning' data-toggle='tooltip' data-placement='top' title='Edit Data ".$data->idProduk."'><i class='bi bi-pencil-square'></i></button>";
            $buttonDelete = "<button onclick='deleteData(".$data->idProduk.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idProduk."'><i class='bi bi-trash'></i></button>";
            return $buttonEdit.$buttonDelete;
        })
        ->rawColumns(['getKategori','getUnit','getBarcode','action'])
        ->make(true);
    }

    public function getKategoriProduk(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $data = MKategori::all();
        }else{
            $data = MKategori::where('nama_kategori', 'like', '%'.$search.'%')->get();
        }

        $response = array();
        foreach($data as $namaKategoriProduk) {
            $response[] = array(
                "id" => $namaKategoriProduk->idKategori,
                "text" => $namaKategoriProduk->nama_kategori
            );
        }

        echo json_encode($response);
        exit;
    }

    public function getUnitProduk(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $data = MUnit::all();
        }else{
            $data = MUnit::where('unit', 'like', '%'.$search.'%')->get();
        }

        $response = array();
        foreach($data as $namaUnitProduk) {
            $response[] = array(
                "id" => $namaUnitProduk->idUnit,
                "text" => $namaUnitProduk->unit
            );
        }

        echo json_encode($response);
        exit;
    }

    public function saveOrUpdate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'kategori'       => ['required'],
            'unit'           => ['required'],
            'nama_produk'    => ['required'],
        ],[
            'kategori.required'     => 'Inputan Kategori Wajib Diisi!',
            'unit.required'         => 'Inputan Unit Wajib Diisi!',
            'nama_produk.required'  => 'Inputan Nama Produk Wajib Diisi!',
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
                $data['message'] = 'Tambah Data Produk Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            if (empty($req->idProduk)) {
                //Generate kode produk
                $totalBarangProduct = MProduk::get()->count();
                $kodeBarcode = '';
                
                $text = '1234567890';
                $panj = 2;
                $txtl = strlen($text)-1;
                $koderef = '';
                for($i=1; $i<=$panj; $i++){
                    $koderef .= $text[rand(0, $txtl)];
                }

                if ($totalBarangProduct === 0) {
                    $kodeBarcode = 'MTS-0001';
                } else if ($totalBarangProduct > 0) {
                    $kodeBarcode = 'MTS-000'.($totalBarangProduct + 1);
                } else if ($totalBarangProduct > 10) {
                    $kodeBarcode = 'MTS-00'.($totalBarangProduct + 1);
                } else if ($totalBarangProduct > 100) {
                    $kodeBarcode = 'MTS-0'.($totalBarangProduct + 1);
                } else if ($totalBarangProduct > 1000) {
                    $kodeBarcode = ($totalBarangProduct + 1);
                }

                $produk                  = new MProduk;
                $produk->idKategori      = $req->kategori;
                $produk->idUnit          = $req->unit;
                $produk->barcode         = $kodeBarcode;
                $produk->nama_produk     = $req->nama_produk;
                $produk->harga_produk    = $req->harga_produk;
                $produk->stock           = $req->stock;
                $produk->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Tambah Data Produk Berhasil!";
            } else {
                $produk                  = MProduk::find($req->idProduk);
                $produk->idKategori      = $req->kategori;
                $produk->idUnit          = $req->unit;
                $produk->nama_produk     = $req->nama_produk;
                $produk->harga_produk    = $req->harga_produk;
                $produk->stock           = $req->stock;
                $produk->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Ubah Data Produk Berhasil!";
            }

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Produk Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function edit(Request $req)
    {
        $dataProduk             = MProduk::find($req->id);
        $dataKategoriProduk     = MKategori::find($dataProduk->idKategori);
        $dataUnit               = MUnit::find($dataProduk->idUnit);
        $data = [
            'dataProduk'        => $dataProduk,
            'dataKategori'      => $dataKategoriProduk,
            'dataUnit'          => $dataUnit,
        ];

        return response()->json($data);
    }

    public function delete(Request $req)
    {
        try {
            $produk = MProduk::find($req->id);
            $produk->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Produk Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Produk Gagal! karna '. $th;
        }

        return response()->json($data);
    }
}
