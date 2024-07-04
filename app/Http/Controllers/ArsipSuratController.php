<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\surat;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;

class ArsipSuratController extends Controller
{
   public function index ( Request $request ) 
   {

    if ($request->ajax()) {
        if (auth()->user()->hak_akses->id == 1) {

            $data = surat::where('is_archive', 1)->orderBy('id', 'desc')->get();

        }else {

            $data = surat::join('users', 'surats.pembuat_surat', '=', 'users.id')
            ->where('users.bidang_id', auth()->user()->bidang_id)
            ->where('is_archive', 1)
            ->orderBy('surats.id', 'desc')
            ->get(['surats.*', 'users.id as user_id']);

        }

        return Datatables::of($data)
        ->addIndexColumn() 
        ->addColumn('action', function($data){
            $eselon = auth()->user()->tingkatan_eselon;

            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Info" class="btn btn-info btn-sm  mx-2 btnInfo"><i class="bi bi-info-square"></i></a>';

            if ((auth()->user()->hak_akses->id == 4 || auth()->user()->hak_akses->id == 1) && $data->nomor_surat != null) {
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Arsip" class="btn btn-secondary btn-sm btnArsip"><i class="bi bi-archive-fill"></i></a>';
            } else {
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Arsip" class="btn btn-secondary btn-sm btnArsip disabled"><i class="bi bi-archive-fill"></i></a>';
            }

            return $btn;
        })   
        ->addColumn('nomor_surat', function ($data) {
            $nomor_surat =  $data->nomor_surat ? $data->nomor_surat : "-";
            return $nomor_surat;
        })
        ->addColumn('perihal_surat', function ($data) {
            $perihal_surat =  $data->perihal_surat;
            $tanggal_dibuat = $data->tanggal_surat;
            $result = '<p>'.$perihal_surat . ' <br>  <b>Tanggal Dibuat: </b>' . $tanggal_dibuat . '</p>';
            return $result;
        })
        ->addColumn('pembuat_surat', function ($data) {
            $pembuat_surat =  $data->nama_pembuat->name;

            $bidang_pembuat = $data->nama_pembuat->bidang->name;

            $result = '<p>'.$pembuat_surat . ' <br> <small style="font-size:10px;"> (' . $bidang_pembuat . ') </small></p>';
            
            return $result;
        })
        ->addColumn('e4', function ($data) {
            $checked = $data->e4 == 1 ? 'checked' : '';
            $status = $data->e4 == 1 ? 1 : 0;
            $disabled = (auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 4) && ($data->e2 == 0 && $data->e3 == 0 && $data->is_archive == 1) ? '' : 'disabled';
            $e4 = '<input class="form-check-input e4" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';
            
            return $e4;
        })
        ->addColumn('e3', function ($data) {
            $checked = $data->e3 == 1 ? 'checked' : '';
            $status = $data->e3 == 1 ? 1 : 0;
            $disabled = (auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 3) && ($data->e3 == 0 && $data->e4 != 0 && $data->e2 == 0 && $data->is_archive == 1 )? '' : 'disabled';
            $e3 = '<input class="form-check-input e3" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';
            
            return $e3;
        })
        ->addColumn('e2', function ($data) {
            $checked = $data->e2 == 1 ? 'checked' : '';
            $status = $data->e2 == 1 ? 1 : 0;
            $disabled = (auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 2) && ($data->e2 == 0 && $data->is_archive == 1) ? '' : 'disabled';
            $e2 = '<input class="form-check-input e2" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';
            
            return $e2;
        })
        
        ->rawColumns(['action', 'e4', 'e3', 'e2', 'pembuat_surat', 'perihal_surat'])
        ->make(true);
    }

    $usersWithEselonAccess = User::where('hak_akses_id', 3)->get();

    $jabatanUsers = [];

    foreach ($usersWithEselonAccess as $user) {
        $jabatanUser = $user->jabatan;
        
        if ($jabatanUser) {
            $jabatanUsers[] = [
                'id' => $jabatanUser->id,
                'name' => $jabatanUser->name,
            ];
        }
    }
    return view('surat.arsip',[
        "jabatans" => $jabatanUsers
    ]);

   }
}
