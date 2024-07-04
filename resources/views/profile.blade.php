@extends('layout.main')

@section('title', 'Users')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" crossorigin="anonymous">

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
      .password-input-wrapper {
        position: relative;
      }

      .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
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
        /* table.dataTable.no-footer {
          border-bottom: 1px solid rgb(255 255 255) !important;
          border-top: 1px solid rgb(255 255 255);
      } */
      /* table.dataTable {
          border-color: white !important;
      } */
      /* table.dataTable th.dt-center, table.dataTable td.dt-center, table.dataTable td.dataTables_empty 
      {
          color: white;
      }
      .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
          color: rgb(0, 0, 0) !important;
      } */
      /* td {
        color: #fff;  
      } */
      /* .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        border: 1px solid rgb(255 255 255);
      } */
      /* .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #f8f8f84e;
      } */
      /* .dataTables_wrapper .dataTables_length select {
        background-color: rgba(26, 20, 20, 0.521) !important;
        color: white;
      } */
      /* table.dataTable.display tbody tr.even>.sorting_1, table.dataTable.order-column.stripe tbody tr.even>.sorting_1 {
          background-color: #191C24 !important;
      } */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

@endsection

@section('navtop') 
<a href="{{ route("surat.manajemen_surat.show") }}" class="button raise">
  <span class="desktop-text">{{ auth()->user()->hak_akses_id == 1 ? "Manajemen Surat" : "Disposisi Surat"}}</span>
  <span class="mobile-logo"><i class="bi bi-kanban-fill"></i></span>
</a>
<a href="{{ route('surat.buat_surat.show') }}" class="mx-3 button raise {{ Request::is('*buat_surat') ? 'active' : '' }}">
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
      <div class="justify-content-between mb-3">
        <h3>Profile</h3>
      </div>
      <div class="row">
        
        <div class="col-md-4 p-5" style="border: 2px solid rgb( 108, 117, 125, 1); border-top-width: 10px; border-radius: 15px; margin-right: 5px;">
          <div class="d-flex flex-column align-items-center">

            @if (auth()->user()->path_image == null)
              <img src="{{ asset('img/user.jpg')}}" alt="" class="rounded-circle mb-3 border border-5 border-secondary" width="125">
            @else
              <img src="{{ asset(auth()->user()->path_image)}}" alt="" class="rounded-circle mb-3 border border-5 border-secondary" width="125">
            @endif

            <h5 class="text-center">{{ $user->name }}</h5>
            <hr class="w-100 border-top border border-1 border-dark">
          </div>
          <div class="d-flex">
            <div style="width: 30%">
              <h6>Bidang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h6>
            </div>
            <div style="width: 70%">
              <p class="text-left">{{ $user->bidang->name }}</p>
            </div>
          </div>
          <div class="d-flex">
            <div style="width: 30%">
              <h6>Jabatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h6>
            </div>
            <div style="width: 70%">
              <p class="text-left">{{ $user->jabatan->name }}</p>
            </div>
          </div>
          <div class="d-flex">
            <div style="width: 30%">
              <h6>Hak Akses &nbsp;:</h6>
            </div>
            <div style="width: 70%">
              <p class="text-left">
                @if ($user->hak_akses_id == 3)
                    {{ $user->hak_akses->name }} {{ $user->tingkatan_eselon }}
                @else
                    {{ $user->hak_akses->name }}
                @endif
              </p>            
            </div>
          </div>
        </div>  

        <div class="col-md-7 p-4 " style="border: 2px solid rgb( 108, 117, 125, 1); border-top-width: 10px; border-radius: 15px; width: 65%">
          @php
            $id = Crypt::encryptString(auth()->user()->id);
          @endphp

          <form action="{{ route('profile.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-4 d-flex flex-column align-items-center">
                <div style="width: 150px;">
                  @if (auth()->user()->path_image == null)
                    <input type="file" name="image" class="dropify" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-default-file="{{ asset('img/user.jpg')}}"/>
                  @else
                    <input type="file" name="image" class="dropify" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-default-file="{{ asset(auth()->user()->path_image)}}"/>
                  @endif
                </div>
              </div>              
              <div class="col-md-8">
                <label for="inputNama" class="form-label">Nama <span style="color: red;">*</span>:</label>
                <input type="text" id="inputNama" name="nama" class="form-control mb-3" value="{{ $user->name}}" required>

                <label for="password" class="form-label">Password <span style="color: red;">*</span>:</label>
                <div class="password-input-wrapper mb-3">
                  <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" required/>
                  <span toggle="#password" class="toggle-password" onclick="togglePassword()">
                    <i class="bi bi-eye-fill" id="eye" aria-hidden="true"></i>
                  </span>
                </div>

                <label for="baru" class="form-label">Password Baru :</label>
                <div class="password-input-wrapper mb-3">
                  <input type="password" class="form-control" id="baru" name="passwordBaru" value="{{ old('passwordBaru') }}"/>
                  <span toggle="#baru" class="toggle-password" onclick="togglePasswordBaru()">
                    <i class="bi bi-eye-fill" id="eye2" aria-hidden="true"></i>
                  </span>
                </div>

                <label for="konfirmasi" class="form-label">Konfirmasi Password Baru :</label>
                <div class="password-input-wrapper">
                  <input type="password" class="form-control" id="konfirmasi" name="konfirmasiPassword" value="{{ old('konfirmasiPassword') }}"/>
                  <span toggle="#konfirmasi" class="toggle-password" onclick="togglePasswordKonfirmasi()">
                    <i class="bi bi-eye-fill" id="eye3" aria-hidden="true"></i>
                  </span>
                </div>

                <button type="submit" class="btn btn-primary mt-3 w-100">Simpan</button>       
              </div>       
            </div>
          </form>
          @if(session()->has('berhasil'))
            <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
            <script src="{{ asset('js/iziToast.min.js') }}"></script>
            <script>
              iziToast.show({
                  title: 'Berhasil',
                  message: "Akun Berhasil Di Update",
                  position: 'topRight',
                  color: 'green',
              });
            </script>
          @endif

          @if(session()->has('gagal'))
            <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
            <script src="{{ asset('js/iziToast.min.js') }}"></script>
            <script>
              iziToast.show({
                  title: 'Gagal',
                  message: "Gagal Memperbarui Akun, Silahkan Periksa Kembali",
                  position: 'topRight',
                  color: 'red',
              });
            </script>
          @endif
        </div>      
      </div>
      <div>

      </div>
    </div>
  </div>
  
