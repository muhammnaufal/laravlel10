<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BebanAnggaran;
use App\Models\surat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class BebanAnggaranController extends Controller
{

    public function index( Request $request )
    {
        if ( auth()->user()->hak_akses->name !== 'Admin' ) {
            abort(403);
        }
        if ($request->ajax()) {
            $data = BebanAnggaran::orderBy('id', 'desc')->get();

            return Datatables::of($data)
            ->addIndexColumn() 
            ->addColumn('action', function($data){
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-original-title="Edit" class="edit btn btn-warning btn-sm btnEdit"><i class="bi bi-pencil-square"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-name="'.$data->nama_lembaga.'" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete"><i class="bi bi-trash-fill"></i></a>';
                return $btn;
            })   
            ->addColumn('jenis_lembaga', function($data){
                $lembaga = $data->jenis_lembaga == 1 ? "Dipa" : "Mitra";
                return $lembaga;
            })   
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('master data.bebanAnggaran');
    }

    protected function validator($data)
    {
        $rules = [
            'id' => ($data['action'] == 'tambah') ? '' : 'required',
            'jenisLembagaNegara' => 'required',
            'namaLembagaNegara' => 'required'

        ];

        $message = [
            'id.required' => 'ID tidak boleh kosong',
            'jenisLembagaNegara.required' => 'Jenis Lembaga Negara tidak boleh kosong',
            'namaLembagaNegara.required' => 'Nama Lembaga Negara tidak boleh kosong'
        ];

        return Validator::make($data, $rules, $message);
    }
    
    public function storeOrUpdate(  Request $request )
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $jenisLembaga = $request->jenisLembagaNegara;

                if ($jenisLembaga != 1 && $jenisLembaga != 2) {
                    return ['error' => 'Jenis Lembaga Negara tidak valid'];
                }
    
                $validator = $this->validator($request->all());
    
                if ($validator->fails()) {
                    $errors = null;
                    $j = 0;
                    foreach ($validator->getMessageBag()->toArray() as $key => $error) {
                        foreach ($error as $key => $pesan_error) {
                            $errors .=  ($j + 1) . '. ' . $pesan_error . '</br>';
                        }
                        $j++;
                    }
                    return ['error' => $errors];
                } else {
                    $id = ($request->id == null) ? '' : Crypt::decryptString($request->id);
    
                    $storeOrUpdate = BebanAnggaran::findOrNew($id);
    
                    if($request->action == 'tambah') {
                        if(BebanAnggaran::where('nama_lembaga', $request->namaLembagaNegara)->exists()) {
                            return ['error' => 'Nama Lembaga sudah ada'];
                        }
                    }
                    
                    $storeOrUpdate->jenis_lembaga = $request->jenisLembagaNegara;
                    $storeOrUpdate->nama_lembaga = $request->namaLembagaNegara;
                    $storeOrUpdate->save();
    
                    $message = ($id == null) ? 'menambahkan' : 'mengubah';
                    return ['status' => true, 'pesan' => 'Anda berhasil '.$message.' data Lembaga Negara'];
                }
            }
        } catch(\Exception $e) {
            return ['status' => false, 'error' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
        }
    }

    public function edit($id)
    {
        if ( auth()->user()->hak_akses->name !== 'Admin' ) {
            abort(403);
        }else {
            $id = Crypt::decryptString($id);
    
            $bebanAnggaran = BebanAnggaran::find($id);
            $encryptedID = Crypt::encryptString($bebanAnggaran->id);
    
            $bebanAnggaran->makeHidden(['id', 'created_at', 'updated_at']);
            return ['data' => $bebanAnggaran, 'encryptedID' => $encryptedID];
        }
    }

    public function destroy($id)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);
                $bebanAnggaran = BebanAnggaran::find($id);

                if ($bebanAnggaran) {
                    $surats = surat::where('beban_anggaran_id', $bebanAnggaran->id)->get();
                    if ($surats) {
                        foreach ($surats as $surat) {
                            $surat->delete();
                        }
                    }
                    $bebanAnggaran->delete();
                    return ['status' => true, 'pesan' => 'Anda berhasil menghapus data Lembaga Negara'];
                }
            }
        } catch(\Exception $e) {
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }
}
