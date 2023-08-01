<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MKategori;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.master-data.kategori-produk.index', [
            'title' => 'Kategori',
            'dropdown' => '1',
        ]);
    }

    public function apiData(){
        $data = MKategori::select('*')
            ->orderBy('idKategori', 'DESC')
            ->get();

        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            $buttonEdit = "<button style='margin-right:10px;' onclick='editData(".$data->idKategori.")' class='btn btn-sm btn-warning' data-toggle='tooltip' data-placement='top' title='Edit Data ".$data->idKategori."'><i class='bi bi-pencil-square'></i></button>";
            $buttonDelete = "<button onclick='deleteData(".$data->idKategori.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idKategori."'><i class='bi bi-trash'></i></button>";
            return $buttonEdit.$buttonDelete;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function saveOrUpdate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama_kategori'       => ['required'],
        ],[
            'nama_kategori.required'    => 'Inputan Nama Kategori Wajib Diisi!',
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
                $data['message'] = 'Tambah Data Kategori Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            if (empty($req->idKategori)) {
                $kategori                          = new MKategori;
                $kategori->nama_kategori           = $req->nama_kategori;
                $kategori->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Tambah Data Kategori Berhasil!";
            } else {
                $kategori                         = MKategori::find($req->idKategori);
                $kategori->nama_kategori          = $req->nama_kategori;
                $kategori->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Ubah Data Kategori Berhasil!";
            }

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Kategori Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function edit(Request $req)
    {
        return response()->json(MKategori::find($req->id));
    }

    public function delete(Request $req)
    {
        try {
            $kategori = MKategori::find($req->id);
            $kategori->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Kategori Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Kategori Gagal! karna '. $th;
        }

        return response()->json($data);
    }
}
