@extends('layout.main')

@section('title', 'Manajemen Surat')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/tutorials/timelines/timeline-4/assets/css/timeline-4.css">
    <style>
      label {
          color: rgb(0, 0, 0);
      }
      .dataTables_wrapper .dataTables_filter {
          margin-bottom: 20px;
      }
      .text-center {
          text-align: justify !important;
      }
      .animate__fadeInDown {
        --animate-duration: 0.5s;
      }
      .form-control, .form-select {
        border: var(--bs-border-width) solid #8693a1 !important;
      }
      .form-check-input-1[type=checkbox] {
          border-radius: 50% !important;
      }
      .form-check-input-1:checked {
          background-color: #40d52a !important;
          border-color: #40d52a !important;
      }
      .form-check-input-1 {
        width: 30px !important;
        height: 30px !important;
        border: 2px solid rgba(0, 0, 0, 0.351) !important;
      }
      .content .navbar .sidebar-toggler, .content .navbar .navbar-nav .nav-link i {
          margin-right: 1.5rem;
      }
      .btn-1 {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: #0d73f0;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            border-radius: 3px 20px 20px 3px !important;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .wrap {
          white-space: normal !important; /* or white-space: break-spaces; */
        }
        p {
          line-height: 1.5 !important; /* Sesuaikan nilai sesuai kebutuhan Anda */
        }
        .ratio-16x9 {
            --bs-aspect-ratio: 100%;
        }
        .modal { overflow-y: auto }
        .button {
          color: rgb(255, 255, 255);
          transition: color 0.25s, border-color 1s;
          text-decoration: none;
        }
        .button {
          background: none;
          border-bottom: 2px solid;
          font: inherit;
          line-height: 1;
          margin: 0.5em;
          padding: .2em .5em;
        }
        .active {
          border-color: #0d6efd;
        }
        .button:hover,
        .button:focus {
          border-color: #0d6efd;
          color: #fff;
        }
        .raise:hover,
        .raise:focus {
          box-shadow: 0 0.5em 0.5em -0.4em rgba(0, 128, 0, 0.4);
          transform: translateY(-0.25em);
        }
        a {
          text-decoration: none;
        }
        .desktop-text {
            display: inline;
        }
        .mobile-logo {
            display: none;
        }

        /* CSS untuk mobile */
        @media screen and (max-width: 768px) {
            .desktop-text {
                display: none;
            }
            .mobile-logo {
                display: inline;
            }
        }
        .bg-red {
          background-color: #B30B00;
        }
        @media (max-width: 576px) {
          #namePDF {
            font-size: 12px;
            padding-left: 10px;
            margin-left: 5px;
          }
        }
        .bg-light {
            --bs-bg-opacity: 1;
            background-color: rgb(224 224 224) !important;
        }
        .ql-container {
            height: 80%;
        }
    </style>
@endsection

@section('navtop')
<a href="{{ route("surat.manajemen_surat.show") }}" class="button raise {{ Request::is('*disposisi_surat') ? 'active' : '' }}">
  <span class="desktop-text">{{ auth()->user()->hak_akses_id == 1 ? "Manajemen Surat" : "Disposisi Surat"}}</span>
  <span class="mobile-logo"><i class="bi bi-kanban-fill"></i></span>
</a>
<a href="{{ route('surat.buat_surat.show') }}" class="mx-3 button raise">
  <span class="desktop-text">Buat Surat</span>
  <span class="mobile-logo"><i class="bi bi-envelope"></i></span>
</a>
<a href="{{ route('surat.arsip_surat.show') }}" class="button raise">
  <span class="desktop-text">Arsip</span>
  <span class="mobile-logo"><i class="bi bi-archive"></i></span>
</a>
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-white shadow-lg text-center rounded p-4">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="mb-0">Manajemen Surat</h6>
        <select id="filter_tahun" class="form-select" style="width: 200px;">
          <option value="" selected hidden disabled>Pilih Tahun</option>
          <option value="2020">2020</option>
          <option value="2021">2021</option>
          <option value="2022">2022</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
        </select>
      </div>
      <div>
        <table id="myTable" class="table table-striped table-hover border responsive nowrap" width="100%">
          <thead>
            <tr>
              <th>Nomor Surat</th>
              <th>Perihal Penugasan</th>
              <th>Pembuat Surat</th>
              <th>Status</th>
              <th>E2</th>
              <th>E3</th>
              <th>E4</th>
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if(session()->has('loginSuccess'))
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script>
        iziToast.show({
            title: 'Login Berhasil',
            message: "Selamat Datang Kembali {{ auth()->user()->name }}",
            position: 'topRight',
            color: 'green',
        });
    </script>
  @endif

  <!----------------------------------------------------------Modal Detail-------------------------------------->
  <div class="modal fade" id="ModalDetail" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="labelModal" style="color: black">Tambah Data User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="p-2 border-2 border-bottom">
            <div class="d-flex">
              <p style="margin-right:51px"><b>Nomor Surat</b></p>
              <p id="nomor_surat">

              </p>
            </div>
            <div class="d-flex">
              <p style="margin-right:43px"><b>Tanggal Surat</b></p>
              <p id="tanggal_surat">

              </p>
            </div>
            <div>
              <p style="margin-right:43px; margin-bottom: -1px"><b>Perihal Surat</b></p>
              <p id="perihal_surat">

              </p>
            </div>
            <div>
              <p style="margin-right:43px; margin-bottom: -1px"><b>Instansi / Pejabat Tujuan Surat</b></p>
              <div id="tujuan_surat">

              </div>
            </div>
            <div>
              <p style="margin-right:43px; margin-bottom: -1px"><b>Alamat Tujuan</b></p>
              <p id="alamat_tujuan">

              </p>
            </div>
          </div>
          <div class="p-2 border-2 border-bottom">
            <div>
              <p style="margin-right:51px; margin-bottom: -1px"><b>Dasar Acuan Penugasan</b></p>
              <div id="dasar_acuan_penugasan">

              </div>
            </div>
            <div>
              <p style="margin-right:43px;margin-bottom: -1px"><b>Pelaksanaan</b></p>
              <p id="pelaksanaan">

              </p>
            </div>
          </div>
          <div class="p-2 border-2 border-bottom">
            <div>
              <p class="fs-5"><b>Pejabat Penandatangan</b></p>
              <div class="d-flex" style="margin-bottom: -10px">
                <p style="margin-right:70px;">
                  <b>Nama</b>
                </p>
                <p id="nama">

                </p>
              </div>

              <div class="d-flex" style="margin-bottom: -10px">
                <p style="margin-right:51px;">
                  <b>Jabatan</b>
                </p>
                <p id="jabatan">

                </p>
              </div>
              <div class="d-flex">
                <p style="margin-right:87px;"><b>NIP</b></p>
                <p id="nip">

                </p>
              </div>

            </div>
          </div>
          <div class="p-2 border-2 border-bottom">
            <div>
              <p style="margin-right:51px; margin-bottom: -1px"><b>Tembusan Surat</b></p>
              <div id="tembusan_surat">

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:void(0)" id="riwayatSurat" class="btn btn-warning">Riwayat Surat</a>
          <a href="javascript:void(0)" id="openPDF" data-link="dwasd" class="btn btn-danger">PDF</a>
        </div>
      </div>
    </div>
  </div>

 <!----------------------------------------------------------Modal Edit---------------------------------------->
