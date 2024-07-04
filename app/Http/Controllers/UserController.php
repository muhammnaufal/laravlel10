<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\surat;
use App\Models\bidang;
use App\Models\jabatan;
use App\Models\hakakses;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index( Request $request )
    {
        if ( auth()->user()->hak_akses->name !== 'Admin' ) {
            abort(403);
        }
        if ($request->ajax()) {
            $data = User::orderBy('id', 'desc')->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-original-title="Edit" class="edit btn btn-warning btn-sm btnEdit"><i class="bi bi-pencil-square"></i></a>';

                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-name="'.$data->name.'" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete"><i class="bi bi-trash-fill"></i></a>';

                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-name="'.$data->name.'" data-original-title="Reset" class="reset btn btn-success btn-sm mx-1 btnReset"><i class="bi bi-arrow-repeat"></i></a>';
                return $btn;
            })
            ->addColumn('bidang', function ($data) {
                $bidang =  $data->bidang->name;
                return $bidang;
            })
            ->addColumn('jabatan', function ($data) {
                $jabatan =  $data->jabatan->name;
                return $jabatan;
            })
            ->addColumn('hak_akses', function ($data) {
                $hak_akses =  $data->hak_akses->name;
                return $hak_akses;
            })
            ->addColumn('NIP', function ($data) {
                $nip = $data->NIP;
                $formattedNIP = substr($nip, 0, 8) . ' ' . substr($nip, 8, 6) . ' ' . substr($nip, 14, 1) . ' ' . substr($nip, 15, 3);
                return $formattedNIP;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('user',[
            'bidangs' => bidang::all(),
            'jabatans' => jabatan::all(),
            'hakakseses' => hakakses::all()
        ]);
    }

    protected function validator($data)
    {
        $rules = [
            'id' => ($data['action'] == 'tambah') ? '' : 'required',
            'nip' => 'required|size:21',
            'name' => 'required',
            'bidang_id' => 'required',
            'jabatan_id' => 'required',
            'hak_akses_id' => 'required',
            'password' => ($data['action'] == 'tambah') ? 'required' : '',
        ];

        $message = [
            'id.required' => 'ID tidak boleh kosong',
            'name.required' => 'Nama User tidak boleh kosong',
            'nip.required' => 'NIP tidak boleh kosong',
            'nip.size' => 'NIP minimum harus memiliki 18 digit',
            'bidang_id.required' => 'Bidang tidak boleh kosong',
            'jabatan_id.required' => 'Jabatan tidak boleh kosong',
            'hak_akses_id.required' => 'Hak Akses tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ];

        if ($data['hak_akses_id'] == 3) {
            $rules['tingkatan_eselon'] = 'required';
            $message['tingkatan_eselon.required'] = 'Tingkat Eselon tidak boleh kosong';
        }

        return Validator::make($data, $rules, $message);
    }

    public function storeOrUpdate( Request $request)
    {
        try {
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

                $storeOrUpdate = User::findOrNew($id);

                $isAddingNew = $id == '';

                if ($isAddingNew && User::where('NIP', $request->nip)->exists()) {
                    return ['error' => 'Nomor NIP sudah ada'];
                }

                if (!$isAddingNew) {
                    $userWithSameNIP = User::where('NIP', $request->nip)->where('id', '<>', $id)->exists();
                    if ($userWithSameNIP) {
                        return ['error' => 'Nomor NIP sudah digunakan oleh pengguna lain'];
                    }
                }

                $nip = str_replace(' ', '', $request->nip);
                $storeOrUpdate->NIP = $nip;
                $storeOrUpdate->name = $request->name;
                $storeOrUpdate->bidang_id = $request->bidang_id;
                $storeOrUpdate->jabatan_id = $request->jabatan_id;
                $storeOrUpdate->hak_akses_id = $request->hak_akses_id;
                if ($request->hak_akses_id == 3 ) {
                    $storeOrUpdate->tingkatan_eselon = $request->tingkatan_eselon;
                }

                // $symbols = ['!', '@', '#', '$', '%', '^', '&', '*'];
                // $randomPassword = Str::random(8) . $symbols[array_rand($symbols, 1)];
                $encryptedPassword = bcrypt($request->password);

                if ($request->action == 'tambah') {
                    $storeOrUpdate->password = $encryptedPassword;
                    $storeOrUpdate->default_password = $request->password;
                }

                $storeOrUpdate->save();

                $message = ($id == null) ? 'menambahkan' : 'mengubah';
                return ['status' => true, 'pesan' => 'Anda berhasil '.$message.' data User'];
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

            $user = User::find($id);
            $encryptedID = Crypt::encryptString($user->id);

            $user->makeHidden(['id', 'created_at', 'updated_at']);
            return ['data' => $user, 'encryptedID' => $encryptedID];
        }
    }

    public function destroy($id)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);

                $userToDelete = User::find($id);

                if ($userToDelete->hak_akses_id == 1) {

                    $superAdminCount = User::where('hak_akses_id', 1)->count();

                    if ($superAdminCount == 1) {
                        return ['status' => false, 'pesan' => 'Tidak dapat menghapus User Admin terakhir'];
                    }else {
                        $userToDelete->delete();
                        return ['status' => true, 'pesan' => 'Anda berhasil menghapus data User'];
                    }
                }else {

                    $pembuat_surat = surat::where('pembuat_surat', $userToDelete->id)->get();
                    if ($pembuat_surat) {
                        $pembuat_surat->each(function ($pivot) {
                            $pivot->delete();
                        });
                    }

                    $pejabat_surat = surat::where('nama_pejabat', $userToDelete->id)->get();
                    if ($pejabat_surat) {
                        $pejabat_surat->each(function ($pivot) {
                            $pivot->delete();
                        });
                    }

                    $userToDelete->delete();
                    return ['status' => true, 'pesan' => 'Anda berhasil menghapus akun'];
                }
            }

        } catch(\Exception $e) {
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }

    public function reset(Request $req ,$id)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);

                $userToReset = User::find($id);

                $encryptedKataSandi= bcrypt($req->password);

                $userToReset->default_password = $req->password;
                $userToReset->password = $encryptedKataSandi;
                $userToReset->save();

                return ['status' => true, 'pesan' => 'Anda berhasil mengatur ulang kata sandi'];
            }
        } catch(\Exception $e) {
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }
}
