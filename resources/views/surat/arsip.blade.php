@extends('layout.main')

@section('title', 'Manajemen Surat')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />

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
      .form-check-input[type=checkbox] {
          border-radius: 50% !important;
      }
      .form-check-input:checked {
          background-color: #40d52a !important;
          border-color: #40d52a !important;
      }
      .form-check-input {
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
<a href="{{ route("surat.manajemen_surat.show") }}" class="button raise">
  <span class="desktop-text">{{ auth()->user()->hak_akses_id == 1 ? "Manajemen Surat" : "Disposisi Surat"}}</span>
  <span class="mobile-logo"><i class="bi bi-kanban-fill"></i></span>
</a>
<a href="{{ route('surat.buat_surat.show') }}" class="mx-3 button raise">
  <span class="desktop-text">Buat Surat</span>
  <span class="mobile-logo"><i class="bi bi-envelope"></i></span>
</a>
<a href="{{ route('surat.arsip_surat.show') }}" class="button raise {{ Request::is('*arsip_surat') ? 'active' : '' }}">
  <span class="desktop-text">Arsip</span>
  <span class="mobile-logo"><i class="bi bi-archive"></i></span>
</a>
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-white shadow-lg text-center rounded p-4">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="mb-0">Arsip Surat</h6>
      </div>
      <div>
        <table id="myTable" class="table table-striped table-hover border responsive nowrap" width="100%">
          <thead>
            <tr>
              <th>Nomor Surat</th>
              <th>Perihal Penugasan</th>
              <th>Pembuat Surat</th>
              <th>E2</th>
              <th>E3</th>
              <th>E4</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
           
          </tbody>
        </table>
      </div>
    </div>
  </div>

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

    <script>
        $(document).ready(function() {
//---------------------------------------------------- CSRF

          var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
            });

//---------------------------------------------------- Table

           var myTable = $('#myTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route("surat.arsip_surat.show") }}',
                columns: [
                  { data: 'nomor_surat', name: 'nomor_surat', width: '10%', },
                  { data: 'perihal_surat', name: 'perihal_surat', width: '60%', },
                  { data: 'pembuat_surat', name: 'pembuat_surat', width: '20%', },
                  { data: 'e2', name: 'e2', width: '5%'},
                  { data: 'e3', name: 'e3', width: '5%'},
                  { data: 'e4', name: 'e4', width: '5%'},
                  { data: 'action', name: 'action ', width: '5%', searchable: false, orderable: false},
                ],
                columnDefs: [
                    { targets: [1, 2], className: 'wrap' } // Apply wrap class to all columns
                ],
            });

            $(document).on('click', '#riwayatSurat', function() {
              $('#ModalRiwayat').modal('show');
              $('#title-riwayat').text('Riwayat Surat');
            });

//---------------------------------------------------- tombol batal
            $(document).on('click', '.btnCancel', function (e) {
                $('#Modal').modal("hide");
            });

            $(document).on('click', '.btnArsip', function (e) { 
              var id = $(this).data('id');
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Keluarkan surat ini dari Arsip ?</p>',
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
        });
    </script>
@endsection