<div class="modal fade" id="ModalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="javascript:void(0)" id="form_surat" enctype="multipart/form-data">
          <div class="border-3 border p-3 rounded mb-5">
          <h4 class="mb-3">*Identitas Surat</h4>
          <div class="row mb-3">
              <div class="col-md-6">
                <input type="hidden" name="id" id="id">
                <span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="Nomor Surat Hanya Bisa Di Isi Sekretaris">
                  <label for="nomor_surat">Nomor Surat</label>
                  <input type="text" name="nomor_surat" id="nomor_surat_input" class="form-control" placeholder="Nomor Surat">
                </span>
              </div>
              <div class="col-md-6">
                  <label for="tanggal_surat">Tanggal Surat</label>
                  <input type="date" name="tanggal_surat" id="tanggal_surat_input" class="form-control" placeholder="Tanggal Surat">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-md-12">
                  <label for="lampiran_surat">Lampiran Surat</label>
                  <input type="text" name="lampiran_surat" id="lampiran_surat_input" class="form-control" placeholder="Lampiran Surat">
              </div>
          </div>
          <div class="row" style="margin-bottom: 100px;">
              <div class="col-md-12">
                  <label for="perihal_surat">Perihal Surat</label>
                  <textarea type="text" name="perihal_surat" id="perihal_surat_input" class="form-control perihal_surat d-none" placeholder="Perihal Surat" rows="3"></textarea>
                  <div id="editor_perihal_surat" class="perihal_surat" style="font-size: 14px;">
                  </div>
              </div>
          </div>
          <hr>
          <div class="row" id="dynamic_form-3">
            {{-- <div class="form-group baru-data-3">
              <div class="col-md-12">
                  <label for="tujuan_surat">Tujuan Surat</label>
                  <textarea id="tujuan_surat" name="tujuan_surat[]" placeholder="Tujuan Surat" class="form-control " rows="3"></textarea>
              </div>
              <div class="button-group d-flex justify-content-center mt-2 mb-3">
                  <button type="button" class="btn btn-success btn-tambah-3 mx-2">Tambah Isian Tujuan Surat <i class="fa fa-plus"></i></button>
                  <button type="button" class="btn btn-danger btn-hapus-3" style="display:none;">Hapus <i class="fa fa-times"></i></button>
              </div>
            </div> --}}
          </div>
          <div class="row mb-3">
              <div class="col-md-12">
                  <label for="alamat_tujuan">Alamat Surat</label>
                  <input type="text" name="alamat_tujuan" id="alamat_tujuan_input" class="form-control" placeholder="Alamat Instansi / Pejabat">
              </div>
          </div>
        </div>
{{-- -------------------------------------------------------------------------------isian Surat--------------------------------------------------}}
          <div class="border-3 border p-3 rounded mb-3" style="min-height:600px;">
            <h4 class="mb-3">*Isian Surat</h4>
            <div class="row" id="dynamic_form">
              {{-- <div class="form-group baru-data">
                <div class="col-md-12">
                    <label for="dasar_acuan">Dasar Acuan Surat</label>
                    <textarea id="dasar_acuan" name="dasar_acuan[]" placeholder="Dasar Acuan Penugasan" class="form-control dasar_acuan" rows="3"></textarea>
                </div>
                <div class="button-group d-flex justify-content-center mt-2 mb-3">
                    <button type="button" class="btn btn-success btn-tambah mx-2">Tambah Isian Dasar Acuan <i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-hapus" style="display:none;">Hapus <i class="fa fa-times"></i></button>
                </div>
              </div> --}}
            </div>
            <div class="row mb-5  ">
              <div class="col-md-12">
                  <label for="rincian_pelaksanaan_penugasan">Rincian Pelaksanaan Penugasan</label>
                  <textarea type="text" name="rincian_pelaksanaan_penugasan" id="rincian_pelaksanaan_penugasan_input" class="form-control d-none" placeholder="kami....." rows="3"></textarea>
                  <div id="editor_rincian_pelaksanaan_penugasan" class="rincian_pelaksanaan_penugasan" style="font-size: 14px;">
                  </div>
              </div>
            </div>
            <div class="row mb-3" style="margin-top: 80px;">
              <label for="beban_anggaran" class="mb-3">Beban Anggaran</label>
              <div class="col-md-6">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                  <label class="form-check-label mb-2" for="flexRadioDefault1">
                    Dipa
                  </label>
                  <select name="beban_anggaran_id" class="form-select" id="beban_anggaran_id_dipa" disabled>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                  <label class="form-check-label mb-2" for="flexRadioDefault2">
                    Mitra
                  </label>
                  <select name="beban_anggaran_id" class="form-select" id="beban_anggaran_id_mitra" disabled>
                  </select>
                </div>
              </div>
            </div>
          </div>
{{----------------------------------------------------------------------------------Penandatangan Surat--------------------------------------------------}}
            <div class="border-3 border p-3 rounded mb-3">
              <h4 class="mb-3">*Penandatangan Surat</h4>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="jabatan_id">Jabatan</label>
                  <select class="form-select" aria-label="Default select example" name="jabatan_id" id="jabatan_id_input">
                    <option value="" disabled selected hidden>Pilih Jabatan</option>
                    @foreach($jabatans as $jabatan)
                      <option value="{{ $jabatan['id'] }}">{{ $jabatan['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_pejabat">Nama Pejabat</label>
                    <input type="text" name="nama_pejabat" id="nama_pejabat_input" class="form-control" placeholder="Nama Pejabat">
                </div>
                <div class="col-md-6">
                  <label for="nip">NIP</label>
                  <div class="input-group">
                    <input type="text" name="nip_pejabat"  id="nip_input" class="form-control" placeholder="NIP">
                    <div class="input-group-append">
                      <button class="btn-1 btn-primary" id="search_nip" type="button">
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
{{-- -------------------------------------------------------------------------------Tembusan Surat--------------------------------------------------}}
          <div class="border-3 border p-3 rounded mb-3">
            <h4 class="mb-3">*Tembusan Surat</h4>
            <div class="row" id="dynamic_form-2">
              {{-- <div class="form-group baru-data-2">
                <div class="col-md-12">
                    <textarea id="tembusan_surat" name="tembusan_surat[]" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="3"></textarea>
                </div>
                <div class="button-group d-flex justify-content-center mt-2 mb-3">
                    <button type="button" class="btn btn-success btn-tambah-2 mx-2">Tambah Isian Tembusan <i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-hapus-2" style="display:none;">Hapus <i class="fa fa-times"></i></button>
                </div>
              </div> --}}
            </div>
          </div>
{{-- -------------------------------------------------------------------------------Lampiran Surat--------------------------------------------------}}
          <div class="border-3 border p-3 rounded mb-3">
            <h4 class="mb-3">*Lampiran Surat</h4>

            <div class="mb-5" id="editPDF">
              {{-- <div class="d-flex mb-3">
                <div class="d-flex align-items-center" style="padding-left:20px; margin-left:auto;" id="deletePDF">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#B30B00" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                  </svg>
                </div>
                <div class="bg-red rounded-pill w-100 d-flex align-items-center" style="padding-left:20px; margin-left:10px;" id="namePDF">
                  <small class="text-white"></small>
                </div>
                <div class="d-flex align-items-center" style="padding-left:20px; margin-left:auto;" id="deletePDF">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#B30B00" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                  </svg>
                </div>
              </div>                                 --}}
            </div>

            <div class="row" id="dynamic_form-4">
              <div class="form-group baru-data-4" style="margin-bottom: -25px">
                <div class="col-md-12">
                  <input class="form-control formFile" type="file" name="lampiran[]" id="formFile">
                </div>
                <div class="button-group d-flex justify-content-center mt-4 mb-3">
                    <button type="button" class="btn btn-success btn-tambah-4 mx-2">
                      <span class="desktop-text">Tambah Lampiran Surat</span>
                      <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-hapus-4" style="display:none;">
                      <span class="desktop-text">Hapus</span>
                      <span class="mobile-logo"><i class="fa fa-times"></i></span>
                    </button>
                </div>
              </div>
            </div>
          </div>
        </form>
        <button id="show_layout" class="btn btn-warning">
          <span class="desktop-text">Layout Surat</span>
          <span class="mobile-logo"><i class="bi bi-info-square"></i></span>
        </button>
        <button id="show_preview" class="btn btn-primary">
          <span class="desktop-text">Preview Surat</span>
          <span class="mobile-logo"><i class="bi bi-eye"></i></span>
        </button>
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save</button>
      </div> --}}
    </div>
  </div>
</div>

  <!----------------------------------------------------------Modal PDF---------------------------------------->
{{--<div class="modal fade" id="ModalPdf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div> --}}

  <!----------------------------------------------------------Modal History---------------------------------------->
<div class="modal fade" id="ModalRiwayat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="title-riwayat">Riwayat Surat</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Timeline 4 - Bootstrap Brain Component -->
        <section class="bsb-timeline-4 bg-light py-3 py-md-5 py-xl-8">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-10 col-md-12 col-xl-10 col-xxl-9">

                <ul class="timeline">
                  {{-- <li class="timeline-item left">
                    <div class="timeline-body">
                      <div class="timeline-meta">
                        <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                          <span>Released on 05 May 2021</span>
                        </div>
                      </div>
                      <div class="timeline-content timeline-indicator">
                        <div class="card border-0 shadow">
                          <div class="card-body p-xl-4">
                            <h6 class="card-title mb-2">Bootstrap 5</h6>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li> --}}
                  {{-- <li class="timeline-item right">
                    <div class="timeline-body">
                      <div class="timeline-meta">
                        <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                          <span>Released on 18 Jan 2018</span>
                        </div>
                      </div>
                      <div class="timeline-content timeline-indicator">
                        <div class="card border-0 shadow">
                          <div class="card-body p-xl-4">
                            <h2 class="card-title mb-2">Bootstrap 4</h2>
                            <h6 class="card-subtitle text-secondary mb-3">No Active Support</h6>
                            <p class="card-text m-0">Get started with Bootstrap, the world’s most popular framework for building responsive, mobile-first sites, with jsDelivr and a template starter page. Bootstrap 4 has no active support.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="timeline-item left">
                    <div class="timeline-body">
                      <div class="timeline-meta">
                        <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                          <span>Released on 19 Aug 2013</span>
                        </div>
                      </div>
                      <div class="timeline-content timeline-indicator">
                        <div class="card border-0 shadow">
                          <div class="card-body p-xl-4">
                            <h2 class="card-title mb-2">Bootstrap 3</h2>
                            <h6 class="card-subtitle text-secondary mb-3">No Active Support</h6>
                            <p class="card-text m-0">Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web. Bootstrap 3 has no active support.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="timeline-item right">
                    <div class="timeline-body">
                      <div class="timeline-meta">
                        <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                          <span>Released on 31 Jan 2012</span>
                        </div>
                      </div>
                      <div class="timeline-content timeline-indicator">
                        <div class="card border-0 shadow">
                          <div class="card-body p-xl-4">
                            <h2 class="card-title mb-2">Bootstrap 2</h2>
                            <h6 class="card-subtitle text-secondary mb-3">No Active Support</h6>
                            <p class="card-text m-0">Sleek, intuitive, and powerful front-end framework for faster and easier web development. Bootstrap 2 is no longer officially supported.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li> --}}
                </ul>

              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>

  <!----------------------------------------------------------Modal PDF---------------------------------------->

<div class="modal fade" id="ModalPDf" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titlePDF" style="color: black"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Preview</button>
          </li>
          {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Lampiran 1</button>
          </li> --}}
        </ul>
        <div class="tab-content mt-2" id="myTabContent">
          <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="ratio ratio-16x9">
              <iframe id="pdfViewer" src="" loading="lazy"></iframe>
            </div>
          </div>
          {{-- <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <iframe id="pdfViewerlampiran1" src="" loading="lazy" width="770px" height="1000px"></iframe>
          </div> --}}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-md waves-effect rounded waves-light btnCancel" title="Batal" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary btn-md waves-effect rounded waves-light" data-bs-toggle="modal" data-bs-target="#exampleModalToggle2" id="saveButton">Save</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.11/dist/sweetalert2.all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
      var userHakAksesId = {{ auth()->user()->hak_akses_id }};
    </script>

    <script>
      const quill_rincian_pelaksanaan_penugasan = new Quill('#editor_rincian_pelaksanaan_penugasan', {
        theme: 'snow'
      });
      const quill_perihal_surat = new Quill('#editor_perihal_surat', {
        theme: 'snow'
      });
      const quill_beban_anggaran = new Quill('#editor_beban_anggaran', {
        theme: 'snow'
      });
    </script>

    <script>
      var myTable;
        $(document).ready(function() {

        $(document).on('change', ".select_tembusan", function () {
          // Dapatkan nilai select yang dipilih
          var selectedValue = $(this).val();

          // Temukan textarea terdekat dengan ID tembusan_surat
          var textarea = $(this).closest('.baru-data-2').find('.tembusan_surat');

          // Masukkan nilai select ke textarea
          textarea.val(selectedValue);
        });
//----------------------------------------------------------------
        var dipa = <?php echo json_encode($Dipa); ?>;

        $(document).on("change", "#flexRadioDefault1", function() {
            if ($(this).is(":checked")) {
                $("#beban_anggaran_id_dipa").prop("disabled", false);
                $("#beban_anggaran_id_mitra").prop("disabled", true);
                $("#beban_anggaran_id_dipa").val(null);
                $("#beban_anggaran_id_mitra").empty()
                loadDropdownOptionsDipa();
            }
        });

        function loadDropdownOptionsDipa() {
          var optionsHtml = dipa.map(function(option) {
              return `<option value="" disabled selected hidden>Pilih salah satu...</option>
              <option value="${option.id}">${option.nama_lembaga}</option>`;
          }).join('');
          $("#beban_anggaran_id_dipa").html(optionsHtml);
        }

//----------------------------------------------------------------

          var mitra = <?php echo json_encode($Mitra); ?>;

          $(document).on("change", "#flexRadioDefault2", function() {
            if ($(this).is(":checked")) {
              $("#beban_anggaran_id_mitra").prop("disabled", false);
              $("#beban_anggaran_id_dipa").prop("disabled", true);
              $("#beban_anggaran_id_mitra").val(null);
              $("#beban_anggaran_id_dipa").empty()
              loadDropdownOptionsMitra();
            }
          });

          function loadDropdownOptionsMitra() {
            var optionsHtml = mitra.map(function(option) {
                return `<option value="" disabled selected hidden>Pilih salah satu...</option>
                <option value="${option.id}">${option.nama_lembaga}</option>`;
            }).join('');
            $("#beban_anggaran_id_mitra").html(optionsHtml);
          }
//---------------------------------------------------- CSRF

          var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
            });

//---------------------------------------------------- Table

            myTable = $('#myTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route("surat.manajemen_surat.show") }}',
                columns: [
                    { data: 'nomor_surat', name: 'nomor_surat', width: '10%' },
                    { data: 'perihal_surat', name: 'perihal_surat', width: '50%' },
                    { data: 'pembuat_surat', name: 'pembuat_surat', width: '20%' },
                    { data: 'status', name: 'status', width: '10%' },
                    { data: 'e2', name: 'e2', width: '5%'},
                    { data: 'e3', name: 'e3', width: '5%'},
                    { data: 'e4', name: 'e4', width: '5%'},
                    { data: 'tahun', name: 'tahun', visible: false},
                    { data: 'action', name: 'action', width: '5%', searchable: false, orderable: false}
                ],
                columnDefs: [
                    { targets: [1, 2], className: 'wrap' } // Apply wrap class to all columns
                ],
                initComplete: function () {
                    // Setelah tabel selesai dimuat, tambahkan popovers ke tombol info
                    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');

                    popoverTriggerList.forEach(popoverTriggerEl => {
                        new bootstrap.Popover(popoverTriggerEl, {
                            container: 'body', // Optional, popovers akan ditambahkan ke body untuk menghindari masalah z-index
                        });
                    });
                }
            });

            $('#filter_tahun').on('change', function() {
                var tahun = $(this).val();
                // Filter kolom tahun
                myTable.column(7).search(tahun).draw();
            });

//---------------------------------------------------- Tambah

            // $('#newUser').click(function () {
            //   $('#Modal').modal('show');
            //   $('#labelModal').html("Tambah Data User");
            //   $('#saveButton').text('Save');
            //   $('#action').val('tambah');
            //   $('#id').val('');
            //   $('#nip').val('');
            //   $('#name').val('');
            //   $('#bidang').val('');
            //   $('#jabatan').val('');
            //   $('#hak_akses').val('');
            //   $("#name-error").html('');
            // });

            // $(document).on('click', '#previewPDf', function() {
            //   $('#ModalPdf').modal('show');
            // });
            $(document).on('click', '#riwayatSurat', function() {
              $('#ModalRiwayat').modal('show');
              $('#title-riwayat').text('Riwayat Surat');
            });

            var deletePDF = [];
            $(document).on('click', '.deletePDF', function() {
              var index = $(this).data('index');
              var linkToDelete = $(this).data('link');
              deletePDF.push(linkToDelete);
              $("#lampiran-tab-" + index).remove();
              $("#lampiran-tab-pane-" + index).remove();
              $(this).closest('.pdfedit').remove();
            });

//---------------------------------------------------- Submit

            $("#saveButton").on('click', function () {
              var form = document.getElementById('form_surat');

              var formData = new FormData(form);
              formData.append('deletePDF', JSON.stringify(deletePDF));

              Swal.fire({
                      title: 'Yakin ?',
                      html: '<p>Apakah anda yakin ingin Memperbarui Surat ?</p>',
                      showCancelButton: true,
                      confirmButtonText: 'Yakin',
                      icon: 'question',
                      cancelButtonColor: '#d61c0f',
                      cancelButtonText: 'Batal',
                      customClass: {
                          actions: 'my-actions',
                          cancelButton: 'order-1 right-gap',
                          confirmButton: 'order-2',
                          denyButton: 'order-3',
                      }
                  }).then((result) => {
                    if (result.isConfirmed) {
                      Swal.fire({
                        title: 'Loading',
                        html: 'Please Wait....',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                          swal.showLoading();
                        }
                      });

                      $.ajax({
                        url: '{{ route('surat.manajemen_surat.update') }}',
                        type: "post",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(response) {
                          Swal.fire({
                              title: 'Berhasil!',
                              text: "Surat berhasil Diperbarui",
                              icon: 'success',
                              timer: 3000,
                          });
                          $('#myTable').DataTable().draw();
                          $('#ModalEdit').modal("hide");
                          $("#ModalPDf").modal("hide");
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                      });
                    }
                  })

            });

//---------------------------------------------------- Edit

            var lengthIndex;
            $(document).on('click', '.btnEdit', function(e) {
              e.preventDefault();

              let id = $(this).data('id');
              let url = '{{ route('surat.manajemen_surat.detail', ':id') }}';
              url = url.replace(':id', id);
              $('#nomor_surat_input').val('');

              Swal.fire({
                  title: 'Loading',
                  html: 'Please Wait....',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      swal.showLoading();
                  }
              });

              setTimeout(function() {
                  $.ajax({
                      url: url,
                      type: "GET",
                      success: function(response) {
                        Swal.close();
                        console.log(response);
                        $('#id').val(id);
                        $('#nomor_surat_input').val(response.surat.nomor_surat);
                        if (response.surat.e3 == 1 && response.surat.e2 == 0 && userHakAksesId == 4) {
                            $('#nomor_surat_input').prop('disabled', false);
                        } else {
                            $('#nomor_surat_input').prop('disabled', true);
                        }

                        $('#tanggal_surat_input').val(response.surat.tanggal_surat);
                        $('#lampiran_surat_input').val(response.surat.keterangan_lampiran);
                        $('#editor_perihal_surat div').html('<p>' + response.surat.perihal_surat + '</p>');
                        $('#alamat_tujuan_input').val(response.surat['alamat_instansi/pejabat']);
                        $('#editor_rincian_pelaksanaan_penugasan div').html('<p>' + response.surat.rincian_pelaksanaan_penugasan + '</p>');
                        if (response.surat.beban_anggaran.jenis_lembaga == 1) {
                          $("#flexRadioDefault1").prop('checked', true);
                          $("#beban_anggaran_id_dipa").prop("disabled", false);
                          $("#beban_anggaran_id_mitra").prop("disabled", true);
                          $("#beban_anggaran_id_dipa").val(null);
                          $("#beban_anggaran_id_mitra").empty()
                          loadDropdownOptionsDipa();
                          $('#beban_anggaran_id_dipa option[value="' + response.surat.beban_anggaran.id + '"]').prop('selected',true);
                        }else if (response.surat.beban_anggaran.jenis_lembaga == 2) {
                          $("#flexRadioDefault2").prop('checked', true);
                          $("#beban_anggaran_id_mitra").prop("disabled", false);
                          $("#beban_anggaran_id_dipa").prop("disabled", true);
                          $("#beban_anggaran_id_mitra").val(null);
                          $("#beban_anggaran_id_dipa").empty()
                          loadDropdownOptionsMitra();
                          $('#beban_anggaran_id_mitra option[value="' + response.surat.beban_anggaran.id + '"]').prop('selected',true);
                        }
                        $('#jabatan_id_input option[value="' + response.surat.nama_pejabat.jabatan_id + '"]').prop('selected',true);
                        $('#nama_pejabat_input').val(response.surat.nama_pejabat.name);
                        $('#nip_input').val(response.surat.nama_pejabat.NIP);
                        $("#dynamic_form-4").empty();
                        $("#dynamic_form-4").html(`<div class="form-group baru-data-4" style="margin-bottom: -25px">
                            <div class="col-md-12">
                              <input class="form-control formFile" type="file" name="lampiran[]" id="formFile">
                            </div>
                            <div class="button-group d-flex justify-content-center mt-4 mb-3">
                                <button type="button" class="btn btn-success btn-tambah-4 mx-2">
                                  <span class="desktop-text">Tambah Lampiran Surat</span>
                                  <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-hapus-4" style="display:none;">
                                  <span class="desktop-text">Hapus</span>
                                  <span class="mobile-logo"><i class="fa fa-times"></i></span>
                                </button>
                            </div>
                          </div>`);

                        $("#dynamic_form-3").html(response.surat.tujuan_surat.map(function (tujuan, index, array) {
                          let isLastItem = index === array.length - 1;
                          return `<div class="form-group baru-data-3 mb-4"">\
                                    <div class="col-md-12">\
                                      ${index == 0 ? '<label for="tujuan_surat">Tujuan Surat</label>' : ''}\
                                        <textarea id="tujuan_surat" name="tujuan_surat[]" value="" placeholder="Tujuan Surat" class="form-control tujuan_surat" rows="3">${tujuan.tujuan_surat}</textarea>\
                                    </div>\
                                    <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                                        <button type="button" class="btn btn-success btn-tambah-3 mx-2  ${isLastItem ? 'd-block' : 'd-none'}">
                                          <span class="desktop-text">Tambah Isian Tujuan Surat</span>
                                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                                        </button>\
                                        <button type="button" class="btn btn-danger btn-hapus-3 ${isLastItem && index != 0 ? 'd-block' : 'd-none'}">
                                          <span class="desktop-text">Hapus</span>
                                          <span class="mobile-logo"><i class="fa fa-times"></i></span>
                                        </button>\
                                    </div>\
                                  </div>`;
                        }).join(''));

                        $("#dynamic_form").html(response.surat.dasar_acuan_surat.map(function (dasar, index, array) {
                          let isLastItem = index === array.length - 1;
                          return `<div class="form-group baru-data mb-4">\
                                    <div class="col-md-12">\
                                      ${index == 0 ? '<label for="tujuan_surat">Dasar Acuan Surat</label>' : ''}\
                                        <textarea id="tujuan_surat" name="dasar_acuan[]" value="" placeholder="Dasar Acuan Surat" class="form-control tujuan_surat" rows="3">${dasar.dasar_acuan_surat}</textarea>\
                                    </div>\
                                    <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                                        <button type="button" class="btn btn-success btn-tambah mx-2  ${isLastItem ? 'd-block' : 'd-none'}">
                                          <span class="desktop-text">Tambah Dasar Acuan Surat</span>
                                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                                        </button>\
                                        <button type="button" class="btn btn-danger btn-hapus ${isLastItem && index != 0 ? 'd-block' : 'd-none'}">
                                          <span class="desktop-text">Hapus</span>
                                          <span class="mobile-logo"><i class="fa fa-times"></i></span>
                                        </button>\
                                    </div>\
                                  </div>`;
                        }).join(''));

                        $("#myTab").html(`<li class="nav-item" role="presentation">
                            <button class="nav-link active" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-tab-pane" type="button" role="tab" aria-controls="preview-tab-pane" aria-selected="true">Preview</button>
                        </li>`);

                        $("#myTabContent").html(`<div class="tab-pane fade show active" id="preview-tab-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
                            <div class="ratio ratio-16x9">
                                <iframe id="pdfViewer" src="" loading="lazy" width="770px" height="1000px"></iframe>
                            </div>
                        </div>`);
                        lengthIndex = response.surat.lampiran.length;
                        response.surat.lampiran.forEach(function (lampiran, index) {
                            $("#myTab").append(`<li class="nav-item" role="presentation">
                                <button class="nav-link" id="lampiran-tab-${index}" data-bs-toggle="tab" data-bs-target="#lampiran-tab-pane-${index}" type="button" role="tab" aria-controls="lampiran-tab-pane-${index}" aria-selected="false">Lampiran ${index + 1}</button>
                            </li>`);
                            $("#myTabContent").append(`<div class="tab-pane fade" id="lampiran-tab-pane-${index}" role="tabpanel" aria-labelledby="lampiran-tab-${index}" tabindex="0">
                                <div class="ratio ratio-16x9">
                                    <iframe id="pdfViewer${index}" src="${'/' + lampiran.lampiran}" loading="lazy" width="770px" height="1000px"></iframe>
                                </div>
                            </div>`);
                        });


                        $("#editPDF").html(response.surat.lampiran.map(function (lampiran, index, array) {
                          var fileName = lampiran.lampiran;
                          var underscoreIndex = fileName.indexOf('_');
                          var cleanFileName = fileName.substring(underscoreIndex + 1);
                          return `<div class="d-flex mb-3 pdfedit">
                                    <div class="d-flex align-items-center" style="margin-left:auto;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#B30B00" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                                      </svg>
                                    </div>
                                    <div class="bg-red rounded-pill w-100 d-flex align-items-center" style="padding-left:20px; margin-left:10px;" id="namePDF">
                                      <small class="text-white"><b>${cleanFileName}</b></small>
                                    </div>
                                    <div class="d-flex align-items-center" style="margin-left:auto;">
                                      <a href="javascript:void(0)" data-index="${index}" data-link="${lampiran.encrypted_id}" class="deletePDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#B30B00" class="bi bi-trash" viewBox="0 0 16 16">
                                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                          <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                      </a>
                                    </div>
                                  </div>`;
                        }).join(''));

                        if (response.surat.tembusan_surat.length > 0) {
                          $("#dynamic_form-2").html(response.surat.tembusan_surat.map(function (tembusan, index, array) {
                            let isLastItem = index === array.length - 1;
                            return `<div class="form-group baru-data-2 mb-4">\
                                      <div class="col-md-12 d-flex">\
                                          <textarea id="tembusan_surat" name="tembusan_surat[]" value="" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="1">${tembusan.tembusan_surat}</textarea>\
                                      </div>\
                                      <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                                          <button type="button" class="btn btn-success btn-tambah-2 mx-2  ${isLastItem ? 'd-block' : 'd-none'}">
                                            <span class="desktop-text">Tambah Tembusan Surat</span>
                                            <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                                          </button>\
                                          <button type="button" class="btn btn-danger btn-hapus-2 ${isLastItem && index != 0 ? 'd-block' : 'd-none'}">
                                            <span class="desktop-text">Hapus</span>
                                            <span class="mobile-logo"><i class="fa fa-times"></i></span>
                                          </button>\
                                      </div>\
                                    </div>`;
                          }).join(''));
                        }
                        else {
                          $("#dynamic_form-2").html(`<div class="form-group baru-data-2 mb-4">\
                            <div class="col-md-12">\
                                <label for="tujuan_surat">Tembusan Surat</label>\
                                <textarea id="tembusan_surat" name="tembusan_surat[]" value="" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="3"></textarea>\
                            </div>\
                            <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                                <button type="button" class="btn btn-success btn-tambah-2 mx-2 d-block">
                                  <span class="desktop-text">Tambah Tembusan Surat</span>
                                  <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                                </button>\
                                <button type="button" class="btn btn-danger btn-hapus-2 d-none">
                                  <span class="desktop-text">Hapus</span>
                                  <span class="mobile-logo"><i class="fa fa-times"></i></span>
                                </button>\
                            </div>\
                          </div>`);
                        }

                        $('#action').val('edit');
                        $('#ModalEdit').modal("show");
                        $('#saveButton').text('Update');
                        $('.modal-title').text('Edit Surat');

                        $("#show_preview").removeAttr("data-link0 data-link1 data-link2 data-link3 data-link4");
                      },
                      error: function(error) {
                          Swal.fire({
                              title: 'Error',
                              text: 'An error occurred',
                              icon: 'error',
                              confirmButtonText: 'OK'
                          });
                      }
                    });
                }, 800);
            });

