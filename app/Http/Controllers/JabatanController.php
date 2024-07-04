<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\surat;
use App\Models\jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    public function index( Request $request )
    {
        if ( auth()->user()->hak_akses->name !== 'Admin' ) {
            abort(403);
        }
        if ($request->ajax()) {
            $data = jabatan::orderBy('id', 'desc')->get();

            return Datatables::of($data)
            ->addIndexColumn() 
            ->addColumn('action', function($data){
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-original-title="Edit" class="edit btn btn-warning btn-sm btnEdit"><i class="bi bi-pencil-square"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-name="'.$data->name.'" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete"><i class="bi bi-trash-fill"></i></a>';
                return $btn;
            })   
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('master data.jabatan');
    }

    protected function validator($data)
    {
        $rules = [
            'id' => ($data['action'] == 'tambah') ? '' : 'required',
            'name' => 'required',
        ];

        $message = [
            'id.required' => 'ID tidak boleh kosong',
            'name.required' => 'Nama Jabatan tidak boleh kosong',
        ];

        return Validator::make($data, $rules, $message);
    }

    public function storeOrUpdate( Request $request)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
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
    
                    $storeOrUpdate = jabatan::findOrNew($id);
    
                    if($request->action == 'tambah') {
                        if(jabatan::where('name', $request->name)->exists()) {
                            return ['error' => 'Nama Jabatan sudah ada'];
                        }
                    }
    
                    $storeOrUpdate->name = $request->name;
                    $storeOrUpdate->save();
    
                    $message = ($id == null) ? 'menambahkan' : 'mengubah';
                    return ['status' => true, 'pesan' => 'Anda berhasil '.$message.' data Jabatan'];
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
    
            $jabatan = jabatan::find($id);
            $encryptedID = Crypt::encryptString($jabatan->id);
    
            $jabatan->makeHidden(['id', 'created_at', 'updated_at']);
            return ['data' => $jabatan, 'encryptedID' => $encryptedID];
        }
    }

    public function destroy($id)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);
                $jabatan = Jabatan::find($id);

                if ($jabatan) {
                    $users = User::where('jabatan_id', $jabatan->id)->get();

                    if ($users->isNotEmpty()) {
                        foreach ($users as $user) {
                            // Delete related records in the 'surat' table
                            Surat::where('pembuat_surat', $user->id)->delete();
                            Surat::where('nama_pejabat', $user->id)->delete();

                            // Delete the user
                            $user->delete();
                        }
                    }

                    // Delete the jabatan
                    $jabatan->delete();

                    return ['status' => true, 'pesan' => 'Anda berhasil menghapus data Jabatan'];
                }
            }
        } catch(\Exception $e) {
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }
}
