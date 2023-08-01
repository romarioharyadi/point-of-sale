<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MSupplier;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.master-data.supplier.index', [
            'title' => 'Supplier',
            'dropdown' => '1',
        ]);
    }

    public function apiData(){
        $data = MSupplier::select('*')
            ->orderBy('idSupplier', 'DESC')
            ->get();

        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            $buttonEdit = "<button style='margin-right:10px;' onclick='editData(".$data->idSupplier.")' class='btn btn-sm btn-warning' data-toggle='tooltip' data-placement='top' title='Edit Data ".$data->idSupplier."'><i class='bi bi-pencil-square'></i></button>";
            $buttonDelete = "<button onclick='deleteData(".$data->idSupplier.")' class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='top' title='Delete Data ".$data->idSupplier."'><i class='bi bi-trash'></i></button>";
            return $buttonEdit.$buttonDelete;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function saveOrUpdate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama_supplier'       => ['required'],
        ],[
            'nama_supplier.required'    => 'Inputan Nama Supplier Wajib Diisi!',
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
                $data['message'] = 'Tambah Data Supplier Gagal!, karna : <br>'.$solusi.'';

                return response()->json($data);exit;
            }

            if (empty($req->idSupplier)) {
                $dataSupplier                       = new MSupplier;
                $dataSupplier->nama_supplier        = $req->nama_supplier;
                $dataSupplier->no_hp                = $req->no_hp;
                $dataSupplier->alamat               = $req->alamat;
                $dataSupplier->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Tambah Data Supplier Berhasil!";
            } else {
                $dataSupplier                       = MSupplier::find($req->idSupplier);
                $dataSupplier->nama_supplier        = $req->nama_supplier;
                $dataSupplier->no_hp                = $req->no_hp;
                $dataSupplier->alamat               = $req->alamat;
                $dataSupplier->save();

                $data['title']      = "Berhasil";
                $data['status']     = "success";
                $data['timer']      = 2500;
                $data['message']    = "Ubah Data Supplier Berhasil!";
            }

        } catch (\Throwable $th) {
            $data['title']      = "Error";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Tambah Data Supplier Gagal!, karna'. $th;
        }

        return response()->json($data);
    }

    public function edit(Request $req)
    {
        return response()->json(MSupplier::find($req->id));
    }

    public function delete(Request $req)
    {
        try {
            $supplier = MSupplier::find($req->id);
            $supplier->delete();

            $data['title']      = "Berhasil";
            $data['status']     = "success";
            $data['timer']      = 2500;
            $data['message']    = 'Hapus Data Supplier Berhasil!';
        } catch (\Throwable $th) {
            $data['title']      = "Gagal";
            $data['status']     = "error";
            $data['timer']      = 10000;
            $data['message']    = 'Hapus Data Supplier Gagal! karna '. $th;
        }

        return response()->json($data);
    }
}