//---------------------------------------------------- lampiran surat

          function addFormsss() {
          var addrow = '<div class="form-group baru-data-4" style="margin-bottom: -25px">\
              <div class="col-md-12">\
                <input class="form-control formFile" type="file" name="lampiran[]" id="formFile">\
              </div>\
              <div class="button-group d-flex justify-content-center mt-4 mb-3">\
                  <button type="button" class="btn btn-success btn-tambah-4 mx-2">\
                    <span class="desktop-text">Tambah Lampiran Surat</span>\
                    <span class="mobile-logo"><i class="fa fa-plus"></i></span>\
                  </button>\
                  <button type="button" class="btn btn-danger btn-hapus-4">\
                    <span class="desktop-text">Hapus</span>\
                    <span class="mobile-logo"><i class="fa fa-times"></i></span>\
                  </button>\
              </div>\
          </div>';
          $("#dynamic_form-4").append(addrow);
        }

        $("#dynamic_form-4").on("click", ".btn-tambah-4", function () {
            addFormsss();
            $(this).css("display", "none");
            $(".btn-hapus-4").css("display", "none");
            $(".baru-data-4:last .btn-hapus-4").show();
        });

        $("#dynamic_form-4").on("click", ".btn-hapus-4", function () {
          $(this).closest('.baru-data-4').remove();
            $(".baru-data-4:last .btn-tambah-4").show();
            var bykrow = $(".baru-data-4").length;
            if (bykrow == 1) {
                $(".btn-hapus-4").css("display", "none");
            } else {
                $('.baru-data-4:last .btn-hapus-4').css("display", "");
            }
        });

