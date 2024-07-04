@extends('layout.main')

@section('title', 'Buat Surat')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


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
      /* .form-control, .form-select {
        border: var(--bs-border-width) solid #8693a1 !important;
      } */
      .content .navbar .sidebar-toggler, .content .navbar .navbar-nav .nav-link i {
        margin-right: 1.5rem;
      }
      .stepper {
          .line {
              width: 2px;
              background-color: lightgrey !important;
          }
          .lead {
              font-size: 1.1rem;
          }
        }

        input:disabled {
            background-color: #c6c6c6 !important;
            /* Warna abu-abu */
            color: #ffffff;
            /* Warna teks yang sesuai */
            cursor: not-allowed;
            /* Ganti kursor menjadi "not-allowed" */
        }

        hr:not([size]) {
            color: white;
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

        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1030px;
            }
        }

        @media (min-width: 992px) {

            .modal-lg,
            .modal-xl {
                max-width: 830px !important;
            }
        }

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

        .ratio-16x9 {
            --bs-aspect-ratio: 100%;
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

        .ql-container {
            height: 80%;
        }
    </style>
@endsection

@section('navtop')
    <a href="{{ route('surat.manajemen_surat.show') }}" class="button raise">
        <span class="desktop-text">{{ auth()->user()->hak_akses_id == 1 ? 'Manajemen Surat' : 'Disposisi Surat' }}</span>
        <span class="mobile-logo"><i class="bi bi-kanban-fill"></i></span>
    </a>
    <a href="{{ route('surat.buat_surat.show') }}"
        class="mx-3 button raise {{ Request::is('*buat_surat') ? 'active' : '' }}">
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
    <div class="bg-white text-center rounded p-4 shadow-lg">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="mb-0">Pembuatan Surat Penugasan</h6>
      </div>
      <div class="stepper d-flex flex-column mt-5 ml-2">
        <div class="d-flex mb-1">
          <div class="container">
            {{-- <h5 class="">Formulir Surat</h5> --}}
{{-- -------------------------------------------------------------------------------identitas Surat-----------------------------------------------}}
        <form action="javascript:void(0)" id="form_surat" enctype="multipart/form-data">
              <div class="border-3 border p-3 rounded mb-5">
              <h4 class="mb-3">*Identitas Surat</h4>
              <div class="row mb-3">
                  <div class="col-md-6">
                    <span tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="Nomor Surat Akan Di Isi Sekretaris">
                      <label for="nomor_surat">Nomor Surat</label>
                      <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" placeholder="Nomor Surat" disabled>
                    </span>
                  </div>
                  <div class="col-md-6">
                      <label for="tanggal_surat">Tanggal Surat</label>
                      <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" placeholder="Tanggal Surat">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-12">
                      <label for="lampiran_surat">Lampiran Surat</label>
                      <input type="text" name="lampiran_surat" id="lampiran_surat" class="form-control" placeholder="1 Lampiran">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-12">
                      <label for="perihal_surat">Perihal Surat</label>
                      <textarea type="text" name="perihal_surat" id="perihal_surat" class="form-control d-none" placeholder="Perihal Surat" rows="3"></textarea>
                      <div id="editor_perihal_surat" class="perihal_surat" style="font-size: 14px;">
                      </div>
                  </div>
              </div>
              <hr>
              <div class="row" style="margin-top:60px;" id="dynamic_form-3">
                <div class="form-group baru-data-3">
                  <div class="col-md-12">
                      <label for="tujuan_surat">Tujuan Surat</label>
                      <textarea id="tujuan_surat" name="tujuan_surat[]" placeholder="Tujuan Surat" class="form-control tujuan_surat" rows="2"></textarea>
                  </div>
                  <div class="button-group d-flex justify-content-center mt-2 mb-3">
                      <button type="button" class="btn btn-success btn-tambah-3 mx-2">
                        <span class="desktop-text">Tambah Isian Tujuan Surat</span>
                        <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                      </button>
                      <button type="button" class="btn btn-danger btn-hapus-3" style="display:none;">
                        <span class="desktop-text">Hapus</span>
                        <span class="mobile-logo"><i class="fa fa-times"></i></span>
                      </button>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-12">
                      <label for="alamat_tujuan">Alamat Surat</label>
                      <input type="text" name="alamat_tujuan" id="alamat_tujuan" class="form-control" placeholder="Alamat Instansi / Pejabat">
                  </div>
              </div>
            </div>
{{-- -------------------------------------------------------------------------------isian Surat--------------------------------------------------}}
              <div class="border-3 border p-3 rounded mb-3" style="min-height:600px;">
                <h4 class="mb-3">*Isian Surat</h4>
                <div class="row" id="dynamic_form">
	                <div class="form-group baru-data">
		                <div class="col-md-12">
                        <label for="dasar_acuan">Dasar Acuan Surat</label>
		                    <textarea id="dasar_acuan" name="dasar_acuan[]" placeholder="Dasar Acuan Penugasan" class="form-control dasar_acuan" rows="2"></textarea>
		                </div>
		                <div class="button-group d-flex justify-content-center mt-2 mb-3">
		                    <button type="button" class="btn btn-success btn-tambah mx-2">
                          <span class="desktop-text">Tambah Dasar Acuan Surat</span>
                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                        </button>
		                    <button type="button" class="btn btn-danger btn-hapus" style="display:none;">
                          <span class="desktop-text">Hapus</span>
                          <span class="mobile-logo"><i class="fa fa-times"></i></span>
                        </button>
		                </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-12">
                      <label for="rincian_pelaksanaan_penugasan">Rincian Pelaksanaan Penugasan</label>
                      <textarea type="text" name="rincian_pelaksanaan_penugasan" id="rincian_pelaksanaan_penugasan" class="form-control d-none" placeholder="kami....." rows="3"></textarea>
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
                      <select name="beban_anggaran_id" class="form-select js-example-basic-single" id="beban_anggaran_id_dipa" disabled>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                      <label class="form-check-label mb-2" for="flexRadioDefault2">
                        Mitra
                      </label>
                      <select name="beban_anggaran_id" class="form-select js-example-basic-single" id="beban_anggaran_id_mitra" disabled>
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
                      <select class="form-select" aria-label="Default select example" name="jabatan_id" id="jabatan_id">
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
                        <input type="text" name="nama_pejabat" id="nama_pejabat" class="form-control" placeholder="Nama Pejabat">
                    </div>
                    <div class="col-md-6">
                      <label for="nip">NIP</label>
                      <div class="input-group">
                        <input type="text" name="nip_pejabat"  id="nip" class="form-control block-mask" placeholder="NIP">
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
	                <div class="form-group baru-data-2">
                    <div class="col-md-12 d-flex">
                        <textarea id="tembusan_surat" name="tembusan_surat[]" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="1"></textarea>
                    </div>
		                <div class="button-group d-flex justify-content-center mt-2 mb-3">
		                    <button type="button" class="btn btn-success btn-tambah-2 mx-2">
                          <span class="desktop-text">Tambah Tembusan Surat</span>
                          <span class="mobile-logo"><i class="fa fa-plus"></i></span>
                        </button>
		                    <button type="button" class="btn btn-danger btn-hapus-2" style="display:none;">
                          <span class="desktop-text">Hapus</span>
                        <span class="mobile-logo"><i class="fa fa-times"></i></span>
                        </button>
		                </div>
                  </div>
                </div>
              </div>
{{-- -------------------------------------------------------------------------------Lampiran Surat--------------------------------------------------}}
              <div class="border-3 border p-3 rounded mb-3">
                <h4 class="mb-3">*Lampiran Surat</h4>
                <div class="row" id="dynamic_form-4">
	                <div class="form-group baru-data-4" style="margin-bottom: -25px">
		                <div class="col-md-12">
                      <input class="form-control formFile" type="file" name="lampiran[]" id="formFile">
		                </div>
		                <div class="button-group d-flex justify-content-center mt-4 mb-4">
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
        </div>
        </div>
        <div class="d-flex mb-1">
          <div>
            <div class="container">
              <button id="show_layout" class="btn btn-warning">
                <span class="desktop-text">Layout Surat</span>
                <span class="mobile-logo"><i class="bi bi-info-square"></i></span>
              </button>
              <button id="show_preview" class="btn btn-primary">
                <span class="desktop-text">Preview Surat</span>
                <span class="mobile-logo"><i class="bi bi-eye"></i></span>
              </button>
            </div>
          </div>
        </div>
        <div class="d-flex mb-1">
          <div class="d-flex flex-column pr-4 align-items-center">
          </div>
          <div>
          </div>
        </div>
      </div>
    </div>
  </div>

    {{-- modal --}}
    <div class="modal fade" id="ModalPDf" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="labelModal" style="color: black"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab"
                                aria-controls="home-tab-pane" aria-selected="true">Preview</button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Lampiran 1</button>
            </li> --}}
                    </ul>
                    <div class="tab-content mt-2" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                            aria-labelledby="home-tab" tabindex="0">
                            <div class="ratio ratio-16x9">
                                <iframe id="pdfViewer" src="" loading="lazy"></iframe>
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
              <iframe id="pdfViewerlampiran1" src="" loading="lazy" width="800px" height="1000px"></iframe>
            </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-md waves-effect rounded waves-light btnCancel"
                        title="Batal" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary btn-md waves-effect rounded waves-light" data-bs-toggle="modal"
                        data-bs-target="#exampleModalToggle2" id="saveButton">Save</button>
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
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/js/forms-extras.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
      $(document).ready(function () {

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
              $("#beban_anggaran_id_mitra").removeClass("is-invalid");
              $("#beban_anggaran_id_mitra").removeClass("is-valid");
              $("#flexRadioDefault2").removeClass("is-valid");
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
            $("#beban_anggaran_id_dipa").removeClass("is-invalid");
            $("#beban_anggaran_id_dipa").removeClass("is-valid");
            $("#flexRadioDefault1").removeClass("is-valid");
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


            $('.ql-formats').eq(0).remove();
            $('.ql-formats').eq(3).remove();
            $('.ql-formats').eq(2).remove();
            $('.ql-formats').eq(1).remove();
            $('.ql-formats').eq(4).remove();

            $('.ql-header.ql-picker').empty();
            $('.ql-clean').remove();
            $('.ql-list').remove();
            $('.ql-link').remove();


            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
            const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(
                popoverTriggerEl))

            let pdfLinks = [];
            // let currentIndex = 0;

            $(document).on("change", ".formFile", function(event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const pdfUrl = event.target.result;
                    pdfLinks.push(pdfUrl);
                };

                reader.readAsDataURL(file);
            });

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
            });

            function addFormsss() {
                var addrow = '<div class="form-group baru-data-4" style="margin-bottom: -25px">\
                  <div class="col-md-12">\
                    <input class="form-control formFile" type="file" name="lampiran[]" id="formFile">\
                  </div>\
                  <div class="button-group d-flex justify-content-center mt-4 mb-4">\
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

            $("#dynamic_form-4").on("click", ".btn-tambah-4", function() {
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
        //-----dynamicform_dasar_acuan------
        function addForm() {
            var addrow = '<div class="form-group baru-data">\
                <div class="col-md-12">\
                    <textarea name="dasar_acuan[]"" placeholder="Dasar Acuan Penugasan" class="form-control dasar_acuan" rows="2"></textarea>\
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

            $("#dynamic_form").on("click", ".btn-tambah", function() {
                addForm();
                $(this).css("display", "none");
                $(".btn-hapus").css("display", "none");
                $(".baru-data:last .btn-hapus").show();
            });

        $("#dynamic_form").on("click", ".btn-hapus", function () {
          $(this).closest('.baru-data').remove();
            $(".baru-data:last .btn-tambah").show();
            var bykrow = $(".baru-data").length;
            if (bykrow == 1) {
                $(".btn-hapus").css("display", "none");
                // $(".btn-tambah").css("display", "");
            } else {
                $('.baru-data:last .btn-hapus').css("display", "");
            }
        });
          //-----dynamicform_tembusan_surat------
        function addForms() {
            var addrow = '<div class="form-group baru-data-2">\
              <div class="col-md-12 d-flex">\
                        <textarea id="tembusan_surat" name="tembusan_surat[]" placeholder="Tembusan Surat" class="form-control tembusan_surat" rows="1"></textarea>\
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

            $("#dynamic_form-2").on("click", ".btn-tambah-2", function() {
                addForms();
                $(this).css("display", "none");
                $(".btn-hapus-2").css("display", "none");
                $(".baru-data-2:last .btn-hapus-2").show();
            });

        $("#dynamic_form-2").on("click", ".btn-hapus-2", function () {
          $(this).closest('.baru-data-2').remove();
            $(".baru-data-2:last .btn-tambah-2").show();
              var bykrow = $(".baru-data-2").length;
              if (bykrow == 1) {
                  $(".btn-hapus-2").css("display", "none");
                  // $(".btn-tambah").css("display", "");
              } else {
                  $('.baru-data-2:last .btn-hapus-2').css("display", "");
              }
        });
          //-----dynamicform_Tujuan_surat------
        function addFormss() {
            var addrow = '<div class="form-group baru-data-3">\
                <div class="col-md-12">\
                    <textarea name="tujuan_surat[]" placeholder="Tujuan Surat" class="form-control tujuan_surat" rows="2"></textarea>\
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

            $("#dynamic_form-3").on("click", ".btn-tambah-3", function() {
                addFormss();
                $(this).css("display", "none");
                $(".btn-hapus-3").css("display", "none");
                $(".baru-data-3:last .btn-hapus-3").show();
            });

            $("#dynamic_form-3").on("click", ".btn-hapus-3", function() {
                $(this).closest('.baru-data-3').remove();
                $(".baru-data-3:last .btn-tambah-3").show();
                var bykrow = $(".baru-data-3").length;
                if (bykrow == 1) {
                    $(".btn-hapus-3").css("display", "none");
                    // $(".btn-tambah").css("display", "");
                } else {
                    $('.baru-data-3:last .btn-hapus-3').css("display", "");
                }
            });

            $("#search_nip").click(function() {
                var nip = $("#nip").val();;
                $("#nama_pejabat").val('');
                $('#jabatan_id').val('');
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
                    url: "{{ route('surat.buat_surat.api-nip') }}",
                    type: "POST",
                    data: {
                        nip: nip,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if (response.status == true) {
                            $("#nama_pejabat").val(response.data.name);
                            $('#jabatan_id option[value="' + response.data.jabatan_id + '"]')
                                .prop('selected', true);
                        } else {
                            $("#nama_pejabat").val('');
                            $('#jabatan_id').val('');
                        }
                    }
                });
            });

            $('#jabatan_id').on('change', function() {
                var idjabatan = this.value;
                $("#nama_pejabat").html('');
                $("#nip").html('');
                $.ajax({
                    url: "{{ route('surat.buat_surat.api-jabatan') }}",
                    type: "POST",
                    data: {
                        jabatan_id: idjabatan,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            $("#nama_pejabat").val(response.data[0].name);
                            var nip = response.data[0].nip;

                            var formattedNIP = nip.slice(0, 8) + ' ' + nip.slice(8, 14) + ' ' +
                                nip.slice(14, 15) + ' ' + nip.slice(15);
                            $("#nip").val(formattedNIP);
                        } else {
                            $("#nama_pejabat").val('');
                            $("#nip").val('');
                        }
                    }
                });
            });

            $("#show_layout").click(function() {
                $("#ModalPDf").modal("show");
                $("#pdfViewer").attr("src", "{{ asset('pdf/Format_Surat.pdf') }}");
                $("#labelModal").html('Contoh Format Surat');
                $("#saveButton").css("display", "none");
                $(".btnCancel").css("display", "none");
            });

            $("#show_preview").click(function() {


                var editorContentPenugasan = $('#editor_rincian_pelaksanaan_penugasan p').html();
                $("#rincian_pelaksanaan_penugasan").val(editorContentPenugasan);

                var editorContentPerihal = $('#editor_perihal_surat p').html();
                $("#perihal_surat").val(editorContentPerihal);

                var editorContentAnggaran = $('#editor_beban_anggaran p').html();
                $("#beban_anggaran").val(editorContentAnggaran);

                var tanggal_surat = $("#tanggal_surat").val();
                var lampiran_surat = $("#lampiran_surat").val();
                var perihal_surat = $("#perihal_surat").val();
                var tujuan_surat = $("#tujuan_surat").val();
                var alamat_tujuan = $("#alamat_tujuan").val();
                var dasar_acuan = $("#dasar_acuan").val();
                var rincian_pelaksanaan_penugasan = $("#rincian_pelaksanaan_penugasan").val();
                var beban_anggaran = $("#beban_anggaran_id_dipa").val();
                var jabatan_id = $("#jabatan_id").val();
                var nama_pejabat = $("#nama_pejabat").val();
                var nip = $("#nip").val();
                var tembusan_surat = $("#tembusan_surat").val();

                $("#myTab").empty();
                $("#myTabContent").empty();

                $("#myTab").append(`<li class="nav-item" role="presentation">
              <button class="nav-link active" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-tab-pane" type="button" role="tab" aria-controls="preview-tab-pane" aria-selected="true">Preview</button>
          </li>`);

                $("#myTabContent").append(`<div class="tab-pane fade show active" id="preview-tab-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
            <div class="ratio ratio-16x9">
                <iframe id="pdfViewer" src="" loading="lazy"></iframe>
              </div>
          </div>`);

        var editorContentPerihalSurat = $("#editor_perihal_surat").text().trim();
        if (editorContentPerihalSurat === "" || editorContentPerihalSurat === "<br>") {
            $("#editor_perihal_surat").addClass("border border-danger");
        } else {
            $("#editor_perihal_surat").removeClass("border border-danger");
            $("#editor_perihal_surat").addClass("border border-success");
        }

        var editorContentRincianPelaksanaan = $("#editor_rincian_pelaksanaan_penugasan").text().trim();
        if (editorContentRincianPelaksanaan === "" || editorContentRincianPelaksanaan === "<br>") {
            $("#editor_rincian_pelaksanaan_penugasan").addClass("border border-danger");
        } else {
            $("#editor_rincian_pelaksanaan_penugasan").removeClass("border border-danger");
            $("#editor_rincian_pelaksanaan_penugasan").addClass("border border-success");
        }

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

        //   if (
        //       tanggal_surat === "" ||
        //       lampiran_surat === "" ||
        //       perihal_surat === "" ||
        //       tujuan_surat === "" ||
        //       alamat_tujuan === "" ||
        //       dasar_acuan === "" ||
        //       rincian_pelaksanaan_penugasan === "" ||
        //       beban_anggaran === "" ||
        //       jabatan_id === "" ||
        //       nama_pejabat === "" ||
        //       nip === ""
        //   ) {
        //     Swal.fire({
        //         title: 'error',
        //         icon: 'error',
        //         html: 'Lengkapi Semua Field Terlebih Dahulu',
        //         allowOutsideClick: false,
        //         showConfirmButton: true,
        //     });
        //   } else {

                    $("#pdfViewer").attr("src", "");
                    $("#labelModal").html('Preview Surat');
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
                            $("#pdfViewer").attr("src",
                                '{{ route('surat.buat_surat.pdfview') }}?' +
                                new URLSearchParams(new FormData(form)).toString());
                            $("#ModalPDf").modal("show");
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
                                    success: function(response) {
                                        $("#myTab").append(response.map(function(
                                            res, index) {
                                            return `<li class="nav-item" role="presentation">
                                        <button class="nav-link" id="lampiran-tab-${index}" data-bs-toggle="tab" data-bs-target="#lampiran-tab-pane-${index}" type="button" role="tab" aria-controls="lampiran-tab-pane-${index}" aria-selected="false">Lampiran ${index + 1}</button>
                                    </li>`;
                                        }).join(''));
                                        $("#myTabContent").append(response.map(
                                                function(res, index) {
                                                    return `<div class="tab-pane fade" id="lampiran-tab-pane-${index}" role="tabpanel" aria-labelledby="lampiran-tab-${index}" tabindex="0">
                                      <div class="ratio ratio-16x9">
                                        <iframe id="pdfViewer${index + 1}" src="data:application/pdf;base64,${res}" loading="lazy" width="800px" height="1000px"></iframe>
                                      </div>
                                    </div>`;
                                                }).join('')).find('iframe')
                                            .removeAttr('style');
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
                        },
                        error: function(error) {
                            // console.log(error);
                            Swal.fire({
                                title: 'Error',
                                text: "Lengkapi Semua Field Terlebih Dahulu",
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                // }
            });

            $("#saveButton").on('click', function() {
                var form = document.getElementById('form_surat');
                Swal.fire({
                    title: 'Yakin ?',
                    html: '<p>Apakah anda yakin ingin Menyimpan Surat ?</p>',
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
                            url: '{{ route('surat.buat_surat.create') }}',
                            type: "post",
                            data: new FormData(form),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(response) {
                                setInterval(() => {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: "Surat berhasil Disimpan",
                                        icon: 'success',
                                        timer: 3000,
                                    });
                                }, 3000);
                                window.location.href = '/surat/disposisi_surat';
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
        });
    </script>
@endsection
