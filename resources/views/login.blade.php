<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BPKP Kalbar</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" href="{{asset('img/bpkp_logo.ico')}}" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href={{ asset('/css/bootstrap.min.css') }} type="text/css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">



    <style>
      @import url("https://fonts.googleapis.com/css2?family=Open+Sans&display=swap");
      body {
        font-family: "Open Sans", sans-serif;
      }
      body {
        background: url('/img/bg-login.jpg') no-repeat center center fixed;
        background-size: cover;
        backdrop-filter: brightness(60%);
        overflow-y: hidden;
      }

      .login-box {
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        padding: 20px;
        background-color: white;
      }

      #logo {
        width: 100px;
        margin: 0 auto;
        display: block;
        margin-bottom: 15px;
      }

      .form-control {
        border: none;
        border-bottom: 2px solid #ddd;
        border-radius: 0;
        background-color: #fff !important;
      }

      .shadow-bottom {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
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

    </style>
  </head>
  <body>
    <div class="container">
      <div
        class="row justify-content-center align-items-center"
        style="height: 100vh"
      >
        <div class="col-md-6 col-lg-4 login-box">
          <p class="text-center">Aplikasi Manajemen Surat</p>
          <img id="logo" src="{{asset('img/bpkp_logo.png')}}" alt="logo" />
          <div class="text-center pb-3 bold fw-semibold">
            Badan Pengawasan Keuangan dan Pembangunan
          </div>

          @if(session()->has('loginError'))
            <script src="{{ asset('js/iziToast.min.js') }}"></script>
            <script>
                iziToast.show({
                    title: 'Login Gagal',
                    message: "NIP atau Password anda tidak sesuai",
                    position: 'topRight',
                    color: 'red',
                });
            </script>
          @endif

          <form id="loginForm" action="/login" method="post">
            @csrf
            <div class="mb-3">
              <label for="nip" class="form-label fw-medium">
                NIP
              </label>
              <input type="text" class="form-control block-mask" id="nip" name="nip" value="{{ old('nip') }}" autofocus/>
              @error('nip')
                <small style="color:red;">{{ $message }} </small>
              @enderror
            </div>
            
            <div class="mb-3 pb-3">
              <label for="password" class="form-label fw-medium">
                Password
              </label>
              <div class="password-input-wrapper">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}"/>
                @error('password')
                  <small style="color:red;">{{ $message }}</small>
                @enderror
                <span toggle="#password" class="toggle-password" onclick="togglePasswordVisibility()">
                  <i class="bi bi-eye-fill" id="eye" aria-hidden="true"></i>
                </span>
              </div>
            </div>
            <button type="submit" class="btn btn-info w-100" id="btnlogin">
              LOGIN
            </button>
          </form>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
    <script src="{{ asset('assets/js/forms-extras.js')}}"></script>

    <script>
          function togglePasswordVisibility() {
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
          // $(document).ready(function () {
          //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
          //         $.ajaxSetup({
          //             headers: {
          //             'X-CSRF-TOKEN': csrfToken
          //             },
          //         });
  
          //         let openToasts = 0;
  
          //         function showToast( jenis, title, message) {   
          //             if (openToasts < 3) {
          //                 iziToast[jenis]({
          //                     title: title,
          //                     message: message,
          //                     position: "topCenter",
          //                     timeout: 3000,
          //                     onOpening: function () {
          //                         openToasts++;
          //                     },
          //                     onClosing: function () {
          //                         openToasts--;
          //                     }
          //                 });
          //             }
          //         }
  
          //     // $('#btnlogin').click(function (e) {
          //     //     e.preventDefault();
  
          //     //     var nip = $("#nip").val();
          //     //     var password = $("#password").val();
          //     //     var token = csrfToken;
  
          //     //     // if(nip.length == "") {
          //     //     //     showToast("warning", "Oops..", "Please enter your <b>NIP</b>");
          //     //     // } else if(password.length == "") {
          //     //     //     showToast("warning", "Oops..", "please enter your <b>password</b>");
          //     //     // } else if (!/^\d+$/.test(nip)) {
          //     //     //     showToast("warning", "Oops..", "Please enter your NIP using only <b>numbers</b>");
          //     //     //     return false;
          //     //     // } else {
          //     //     //   iziToast.warning({
          //     //     //       icon: 'fas fa-spinner fa-spin',
          //     //     //       title: 'Loading...',
          //     //     //       message: 'Mohon Tunggu Sebentar',
          //     //     //       position: 'topCenter',
          //     //     //   });

          //     //       // setTimeout(function() {
          //     //         iziToast.destroy();
          //     //         $.ajax({
          //     //           url: '{{route('login')}}',
          //     //           type: "POST",
          //     //           dataType: "JSON",
          //     //           cache: false,
          //     //           data: {
          //     //               "nip": nip,
          //     //               "password": password,
          //     //               "_token": token
          //     //           },
          //     //           success:function(response){
          //     //             console.log(response);
          //     //               if (response.success) {
          //     //                   iziToast.success({
          //     //                       title: "Login Success",
          //     //                       message: response.success,
          //     //                       timeout: 2000,
          //     //                       position: "topCenter",
          //     //                       closeOnClick: true,
          //     //                       onClosing: function () {
          //     //                         if (response.status == "admin") {
          //     //                           window.location.href = '/dashboard';
          //     //                       }else {
          //     //                         window.location.href = '/surat/disposisi_surat';
          //     //                       }
          //     //                   }
          //     //                 })
          //     //               } else if (response.error) {
          //     //                   showToast("error", "Login Error", response.error);
          //     //               }
          //     //           },
          //     //           error:function(error){
          //     //               showToast("error", "Login Error", "something went wrong");
          //     //           }
          //     //         });
          //     //       // },2000);
          //     //     // }
          //     // });
          // });
  </script>
  </body>
</html>