//---------------------------------------------------- tujuan surat

            function addFormss() {
                var addrow = '<div class="form-group baru-data-3 mb-4">\
                    <div class="col-md-12">\
                        <textarea name="tujuan_surat[]" placeholder="Tujuan Surat" class="form-control tujuan_surat" rows="3"></textarea>\
                    </div>\
                    <div class="button-group d-flex justify-content-center mt-3 mb-3">\
                        <button type="button" class="btn btn-success btn-tambah-3 mx-2">\
                          <span class="desktop-text">Tambah Isian Tujuan Surat</span>\
                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>\
                        </button>\
                        <button type="button" class="btn btn-danger btn-hapus-3">\
                          <span class="desktop-text">Hapus</span>\
                          <span class="mobile-logo"><i class="fa fa-times"></i></span>\
                        </button>\
                    </div>\
                </div>';
                $("#dynamic_form-3").append(addrow);
            }

            $("#dynamic_form-3").on("click", ".btn-tambah-3", function () {
                addFormss();
                $(this).hide();
                $(this).parent().nextAll(".btn-hapus-3").first().hide();
                $(this).parent().addClass('d-none');
            });

            $("#dynamic_form-3").on("click", ".btn-hapus-3", function () {
              $(this).closest('.baru-data-3').remove();
              $(".baru-data-3:last .btn-tambah-3").parent().removeClass('d-none');
              $(".baru-data-3:last .btn-tambah-3").css('display', '')
              $(".baru-data-3:last .btn-tambah-3").removeClass('d-none')
              if ($('.baru-data-3').length != 1) {
                $(".baru-data-3:last .btn-hapus-3").removeClass('d-none')
              }
            });

