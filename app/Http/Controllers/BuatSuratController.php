<?php

namespace App\Http\Controllers;

use App\Models\BebanAnggaran;
use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\surat;
use App\Models\jabatan;

use App\Models\lampiran;
use App\Models\tujuansurat;
use Illuminate\Http\Request;
use App\Models\tembusansurat;
use App\Models\dasaracuansurat;
use App\Models\RiwayatSurat;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;

class BuatSuratController extends Controller
{
    public function index()
    {
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

        // Tampilkan data jabatan pengguna
        // return dd($jabatanUsers);
        return view('surat.buatSurat', [
            "jabatans" => $jabatanUsers,
            "Dipa" => BebanAnggaran::where('jenis_lembaga', 1)->get(),
            "Mitra" => BebanAnggaran::where('jenis_lembaga', 2)->get()
        ]);
    }


    public function fetchjabatan(Request $request)
    {
        $data['jabatan'] = User::where("jabatan_id", $request->jabatan_id)->where('hak_akses_id', 3)->get(["name", "nip"]);

        if ($data['jabatan']->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        return response()->json(['status' => true, 'data' => $data['jabatan']]);
    }

    public function fetchnip(Request $request)
    {
        $nip = str_replace(' ', '', $request->nip);

        $data['user'] = User::where('NIP', $nip)->where('hak_akses_id', 3)->with('jabatan')->first(['name', 'jabatan_id']);

        if (!$data['user']) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        if ($data['user']->jabatan && $data['user']->jabatan->count() > 0) {
            return response()->json(['status' => true, 'data' => $data['user']]);
        } else {
            return response()->json(['status' => false, 'message' => 'No jabatan data found']);
        }
    }

    public function pdfview(Request $request)
    {
        // return $request->all();
        $jabatan = jabatan::find($request->jabatan_id);

        if ($jabatan) {
            $nama_jabatan = $jabatan->name;
        } else {
            $nama_jabatan = "<Jabatan>";
        }

        $bebanAnggaran = BebanAnggaran::find($request->beban_anggaran_id);

        if (
            (strpos($request->perihal_surat, '&lt;script&gt;') !== false || strpos($request->perihal_surat, '&lt;link&gt;') !== false) ||
            (strpos($request->rincian_pelaksanaan_penugasan, '&lt;script&gt;') !== false || strpos($request->rincian_pelaksanaan_penugasan, '&lt;link&gt;') !== false) ||
            (strpos($request->beban_anggaran, '&lt;script&gt;') !== false || strpos($request->beban_anggaran, '&lt;link&gt;') !== false)
        ) {
            return response()->json(['error' => 'Input tidak valid. Tolong hapus tag <script> atau <link>'], 400);
        }


        $data = [
            "nomor_surat" => $request->nomor_surat ? $request->nomor_surat : "<Nomor_Surat>",
            "lampiran_surat" => $request->lampiran_surat ? $request->lampiran_surat : "<Lampiran_Surat>",
            "perihal_surat" => $request->perihal_surat ? $request->perihal_surat : "<Perihal_Surat>",
            "tanggal_surat" =>  $request->tanggal_surat ? Carbon::parse($request->tanggal_surat)->locale('id')->isoFormat('D MMMM Y') : "<Tanggal_Surat>",

            "tujuan_surat" => $request->tujuan_surat,
            "alamat_tujuan" => $request->alamat_tujuan ?? "<Alamat_Tujuan>",


            "dasar_acuan" => $request->dasar_acuan,
            "rincian_pelaksanaan_penugasan" => $request->rincian_pelaksanaan_penugasan ? $request->rincian_pelaksanaan_penugasan : "<Rincian_pelaksanaan_penugasan>",
            "beban_anggaran" => $bebanAnggaran->nama_lembaga ? $bebanAnggaran->nama_lembaga : "<Beban_Anggaran>",


            "Jabatan" => $nama_jabatan,
            "nama_pejabat" => $request->nama_pejabat ? $request->nama_pejabat : "<Nama_Pejabat>",
            "nip_pejabat" => $request->nip_pejabat ? $request->nip_pejabat : "<NIP_Pejabat>",

            "tembusan_surat" => $request->tembusan_surat,
        ];
        // dd($data["rincian_pelaksanaan_penugasan"]);
        $pdf = PDF::loadView('pdf.pdf_preview', compact('data'));

        return $pdf->stream('pdf.pdf_preview.pdf');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $surat = new surat();
        $surat->tanggal_surat = $request->tanggal_surat;
        $surat->keterangan_lampiran     = $request->lampiran_surat;
        $surat->perihal_surat = $request->perihal_surat;

        $surat->{'alamat_instansi/pejabat'} = $request->alamat_tujuan;
        $surat->rincian_pelaksanaan_penugasan = $request->rincian_pelaksanaan_penugasan;
        $surat->beban_anggaran_id = $request->beban_anggaran_id;

        $nip_pejabat = str_replace(' ', '', $request->nip_pejabat);
        $user = User::where("NIP", $nip_pejabat)->first();
        $surat->nama_pejabat = $user->id;

        $surat->pembuat_surat = auth()->user()->id;
        $surat->status = "Review Dalnis";
        $surat->bidang_id = auth()->user()->bidang_id;

        $jabatan = jabatan::find($request->jabatan_id);

        if ($jabatan) {
            $nama_jabatan = $jabatan->name;
        } else {
            $nama_jabatan = "<Jabatan>";
        }

        if (
            (strpos($request->perihal_surat, '&lt;script&gt;') !== false || strpos($request->perihal_surat, '&lt;link&gt;') !== false) ||
            (strpos($request->rincian_pelaksanaan_penugasan, '&lt;script&gt;') !== false || strpos($request->rincian_pelaksanaan_penugasan, '&lt;link&gt;') !== false) ||
            (strpos($request->beban_anggaran, '&lt;script&gt;') !== false || strpos($request->beban_anggaran, '&lt;link&gt;') !== false)
        ) {
            return response()->json(['error' => 'Input tidak valid. Tolong hapus tag <script> atau <link>'], 400);
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
            "beban_anggaran" => $bebanAnggaran->nama_lembaga ? $bebanAnggaran->nama_lembaga : "<Beban_Anggaran>",


            "Jabatan" => $nama_jabatan,
            "nama_pejabat" => $request->nama_pejabat ? $request->nama_pejabat : "<Nama_Pejabat>",
            "nip_pejabat" => $request->nip_pejabat ? $request->nip_pejabat : "<NIP_Pejabat>",

            "tembusan_surat" => $request->tembusan_surat,
        ];

        $pdf = PDF::loadView('pdf.pdf_preview', compact('data'));
        $uniq = uniqid();
        $pdfPath = 'public/pdf/' . $uniq . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $surat->pdf = 'storage/pdf/' . $uniq . '.pdf';
        $surat->save();

        foreach ($request->tujuan_surat as $tujuan) {
            $tujuan_surat = new tujuansurat();
            $tujuan_surat->surat_id = $surat->id;
            $tujuan_surat->tujuan_surat    = $tujuan;
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

        $pdfFiles = $request->file('lampiran');
        if (isset($pdfFiles) && count($pdfFiles) > 0) {
            foreach ($pdfFiles as $pdfFile) {
                $lampiran = new lampiran();
                $path = $pdfFile->storeAs('public/pdf', uniqid() . '_' . $pdfFile->getClientOriginalName());

                $storagePath = str_replace('public/', 'storage/', $path);

                $lampiran->lampiran = $storagePath;
                $lampiran->surat_id = $surat->id;
                $lampiran->save();
            }
        }

        $riwayat_surat = new RiwayatSurat();
        $riwayat_surat->riwayat = "Surat Telah Dibuat Oleh " . auth()->user()->name;
        $riwayat_surat->surat_id = $surat->id;
        $riwayat_surat->save();
    }


    public function pdflink(Request $req)
    {
        $pdfFiles = $req->file('lampiran');

        $lampiranBase64 = [];

        foreach ($pdfFiles as $pdfFile) {
            // Baca konten file
            $content = File::get($pdfFile->getRealPath());

            // Encode konten file menjadi base64
            $base64Content = base64_encode($content);

            // Tambahkan base64 encoded content ke dalam array
            $lampiranBase64[] = $base64Content;
        }

        return $lampiranBase64;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(surat $surat)
    {
        //
    }
}
