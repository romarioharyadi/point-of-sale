<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MUnit;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        return view('admin.master-data.unit.index', [
            'title' => 'Unit',
            'dropdown' => '1',
        ]);
    }

    public function apiData(){
        $data = MUnit::select('*')
            ->orderBy('idUnit', 'DESC')
            ->get();

        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            $buttonEdit = "<button style='margin-right:10px;' onclick='editData(".$data->idUnit.")' class='btn btn-sm btn-warning' data-toggle='tooltip' data-placement='top' title='Edit Data ".$data->idUnit."'><i class='bi bi-pencil-square'></i></button>";
            $buttonDelete = "<button onclick='deleteData(".$data->idUnit.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idUnit."'><i class='bi bi-trash'></i></button>";
            return $buttonEdit.$buttonDelete;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function saveOrUpdate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit'       => ['required'],
        ],[
            'unit.required'    => 'Inputan Nama Unit Wajib Diisi!',
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
                $data['message'] = 'Tambah Data Unit Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            if (empty($req->idUnit)) {
                $unitProduk                  = new MUnit;
                $unitProduk->unit            = $req->unit;
                $unitProduk->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Tambah Data Unit Berhasil!";
            } else {
                $unitProduk                  = MUnit::find($req->idUnit);
                $unitProduk->unit            = $req->unit;
                $unitProduk->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Ubah Data Unit Berhasil!";
            }

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Unit Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function edit(Request $req)
    {
        return response()->json(MUnit::find($req->id));
    }

    public function delete(Request $req)
    {
        try {
            $unitProduk = MUnit::find($req->id);
            $unitProduk->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Unit Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Unit Gagal! karna '. $th;
        }

        return response()->json($data);
    }
}