//---------------------------------------------------- dasar acuan surat

            function addForm() {
                var addrow = '<div class="form-group baru-data mb-4">\
                    <div class="col-md-12">\
                        <textarea name="dasar_acuan[]"" placeholder="Dasar Acuan Penugasan" class="form-control dasar_acuan" rows="3"></textarea>\
                    </div>\
                    <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                        <button type="button" class="btn btn-success btn-tambah mx-2">\
                          <span class="desktop-text">Tambah Dasar Acuan Surat</span>\
                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>\
                        </button>\
                        <button type="button" class="btn btn-danger btn-hapus">\
                          <span class="desktop-text">Hapus</span>\
                          <span class="mobile-logo"><i class="fa fa-times"></i></span>\
                        </button>\
                    </div>\
                </div>';
                $("#dynamic_form").append(addrow);
            }

            $("#dynamic_form").on("click", ".btn-tambah", function () {
                addForm();
                $(this).hide();
                $(this).parent().nextAll(".btn-hapus").first().hide();
                $(this).parent().addClass('d-none');
            });

            $("#dynamic_form").on("click", ".btn-hapus", function () {
              $(this).closest('.baru-data').remove();
              $(".baru-data:last .btn-tambah").parent().removeClass('d-none');
              $(".baru-data:last .btn-tambah").css('display', '')
              $(".baru-data:last .btn-tambah").removeClass('d-none')
              if ($('.baru-data').length != 1) {
                $(".baru-data:last .btn-hapus").removeClass('d-none')
              }
            });

