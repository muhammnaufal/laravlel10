<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\surat;
use App\Models\jabatan;
use App\Models\lampiran;
use App\Models\tujuansurat;
use App\Models\RiwayatSurat;
use Illuminate\Http\Request;
use App\Models\BebanAnggaran;
use App\Models\tembusansurat;
use App\Models\dasaracuansurat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class ManajemenSuratController extends Controller
{
    public function index( Request $request )
    {
        if ($request->ajax()) {
            if (auth()->user()->hak_akses->id == 1) {
                $data = surat::where('is_archive', 0)->orderBy('id', 'desc')->get();
            }else if (auth()->user()->bidang->name == "Bagian Umum") {
                $data = surat::join('users', 'surats.pembuat_surat', '=', 'users.id')
                            // ->where('users.bidang_id', auth()->user()->bidang_id)
                            ->where(function ($query) {
                                $query->whereHas('bidang', function ($query) {
                                          $query->where('id', auth()->user()->bidang_id)
                                                ->orWhere('name', "Sub Bagian PBMN & RTK")
                                                ->orWhere('name', "Sub Bagian Kepagawaian")
                                                ->orWhere('name', "Sub Bagian Keuangan");
                                        });
                            })
                            ->where('is_archive', 0)
                            ->orderBy('surats.id', 'desc')
                            ->get(['surats.*', 'users.id as user_id']);
            }else {
                $data = surat::join('users', 'surats.pembuat_surat', '=', 'users.id')
                ->where('users.bidang_id', auth()->user()->bidang_id)
                ->where('is_archive', 0)
                ->orderBy('surats.id', 'desc')
                ->get(['surats.*', 'users.id as user_id']);
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $eselon = auth()->user()->tingkatan_eselon;

                $btn = '<span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Info Surat">
                <a href="javascript:void(0)" data-bs-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Info" class="btn btn-info btn-sm  mx-2 btnInfo"><i class="bi bi-info-square"></i></a>
              </span>';
              
                
                if (
                    auth()->user()->hak_akses->name == "Admin" || // Jika user adalah Admin
                    (auth()->user()->id == $data->pembuat_surat && $data->e4 != 1 && $data->e3 != 1 && $data->e2 == 1) || // Jika user adalah pembuat surat
                    ($eselon == 4 && $data->e4 == 0) || // Jika user adalah pejabat eselon 4 dan e4 == 0
                    ($eselon == 3 && $data->e4 != 0 && $data->e3 == 0) || // Jika user adalah pejabat eselon 3, e4 == 0, dan e3 == 0
                    ($eselon == 2 && $data->e4 != 0 && $data->e3 != 0 && $data->e2 == 0 && $data->nomor_surat != null) || // Jika user adalah pejabat eselon 2, e4 == 0, e3 == 0, dan e2 == 0
                    (auth()->user()->hak_akses_id == 4 && $data->e4 != 0 && $data->e3 != 0 && $data->e2 == 0) ||
                    ($data->status != "Final")
                ) {
                    //  edit tidak dinonaktifkan
                    $btn .= '<span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Edit Surat">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-original-title="Edit" class="edit btn btn-warning btn-sm btnEdit mx-2"><i class="bi bi-pencil-square"></i></a>
                    </span>';
                } else {
                    //  edit di ganti tombol download
                    $btn .= '<span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Unduh Surat">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encryptString($data->id).'" data-original-title="Unduh" class="download btn btn-danger btn-sm btnDownload mx-2"><i class="bi bi-download"></i></a>
                    </span>';
                }

                if ((auth()->user()->hak_akses->id == 4 || auth()->user()->hak_akses->id == 1) && $data->nomor_surat != null) {
                    $btn .= ' <span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Arsip Surat">
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Arsip" class="btn btn-secondary btn-sm btnArsip"><i class="bi bi-archive-fill"></i></a>
                    </span>';
                } else {
                    $btn .= '<span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Arsip Surat">
                     <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Arsip" class="btn btn-secondary btn-sm btnArsip disabled"><i class="bi bi-archive-fill"></i></a>
                     </span>';
                }

                if (auth()->user()->hak_akses->name == "Admin") {
                    $btn .= '<span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content=" Hapus Surat">
                     <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encryptString($data->id).'" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete" style="margin-left:8px;"><i class="bi bi-trash-fill"></i></a>
                     </span>';
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
                $disabled = auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 4 && $data->e4 == 0 && $data->e2 == 0 ? '' : 'disabled';
                $e4 = '<input class="form-check-input-1 e4" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';

                return $e4;
            })
            ->addColumn('e3', function ($data) {
                $checked = $data->e3 == 1 ? 'checked' : '';
                $status = $data->e3 == 1 ? 1 : 0;
                $disabled = auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 3 && $data->e3 == 0 && $data->e4 != 0 && $data->e2 == 0 ? '' : 'disabled';
                $e3 = '<input class="form-check-input-1 e3" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';

                return $e3;
            })
            ->addColumn('e2', function ($data) {
                $checked = $data->e2 == 1 ? 'checked' : '';
                $status = $data->e2 == 1 ? 1 : 0;
                $disabled = auth()->user()->hak_akses_id == 1 || auth()->user()->tingkatan_eselon == 2 && $data->e2 == 0 ? '' : 'disabled';
                $e2 = '<input class="form-check-input-1 e2" type="checkbox" data-status="'. $status .'" data-id="'.Crypt::encryptString($data->id).'" '. $disabled.' '. $checked .'>';

                return $e2;
            })
            ->addColumn('tahun', function ($data) {
                return $data->tanggal_surat;
            })
            
            ->rawColumns(['action', 'e4', 'e3', 'e2', 'pembuat_surat', 'perihal_surat'])
            ->make(true);
        }

        $usersWithEselonAccess = User::where('hak_akses_id', 3)->get();

        // Inisialisasi array untuk menyimpan jabatan dari pengguna
        $jabatanUsers = [];

        // Iterasi setiap pengguna dan ambil jabatan mereka
        foreach ($usersWithEselonAccess as $user) {
            // Ambil jabatan pengguna
            $jabatanUser = $user->jabatan;

            // Pastikan jabatan pengguna tidak null
            if ($jabatanUser) {
                // Tambahkan jabatan pengguna ke dalam array
                $jabatanUsers[] = [
                    'id' => $jabatanUser->id,
                    'name' => $jabatanUser->name,
                ];
            }
        }
        return view('surat.manajemenSurat',[
            "jabatans" => $jabatanUsers,
            "Dipa" => BebanAnggaran::where('jenis_lembaga', 1 )->get(),
            "Mitra"=> BebanAnggaran::where('jenis_lembaga', 2 )->get()
        ]);
    }

    public function e2($id)
    {
        try {
            if ( auth()->user()->hak_akses->name == 'Pegawai' || auth()->user()->hak_akses->name == 'Sekretaris') {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);

                $surat = surat::findOrFail($id);

                if ($surat->e2 == 0) {
                    $statusSurat = "Disetujui";
                    if (auth()->user()->hak_akses->name == "Admin") {
                        $statusUSer = "Admin";
                    }else {
                        $statusUSer = "Pejabat eselon 2";
                    }
                }else {
                    $statusSurat = "Dibatalkan Pesetujuan Eselon 2";
                    $statusUSer = "Admin";
                }

                $riwayat_surat = new RiwayatSurat();
                $riwayat_surat->riwayat = "Surat Telah " . $statusSurat . " Oleh " . $statusUSer;
                $riwayat_surat->surat_id = $surat->id;
                $riwayat_surat->save();

                $status = $surat->e2 == 1 ? 0 : 1;
                $surat->status = $surat->e2 == 1 ? "Review Eselon 2" : "Final";
                $surat->e2 = $status;
                $surat->save();


                return ['status' => true, 'pesan' => 'Surat Telah Disetujui'];
            }
        }catch(\Exception $e) {
            return ['status' => false, 'pesan' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
        }
    }
    public function e3($id)
    {
        try {
            if ( auth()->user()->hak_akses->name == 'Pegawai' || auth()->user()->hak_akses->name == 'Sekretaris') {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);

                $surat = surat::findOrFail($id);

                if ($surat->e3 == 0) {
                    $statusSurat = "Disetujui";
                    if (auth()->user()->hak_akses->name == "Admin") {
                        $statusUSer = "Admin";
                    }else {
                        $statusUSer = "Pejabat eselon 3";
                    }
                }else {
                    $statusSurat = "Dibatalkan Pesetujuan Eselon 3";
                    $statusUSer = "Admin";
                }

                $riwayat_surat = new RiwayatSurat();
                $riwayat_surat->riwayat = "Surat Telah " . $statusSurat . " Oleh " . $statusUSer;
                $riwayat_surat->surat_id = $surat->id;
                $riwayat_surat->save();

                $status = $surat->e3 == 1 ? 0 : 1;
                $surat->status = $surat->e3 == 1 ? "Review Daltu" : "Penomoran Surat";
                $surat->nomor_surat = $surat->e3 == 1 ? null : null;
                $surat->e3 = $status;
                $surat->save();


                return ['status' => true, 'pesan' => 'Surat Telah Disetujui'];
            }
        }catch(\Exception $e) {
            return ['status' => false, 'pesan' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
        }
    }
    public function e4($id)
    {
        try {
            if ( auth()->user()->hak_akses->name == 'Pegawai' || auth()->user()->hak_akses->name == 'Sekretaris') {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);

                $surat = surat::findOrFail($id);

                if ($surat->e4 == 0) {
                    $statusSurat = "Disetujui";
                    if (auth()->user()->hak_akses->name == "Admin") {
                        $statusUSer = "Admin";
                    }else {
                        $statusUSer = "Pejabat eselon 4";
                    }
                }else {
                    $statusSurat = "Dibatalkan Pesetujuan Eselon 4";
                    $statusUSer = "Admin";
                }

                $riwayat_surat = new RiwayatSurat();
                $riwayat_surat->riwayat = "Surat Telah " . $statusSurat . " Oleh " . $statusUSer;
                $riwayat_surat->surat_id = $surat->id;
                $riwayat_surat->save();

                $status = $surat->e4 == 1 ? 0 : 1;
                $surat->status = $surat->e4 == 1 ? "Review Dalnis" : "Review Daltu";
                $surat->e4 = $status;
                $surat->save();
            }

            return ['status' => true, 'pesan' => 'Surat Telah Disetujui'];
        }catch(\Exception $e) {
            return ['status' => false, 'pesan' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
        }
    }

    public function arsip($id)
    {
        try {
            if (auth()->user()->hak_akses_id == 4 || auth()->user()->hak_akses_id == 1) {
                $id = Crypt::decryptString($id);

                $surat = surat::findOrFail($id);
                $status = $surat->is_archive == 0 ? "Arsipkan" : "Keluarkan Dari Arsip";
                if ($surat->is_archive == 0) {
                    $surat->is_archive = 1;
                }else {
                    $surat->is_archive = 0;
                }
                $surat->save();

                return ['status' => true, 'pesan' => 'Surat Telah Di ' . $status];
            }else {
                return ['status' => false, 'pesan' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
            }
        }catch(\Exception $e) {
            return ['status' => false, 'pesan' => 'Terjadi kesalahan pada sistem dengan kode : 500'];
        }
    }

    public function detail($id)
    {
        $id = Crypt::decryptString($id);

        $surat = surat::with('tujuan_surat', 'dasar_acuan_surat', 'nama_pejabat', 'tembusan_surat', 'lampiran', 'riwayat_surat', 'beban_anggaran')->find($id);

        foreach ($surat->tujuan_surat as $tujuan) {
            $tujuan->makeHidden('id', 'surat_id', 'created_at', 'updated_at');
        }

        foreach ($surat->dasar_acuan_surat as $dasar) {
            $dasar->makeHidden('id', 'surat_id', 'created_at', 'updated_at');
        }

        foreach ($surat->lampiran as $lam) {
            $lam->setAttribute('encrypted_id', Crypt::encryptString($lam->id));
            $lam->makeHidden('id','surat_id', 'created_at', 'updated_at');
        }



        $jabatan = User::with('jabatan')->find($surat->nama_pejabat);
        $surat->makeHidden(['id', 'created_at', 'updated_at']);
        return ['surat' => $surat, 'jabatan' => $jabatan];
    }

    public function update( Request $request)
    {
        // dd($request->all());
        $deletePDFArray = json_decode($request->deletePDF, true);

        $id = Crypt::decryptString($request->id);
        $surat = surat::find($id);

        foreach ($surat->tujuan_surat as $tujuan) {
            $tujuan->delete();
        }

        foreach ($surat->dasar_acuan_surat as $dasar) {
            $dasar->delete();
        }

        foreach ($surat->tembusan_surat as $tembusan) {
            $tembusan->delete();
        }

        if (auth()->user()->hak_akses_id == 4) {
            $surat->nomor_surat = $request->nomor_surat;
            $surat->status = "Review Eselon 2";
        }else {
            $surat->nomor_surat = null;
        }

        $surat->tanggal_surat = $request->tanggal_surat;
        $surat->keterangan_lampiran	 = $request->lampiran_surat;
        $surat->perihal_surat = $request->perihal_surat;

        $surat->{'alamat_instansi/pejabat'} = $request->alamat_tujuan;
        $surat->rincian_pelaksanaan_penugasan = $request->rincian_pelaksanaan_penugasan;
        $surat->beban_anggaran_id = $request->beban_anggaran_id;

        $user = User::where("NIP" , $request->nip_pejabat)->first();
        $surat->nama_pejabat = $user->id;

        $jabatan = jabatan::find($request->jabatan_id);

        if ($jabatan) {
            $nama_jabatan = $jabatan->name;
        } else {
            $nama_jabatan = "<Jabatan>";
        }

        if (
            (strpos($request->perihal_surat, '&lt;script&gt;') !== false || strpos($request->perihal_surat, '&lt;link&gt;') !== false || strpos($request->perihal_surat, '&lt;style&gt;') !== false) ||
            (strpos($request->rincian_pelaksanaan_penugasan, '&lt;script&gt;') !== false || strpos($request->rincian_pelaksanaan_penugasan, '&lt;link&gt;') !== false || strpos($request->perihal_surat, '&lt;style&gt;') !== false) ||
            (strpos($request->beban_anggaran, '&lt;script&gt;') !== false || strpos($request->beban_anggaran, '&lt;link&gt;' || strpos($request->perihal_surat, '&lt;style&gt;') !== false) !== false)
            ) {
            return response()->json(['error' => 'Input tidak valid. Tolong hapus tag <script>, <style> atau <link>'], 400);
        }

        $bebanAnggaran = BebanAnggaran::find($request->beban_anggaran_id);

        $data = [
            "nomor_surat" => $request->nomor_surat ? $request->nomor_surat : "<Nomor_Surat>",
            "lampiran_surat" => $request->lampiran_surat ? $request->lampiran_surat : "<Lampiran_Surat>",
            "perihal_surat" => $request->perihal_surat ? $request->perihal_surat : "<Perihal_Surat>",
            "tanggal_surat" =>  $request->tanggal_surat ? Carbon::parse($request->tanggal_surat)->locale('id')->isoFormat('D MMMM Y') : "<Tanggal_Surat>",

            "tujuan_surat" => $request->tujuan_surat,
            "alamat_tujuan" => $request->alamat_tujuan ?? "<Alamat_Tujuan>",


            "dasar_acuan" => $request->dasar_acuan,
            "rincian_pelaksanaan_penugasan" => $request->rincian_pelaksanaan_penugasan ? $request->rincian_pelaksanaan_penugasan : "<Rincian_pelaksanaan_penugasan>",
            "beban_anggaran" =>$bebanAnggaran->nama_lembaga ? $bebanAnggaran->nama_lembaga : "<Beban_Anggaran>",


            "Jabatan" => $nama_jabatan,
            "nama_pejabat" => $request->nama_pejabat ? $request->nama_pejabat : "<Nama_Pejabat>",
            "nip_pejabat" => $request->nip_pejabat ? $request->nip_pejabat : "<NIP_Pejabat>",

            "tembusan_surat" => $request->tembusan_surat,
        ];

        $filePathOld = $surat->pdf;
        if (file_exists($filePathOld)) {
            unlink($filePathOld);
        }

        $pdf = PDF::loadView('pdf.pdf_preview', compact('data'));
        $uniq = auth()->user()->hak_akses_id == 4 ? $request->nomor_surat : uniqid();
        $pdfPath = 'public/pdf/' . $uniq . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $surat->pdf = 'storage/pdf/'. $uniq .'.pdf';
        $surat->save();

        foreach ($request->tujuan_surat as $tujuan) {
            $tujuan_surat = new tujuansurat();
            $tujuan_surat->surat_id = $surat->id;
            $tujuan_surat->tujuan_surat	= $tujuan;
            $tujuan_surat->save();
        }
        foreach ($request->dasar_acuan as $acuan) {
            $dasar_acuan = new dasaracuansurat();
            $dasar_acuan->surat_id = $surat->id;
            $dasar_acuan->dasar_acuan_surat = $acuan;
            $dasar_acuan->save();
        }
        if ($request->has('tembusan_surat')) {
            foreach ($request->tembusan_surat as $tembusan) {
                if ($tembusan != null) {
                    $tembusan_surat = new tembusansurat();
                    $tembusan_surat->surat_id = $surat->id;
                    $tembusan_surat->tembusan_surat = $tembusan;
                    $tembusan_surat->save();
                }
            }
        }

        foreach ($deletePDFArray as $deletePDF) {
            $id = Crypt::decryptString($deletePDF);

            $lampiran = Lampiran::find($id);

            $filePath = $lampiran->lampiran;
            $fullPath = public_path($filePath);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $lampiran->delete();
        }


        $pdfFiles = $request->file('lampiran');

        if ($pdfFiles) {
            foreach ($pdfFiles as $pdfFile) {
                $lampiran = new lampiran();
                $path = $pdfFile->storeAs('public/pdf', uniqid() . '_' . $pdfFile->getClientOriginalName());

                $storagePath = str_replace('public/', 'storage/', $path);

                $lampiran->lampiran = $storagePath;
                $lampiran->surat_id = $surat->id;
                $lampiran->save();
            }
        }

        // if (auth()->user()->hak_akses->name == "Admin") {
        //     $statusUSer = "Admin";
        // }else if (auth()->user()->id == $surat->pembuat_surat) {
        //     $statusUSer = "Pembuat Surat";
        // }else if (auth()->user()->tingkatan_eselon == 4) {
        //     $statusUSer = "Eselon 4";
        // }else if (auth()->user()->tingkatan_eselon == 3) {
        //     $statusUSer = "Eselon 3";
        // }else if (auth()->user()->tingkatan_eselon == 2) {
        //     $statusUSer = "Eselon 2";
        // }else if (auth()->user()->hak_akses_id == 4) {
        //     $statusUSer = "Sekretaris";
        // }

        $riwayat_surat = new RiwayatSurat();
        $riwayat_surat->riwayat = "Surat Telah Diperbarui Oleh ";
        $riwayat_surat->surat_id = $surat->id;
        $riwayat_surat->save();
    }

    public function destroy($id)
    {
        try {
            if ( auth()->user()->hak_akses->name !== 'Admin' ) {
                abort(403);
            }else {
                $id = Crypt::decryptString($id);
                $surat = Surat::find($id);

                $dasar_acuan_surat = dasaracuansurat::where('surat_id', $surat->id)->get();
                if ($dasar_acuan_surat) {
                    $dasar_acuan_surat->each(function ($pivot) {
                        $pivot->delete();
                    });
                }

                $lampiran = lampiran::where('surat_id', $surat->id)->get();
                if ($lampiran) {
                    $lampiran->each(function ($pivot) {
                        $pivot->delete();
                    });
                }

                $riwayat_surat = RiwayatSurat::where('surat_id', $surat->id)->get();
                if ($riwayat_surat) {
                    $riwayat_surat->each(function ($pivot) {
                        $pivot->delete();
                    });
                }

                $tembusan_surat = tembusansurat::where('surat_id', $surat->id)->get();
                if ($tembusan_surat) {
                    $tembusan_surat->each(function ($pivot) {
                        $pivot->delete();
                    });
                }

                $tujuan_surat = tujuansurat::where('surat_id', $surat->id)->get();
                if ($tujuan_surat) {
                    $tujuan_surat->each(function ($pivot) {
                        $pivot->delete();
                    });
                }

                $surat->delete();

                return ['status' => true, 'pesan' => 'Anda berhasil menghapus Surat'];
            }
        } catch(\Exception $e) {
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }

    public function download($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $surat = Surat::find($id);

            $file = public_path() . '/' . $surat->pdf;
            return response()->download($file, $surat->nomor_surat);
            
        } catch(\Exception $e) {
            // return dd($e);
            return ['status' => false, 'Terjadi Kesalahan Pada Sistem Dengan Kode : 500'];
        }
    }
}