@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.11/dist/sweetalert2.all.js"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
    <script src="{{ asset('assets/js/forms-extras.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <script>
      $('.dropify').dropify();

      function togglePasswordKonfirmasi() {
        var passwordInput = $('#konfirmasi');
        var eyeIcon = $('#eye3');

        if (passwordInput.attr('type') === "password") {
            passwordInput.attr('type', 'text');
            eyeIcon.removeClass('bi-eye-fill');
            eyeIcon.addClass('bi-eye-slash-fill');
        } else {
            passwordInput.attr('type', 'password');
            eyeIcon.removeClass('bi-eye-slash-fill');
            eyeIcon.addClass('bi-eye-fill');
        }
      }

      function togglePasswordBaru() {
        var passwordInput = $('#baru');
        var eyeIcon = $('#eye2');

        if (passwordInput.attr('type') === "password") {
            passwordInput.attr('type', 'text');
            eyeIcon.removeClass('bi-eye-fill');
            eyeIcon.addClass('bi-eye-slash-fill');
        } else {
            passwordInput.attr('type', 'password');
            eyeIcon.removeClass('bi-eye-slash-fill');
            eyeIcon.addClass('bi-eye-fill');
        }
      }

      function togglePassword() {
        var passwordInput = $('#password');
        var eyeIcon = $('#eye');

        if (passwordInput.attr('type') === "password") {
            passwordInput.attr('type', 'text');
            eyeIcon.removeClass('bi-eye-fill');
            eyeIcon.addClass('bi-eye-slash-fill');
        } else {
            passwordInput.attr('type', 'password');
            eyeIcon.removeClass('bi-eye-slash-fill');
            eyeIcon.addClass('bi-eye-fill');
        }
      }
    </script>

@endsection