//---------------------------------------------------- dasar acuan surat
          function addForms() {
              var addrow = '<div class="form-group baru-data-2 mb-4">\
                  <div class="col-md-12 d-flex">\
                      <textarea name="tembusan_surat[]" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="1"></textarea>\
                  </div>\
                  <div class="button-group d-flex justify-content-center mt-2 mb-3">\
                      <button type="button" class="btn btn-success btn-tambah-2 mx-2">\
                        <span class="desktop-text">Tambah Tembusan Surat</span>\
                        <span class="mobile-logo"><i class="fa fa-plus"></i></span>\
                      </button>\
                      <button type="button" class="btn btn-danger btn-hapus-2">\
                        <span class="desktop-text">Hapus</span>\
                        <span class="mobile-logo"><i class="fa fa-times"></i></span>\
                      </button>\
                  </div>\
              </div>';
              $("#dynamic_form-2").append(addrow);
          }

          $("#dynamic_form-2").on("click", ".btn-tambah-2", function () {
              addForms();
                $(this).hide();
                $(this).parent().nextAll(".btn-hapus-2").first().hide();
                $(this).parent().addClass('d-none');
            });

            $("#dynamic_form-2").on("click", ".btn-hapus-2", function () {
              $(this).closest('.baru-data-2').remove();
              $(".baru-data-2:last .btn-tambah-2").parent().removeClass('d-none');
              $(".baru-data-2:last .btn-tambah-2").css('display', '')
              $(".baru-data-2:last .btn-tambah-2").removeClass('d-none')
              if ($('.baru-data-2').length != 1) {
                $(".baru-data-2:last .btn-hapus-2").removeClass('d-none')
              }
            });

//---------------------------------------------------- jabatan_id

            $('#jabatan_id_input').on('change', function () {
              var idjabatan = this.value;
              $("#nama_pejabat").html('');
              $("#nip_input").html('');
              $.ajax({
                  url: "{{route('surat.buat_surat.api-jabatan')}}",
                  type: "POST",
                  data: {
                      jabatan_id: idjabatan,
                      _token: '{{csrf_token()}}'
                  },
                  dataType: 'json',
                  success: function (response) {
                    if (response.status == true) {
                      $("#nama_pejabat_input").val(response.data[0].name);
                      $("#nip_input").val(response.data[0].nip);
                    }else {
                      $("#nama_pejabat_input").val('');
                      $("#nip_input").val('');
                    }
                  }
              });
            });

//---------------------------------------------------- search nip

            $("#search_nip").click(function () {
              var nip = $("#nip_input").val(); ;
              $("#nama_pejabat_input").val('');
              $('#jabatan_id_input').val('');
              Swal.fire({
                  title: 'Loading',
                  html: 'Please Wait....',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      swal.showLoading();
                  }
              });
              $.ajax({
                  url: "{{route('surat.buat_surat.api-nip')}}",
                  type: "POST",
                  data: {
                      nip: nip,
                      _token: '{{csrf_token()}}'
                  },
                  dataType: 'json',
                  success: function (response) {
                    Swal.close();
                    if (response.status == true) {
                      $("#nama_pejabat_input").val(response.data.name);
                      $('#jabatan_id_input option[value="' + response.data.jabatan_id + '"]').prop('selected',true);
                    }else {
                      $("#nama_pejabat_input").val('');
                      $('#jabatan_id_input').val('');
                    }
                  }
              });
            });
//---------------------------------------------------- Delete

            $(document).on('click', '.btnDelete', function (e) {
              e.preventDefault();
              var id = $(this).data("id");
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Apakah anda yakin ingin menghapus Surat ?</p>',
                  showCancelButton: true,
                  confirmButtonText: 'Hapus',
                  icon: 'question',
                  cancelButtonColor: '#d61c0f',
                  cancelButtonText: 'Batal',
                  customClass: {
                      actions: 'my-actions',
                      cancelButton: 'order-1 right-gap',
                      confirmButton: 'order-2',
                      denyButton: 'order-3',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                      let url = '{{ route('surat.manajemen_surat.delete', ':id') }}';
                      url = url.replace(':id', id);

                      Swal.fire({
                        title: 'Loading',
                        html: 'Please Wait....',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                          swal.showLoading();
                        }
                      });

                      setTimeout(function () {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function (res) {
                                if(res.status == true) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: res.pesan,
                                        icon: 'success',
                                        confirmButtonText: 'Ok'
                                    });
                                    $('#myTable').DataTable().ajax.reload();
                                } else if(res.status == false) {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: res.pesan,
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    });
                                }else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        html: res.error,
                                        icon: 'error',
                                        confirmButtonText: 'Redo'
                                    })
                                }
                            }
                        })
                      },800);
                  }
              })
            });
//---------------------------------------------------- tombol batal
            $(document).on('click', '.btnCancel', function (e) {
                $('#Modal').modal("hide");
            })

            $(document).on('change', '.e4', function (e) {
              var id = $(this).data('id');
              var status = $(this).data('status');
              $(this).prop('checked', status);
              var confirmMessage = status == 1 ? "Membatalkan Persetujuan" : "Menyetujui";
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Apakah anda yakin ingin <b>' + confirmMessage + '</b> surat ini</p>',
                  showCancelButton: true,
                  confirmButtonText: 'Yakin',
                  icon: 'question',
                  cancelButtonColor: '#d61c0f',
                  confirmButtonColor: '#198754',
                  cancelButtonText: 'Batal',
                  customClass: {
                      actions: 'my-actions',
                      cancelButton: 'order-1 right-gap',
                      confirmButton: 'order-2',
                      denyButton: 'order-3',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Loading',
                      html: 'Please Wait....',
                      allowOutsideClick: false,
                      showConfirmButton: false,
                      willOpen: () => {
                        swal.showLoading();
                      }
                    })

                    let url = '{{ route('surat.manajemen_surat.change_e4', ':id') }}';
                    url = url.replace(':id', id);
                    setTimeout(function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function (res) {
                        swal.close();
                          if (res.status == true) {
                            $(this).prop('checked', true);
                            Swal.fire({
                              title: 'Berhasil!',
                              text: res.pesan,
                              icon: 'success',
                              confirmButtonText: 'Ok'
                            });
                            $('#myTable').DataTable().ajax.reload();
                          }else{
                            Swal.fire({
                              title: 'Gagal!',
                              text: res.pesan,
                              icon: 'error',
                              confirmButtonText: 'Ok'
                            });
                          }
                        },
                        error: function (err) {
                          swal.close();
                        }
                    });
                  }, 800);
                  }
              });
            });

            $(document).on('change', '.e3', function (e) {
              var id = $(this).data('id');
              var status = $(this).data('status');
              $(this).prop('checked', status);
              var confirmMessage = status == 1 ? "Membatalkan Persetujuan" : "Menyetujui";
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Apakah anda yakin ingin <b>' + confirmMessage + '</b> surat ini</p>',
                  showCancelButton: true,
                  confirmButtonText: 'Yakin',
                  icon: 'question',
                  cancelButtonColor: '#d61c0f',
                  confirmButtonColor: '#198754',
                  cancelButtonText: 'Batal',
                  customClass: {
                      actions: 'my-actions',
                      cancelButton: 'order-1 right-gap',
                      confirmButton: 'order-2',
                      denyButton: 'order-3',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Loading',
                      html: 'Please Wait....',
                      allowOutsideClick: false,
                      showConfirmButton: false,
                      willOpen: () => {
                        swal.showLoading();
                      }
                    })

                    let url = '{{ route('surat.manajemen_surat.change_e3', ':id') }}';
                    url = url.replace(':id', id);
                    setTimeout(function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function (res) {
                        swal.close();
                          if (res.status == true) {
                            $(this).prop('checked', true);
                            Swal.fire({
                              title: 'Berhasil!',
                              text: res.pesan,
                              icon: 'success',
                              confirmButtonText: 'Ok'
                            });
                            $('#myTable').DataTable().ajax.reload();
                          }else{
                            Swal.fire({
                              title: 'Gagal!',
                              text: res.pesan,
                              icon: 'error',
                              confirmButtonText: 'Ok'
                            });
                          }
                        },
                        error: function (err) {
                          swal.close();
                        }
                    });
                  }, 800);
                  }
              });
            });

            $(document).on('click', '.e2', function (e) {
              var id = $(this).data('id');
              var status = $(this).data('status');
              $(this).prop('checked', status);
              var confirmMessage = status == 1 ? "Membatalkan Persetujuan" : "Menyetujui";
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Apakah anda yakin ingin <b>' + confirmMessage + '</b> surat ini</p>',
                  showCancelButton: true,
                  confirmButtonText: 'Yakin',
                  icon: 'question',
                  cancelButtonColor: '#d61c0f',
                  confirmButtonColor: '#198754',
                  cancelButtonText: 'Batal',
                  customClass: {
                      actions: 'my-actions',
                      cancelButton: 'order-1 right-gap',
                      confirmButton: 'order-2',
                      denyButton: 'order-3',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Loading',
                      html: 'Please Wait....',
                      allowOutsideClick: false,
                      showConfirmButton: false,
                      willOpen: () => {
                        swal.showLoading();
                      }
                    })

                    let url = '{{ route('surat.manajemen_surat.change_e2', ':id') }}';
                    url = url.replace(':id', id);
                    setTimeout(function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function (res) {
                        swal.close();
                          if (res.status == true) {
                            Swal.fire({
                              title: 'Berhasil!',
                              text: res.pesan,
                              icon: 'success',
                              confirmButtonText: 'Ok'
                            });
                            $('#myTable').DataTable().ajax.reload();
                          }else{
                            Swal.fire({
                              title: 'Gagal!',
                              text: res.pesan,
                              icon: 'error',
                              confirmButtonText: 'Ok'
                            });
                          }
                        },
                        error: function (err) {
                          swal.close();
                        }
                    });
                  }, 800);
                  }
              });
            });


            $(document).on('click', '.btnArsip', function (e) {
              var id = $(this).data('id');
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Arsipkan surat ini ?</p>',
                  showCancelButton: true,
                  confirmButtonText: 'Yakin',
                  icon: 'question',
                  cancelButtonColor: '#d61c0f',
                  confirmButtonColor: '#198754',
                  cancelButtonText: 'Batal',
                  customClass: {
                      actions: 'my-actions',
                      cancelButton: 'order-1 right-gap',
                      confirmButton: 'order-2',
                      denyButton: 'order-3',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Loading',
                      html: 'Please Wait....',
                      allowOutsideClick: false,
                      showConfirmButton: false,
                      willOpen: () => {
                        swal.showLoading();
                      }
                    })

                    let url = '{{ route('surat.manajemen_surat.arsip', ':id') }}';
                    url = url.replace(':id', id);
                    setTimeout(function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function (res) {
                        swal.close();
                          if (res.status == true) {
                            Swal.fire({
                              title: 'Berhasil!',
                              text: res.pesan,
                              icon: 'success',
                              confirmButtonText: 'Ok'
                            });
                            $('#myTable').DataTable().ajax.reload();
                          }else{
                            Swal.fire({
                              title: 'Gagal!',
                              text: res.pesan,
                              icon: 'error',
                              confirmButtonText: 'Ok'
                            });
                          }
                        },
                        error: function (err) {
                          swal.close();
                        }
                    });
                  }, 800);
                  }
              });
            });

            $(document).on('click', '.btnInfo', function(e) {
              e.preventDefault();

              let id = $(this).data('id');
              let url = '{{ route('surat.manajemen_surat.detail', ':id') }}';
              url = url.replace(':id', id);

              Swal.fire({
                  title: 'Loading',
                  html: 'Please Wait....',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      swal.showLoading();
                  }
              });

              setTimeout(function() {
                  $.ajax({
                      url: url,
                      type: "GET",
                      success: function(response) {
                          Swal.close();
                          var nomor_surat = response.surat.nomor_surat ? response.surat.nomor_surat : "-";
                          $("#nomor_surat").text(nomor_surat);
                          $("#tanggal_surat").text(response.surat.tanggal_surat);
                          $("#perihal_surat").html(response.surat.perihal_surat);
                          $("#tujuan_surat").html(response.surat.tujuan_surat.map(function (tujuan, index, array) {
                            var marginBottomStyle = index === array.length - 1 ? '' : 'margin-bottom: -2px;';
                            return `<p style="${marginBottomStyle}">${index + 1}.${tujuan.tujuan_surat}</p>`;
                          }).join(''));
                          $("#alamat_tujuan").text(response.surat['alamat_instansi/pejabat']);

                          $("#dasar_acuan_penugasan").html(response.surat.dasar_acuan_surat.map(function (acuan, index, array){
                            var marginBottomStyle = index === array.length - 1 ? '' : 'margin-bottom: -10px;';
                            return `<div class="d-flex" style="${marginBottomStyle} text-align: justify;">
                              <p>
                                ${index + 1}.
                                <p>
                                  ${acuan.dasar_acuan_surat}
                                </p>
                              </p>
                            </div>`;
                          }).join(''));
                          $("#pelaksanaan").html(response.surat.rincian_pelaksanaan_penugasan);

                          $("#nama").text(response.surat.nama_pejabat.name);
                          $("#jabatan").text(response.jabatan.jabatan.name);
                          $("#nip").text(response.surat.nama_pejabat.NIP);

                          $("#tembusan_surat").html(response.surat.tembusan_surat.map(function (tembusan, index, array){
                            var marginBottomStyle = index === array.length - 1 ? '' : 'margin-bottom: -10px;';
                            return `<div class="d-flex" style="${marginBottomStyle} text-align: justify;">
                              <p>
                                ${index + 1}.
                                <p>
                                  ${tembusan.tembusan_surat}
                                </p>
                              </p>
                            </div>`;
                          }).join(''));

                          $("#myTab").html(`<li class="nav-item" role="presentation">
                              <button class="nav-link active" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-tab-pane" type="button" role="tab" aria-controls="preview-tab-pane" aria-selected="true">Preview</button>
                          </li>`);

                          $("#myTabContent").html(`<div class="tab-pane fade show active" id="preview-tab-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
                              <div class="ratio ratio-16x9">
                                  <iframe id="pdfViewer" src="${'/' + response.surat.pdf}" loading="lazy" width="770px" height="1000px"></iframe>
                              </div>
                          </div>`);

                          response.surat.lampiran.forEach(function (lampiran, index) {
                              $("#myTab").append(`<li class="nav-item" role="presentation">
                                  <button class="nav-link" id="lampiran-tab-${index}" data-bs-toggle="tab" data-bs-target="#lampiran-tab-pane-${index}" type="button" role="tab" aria-controls="lampiran-tab-pane-${index}" aria-selected="false">Lampiran ${index + 1}</button>
                              </li>`);
                              $("#myTabContent").append(`<div class="tab-pane fade" id="lampiran-tab-pane-${index}" role="tabpanel" aria-labelledby="lampiran-tab-${index}" tabindex="0">
                                  <div class="ratio ratio-16x9">
                                      <iframe id="pdfViewer${index}" src="${'/' + lampiran.lampiran}" loading="lazy" width="770px" height="1000px"></iframe>
                                  </div>
                              </div>`);
                          });

                          $('.timeline').empty();
                          response.surat.riwayat_surat.forEach(function (riwayat_surat, index) {
                          var tanggalString = riwayat_surat.created_at;
                          var tanggal = new Date(tanggalString);

                          var namaHari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                          var hari = namaHari[tanggal.getDay()];

                          var tanggalIndonesia = tanggal.getDate();
                          var bulanIndonesia = tanggal.getMonth() + 1; // Penambahan 1 karena bulan dimulai dari 0
                          var tahunIndonesia = tanggal.getFullYear();

                          var jam = ("0" + tanggal.getHours()).slice(-2);
                          var menit = ("0" + tanggal.getMinutes()).slice(-2);
                          var detik = ("0" + tanggal.getSeconds()).slice(-2);

                          var tanggalFormat = ("0" + tanggalIndonesia).slice(-2);
                          var bulanFormat = ("0" + bulanIndonesia).slice(-2);
                          var tahunFormat = tahunIndonesia;

                          var hasilAkhir = hari + ", " + tanggalFormat + "-" + bulanFormat + "-" + tahunFormat + " " + jam + ":" + menit + ":" + detik;

                          $(".timeline").append(`
                              <li class="timeline-item ${index % 2 == 1 ? 'left' : 'right'}">
                                <div class="timeline-body">
                                  <div class="timeline-meta">
                                    <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                                      <span>${hasilAkhir}</span>
                                    </div>
                                  </div>
                                  <div class="timeline-content timeline-indicator">
                                    <div class="card border-0 shadow">
                                      <div class="card-body px-2">
                                        <h6 class="card-title mb-2">${riwayat_surat.riwayat}</h6>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </li>
                              `);
                          });

                          $('#ModalDetail').modal("show");
                          $('.btnCancel').text('Close');
                          $('#labelModal').text('Detail Surat');
                      },
                      error: function(error) {
                          Swal.fire({
                              title: 'Error',
                              text: 'An error occurred',
                              icon: 'error',
                              confirmButtonText: 'OK'
                          });
                      }
                  });
              }, 800);
            });

            $(document).on('click', '.btnDownload', function(e) {
              e.preventDefault();

              let id = $(this).data('id');
              let url = '{{ route('surat.manajemen_surat.download', ':id') }}';
              url = url.replace(':id', id);

              Swal.fire({
                  title: 'Loading',
                  html: 'Please Wait....',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      swal.showLoading();
                  }
              });

              setTimeout(function() {
                  $.ajax({
                      url: url,
                      type: "GET",
                      success: function(response) {
                        window.open(url, '_blank');
                        
                        Swal.close();
                      },
                      error: function(error) {
                        console.log(error);
                          Swal.fire({
                              title: 'Error',
                              text: 'An error occurred',
                              icon: 'error',
                              confirmButtonText: 'OK'
                          });
                      }
                  });
              }, 800);
            });

            $(document).on('click', '#openPDF', function () {
              var linkpdf = $(this).data('link');

              $("#ModalPDf").modal('show');
              $(".btnCancel").text('Close');
              $("#saveButton").css('display', 'none');
              $('#titlePDF').text('Preview PDF');
            });

            $("#show_layout").click(function () {
              $("#ModalPDf").modal("show");

              $("#myTab").empty();
              $("#myTabContent").empty();

              $("#myTab").append(`<li class="nav-item" role="presentation">
                  <button class="nav-link active" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-tab-pane" type="button" role="tab" aria-controls="preview-tab-pane" aria-selected="true">Format Surat</button>
              </li>`);

              $("#myTabContent").append(`<div class="tab-pane fade show active" id="preview-tab-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
                <div class="ratio ratio-16x9">
                  <iframe id="pdfViewer" src="" loading="lazy" width="770px" height="1000px"></iframe>
                </div>
              </div>`);

              $("#pdfViewer").attr("src", "{{ asset('pdf/Format_Surat.pdf')}}");
              $("#titlePDF").html('Contoh Format Surat');
              $("#saveButton").css("display", "none");
              $(".btnCancel").css("display", "none");
            });

          $("#show_preview").click( function () {

            var editorContentPerihal = $('.perihal_surat p').html();
            var perihal_surat = $("#perihal_surat_input").val(editorContentPerihal);

            var editorContentPenugasan = $('.rincian_pelaksanaan_penugasan p').html();
            $("#rincian_pelaksanaan_penugasan_input").val(editorContentPenugasan);

            var editorContentAnggaran = $('.beban_anggaran p').html();
            $("#beban_anggaran_input").val(editorContentAnggaran);


            var tanggal_surat = $("#tanggal_surat_input").val();
            var lampiran_surat = $("#lampiran_surat_input").val();
            var perihal_surat = $("#perihal_surat_input").val();
            var tujuan_surat = $(".tujuan_surat").val();
            var alamat_tujuan = $("#alamat_tujuan_input").val();
            var dasar_acuan = $("#dasar_acuan").val();
            var rincian_pelaksanaan_penugasan = $("#rincian_pelaksanaan_penugasan_input").val();
            var beban_anggaran = $("#beban_anggaran_input").val();
            var jabatan_id = $("#jabatan_id_input").val();
            var nama_pejabat = $("#nama_pejabat_input").val();
            var nip = $("#nip_input").val();
            var tembusan_surat = $("#tembusan_surat").val();

            $('input[type="text"]:not([disabled]), input[type="date"]:not([disabled]), textarea:not([disabled]):not(#tembusan_surat):not(#select_tembusan), select:not([disabled]):not(#select_tembusan), input[type="radio"]:not([disabled])').each(function() {
              if (!$(this).is(':disabled')) {
                  if ($(this).is(':radio')) {
                      var radioName = $(this).attr('name');
                      if ($('input[name="' + radioName + '"]:checked').length === 0) {
                          $(this).addClass("is-invalid");
                          $(this).removeClass("is-valid");
                      } else {
                          $(this).removeClass("is-invalid");
                          $(this).addClass("is-valid");
                      }
                  } else {
                      if ($(this).val() === "" || $(this).val() === null) {
                          $(this).addClass("is-invalid");
                          $(this).removeClass("is-valid");
                      } else {
                          $(this).removeClass("is-invalid");
                          $(this).addClass("is-valid");
                      }
                  }
              }
          });

            if (
                tanggal_surat === "" ||
                lampiran_surat === "" ||
                perihal_surat === "" ||
                tujuan_surat === "" ||
                alamat_tujuan === "" ||
                dasar_acuan === "" ||
                rincian_pelaksanaan_penugasan === "" ||
                beban_anggaran === "" ||
                jabatan_id === "" ||
                nama_pejabat === "" ||
                nip === ""
            ) {
              Swal.fire({
                  title: 'error',
                  icon: 'error',
                  html: 'Lengkapi Semua Field Terlebih Dahulu',
                  allowOutsideClick: false,
                  showConfirmButton: true,
              });
            } else {

              $("#titlePDF").html('Preview Surat');
              $("#ModalPDf").modal("show");
              $("#pdfViewer").attr("src", "");
              $("#saveButton").css("display", "block");
              $(".btnCancel").css("display", "block");

              var form = document.getElementById('form_surat');

              Swal.fire({
                  title: 'Loading',
                  html: 'Please Wait....',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  willOpen: () => {
                      swal.showLoading();
                  },
              });
              $.ajax({
                url: '{{ route('surat.buat_surat.pdfview') }}',
                type: "post",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                  $("#pdfViewer").attr("src", '{{ route('surat.buat_surat.pdfview') }}?' + new URLSearchParams(new FormData(form)).toString());
                  Swal.close();
                  var linkpdf = $(".formFile").val();

                  if (linkpdf.length > 0) {
                    $.ajax({
                        url: '{{ route('surat.buat_surat.pdf') }}',
                        type: "post",
                        data: new FormData(form),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (response) {

                          var urutan = lengthIndex;
                          var urutans = lengthIndex;

                          $("#myTab .nav-link.new-tab").closest('.nav-item').remove();
                          $("#myTabContent .tab-pane.new-tab").remove();

                          $("#myTab").append(response.map(function (res, index) {
                              var currentIndex = urutan++;
                              return `<li class="nav-item" role="presentation">
                                          <button class="nav-link new-tab" id="lampiran-tab-${currentIndex}" data-bs-toggle="tab" data-bs-target="#lampiran-tab-pane-${currentIndex}" type="button" role="tab" aria-controls="lampiran-tab-pane-${currentIndex}" aria-selected="false">Lampiran ${currentIndex + 1}</button>
                                      </li>`;
                          }).join(''));
                          $("#myTabContent").append(response.map(function (res, index) {
                              var currentIndex = urutans++;
                              return `<div class="tab-pane new-tab fade" id="lampiran-tab-pane-${currentIndex}" role="tabpanel" aria-labelledby="lampiran-tab-${currentIndex}" tabindex="0">
                                        <div class="ratio ratio-16x9">
                                          <iframe id="pdfViewer${currentIndex + 1}" src="data:application/pdf;base64,${res}" loading="lazy" width="800px" height="1000px"></iframe>
                                        </div>
                                      </div>`;
                          }).join('')).find('iframe').removeAttr('style');
                        },
                        error: function (error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                  }
                },
                error: function(error) {
                  $("#ModalPDf").modal("hide");
                    Swal.fire({
                        title: 'Error',
                        text: error.responseJSON.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
              });
            }
          });
        });
    </script>
@endsection
