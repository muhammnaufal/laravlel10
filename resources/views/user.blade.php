@extends('layout.main')

@section('title', 'Users')

@section('css')

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

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-white shadow-lg text-center rounded p-4">
      <div class="justify-content-between">
        <h3 class="mb-0">Data User</h3>
        <a href="javascript:void(0)" class="btn btn-primary my-3" id="newUser">
          Tambah Data
        </a>
      </div>
      <div>
        <table id="myTable" class="table table-striped table-hover border responsive nowrap"  width="100%">
          <thead>
            <tr>
              <th scope="col"  class="col-md-2">#</th>
              <th scope="col"  class="col-md-6">NIP</th>
              <th scope="col"  class="col-md-6">Nama User</th>
              <th scope="col"  class="col-md-6">Bidang</th>
              <th scope="col"  class="col-md-6">Jabatan</th>
              <th scope="col"  class="col-md-6">Hak Akses</th>
              <th scope="col"  class="col-md-6">Default Password</th>
              <th scope="col"  class="col-md-4">Aksi</th>
            </tr>
          </thead>
          <tbody>
           
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- modal --}}
  <div class="modal fade" id="Modal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="labelModal" style="color: black">Tambah Data User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" id="userForm" action="javascript:void(0)">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="action" id="action">
            
            <div class="mb-2">
              <label for="name" class="col-form-label" style="color: black">NIP</label>
              <input type="text" class="form-control block-mask" id="nip" name="nip" placeholder="Isi Nomor Nip" required>
            </div>
            
            <div class="mb-2">
              <label for="name" class="col-form-label" style="color: black">Nama User</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Isi Nama User" required>
            </div>

            <div class="mb-2" id="password-input">
              <label for="password" class="col-form-label" style="color: black">
                Password User
              </label>
              <div class="password-input-wrapper">
                <input type="password" class="form-control" id="password" placeholder="Isi Passwor User" name="password" required/>
                <span toggle="#password" class="toggle-password" onclick="togglePasswordVisibility()">
                  <i class="bi bi-eye-fill" id="eye" aria-hidden="true"></i>
                </span>
              </div>
            </div>

            <div class="mb-2">
              <label for="name" class="col-form-label" style="color: black">Bidang</label>
              <select class="form-select" aria-label="Default select example" name="bidang_id" id="bidang">
                <option value="" disabled selected hidden>Pilih Bidang</option>
                @foreach($bidangs as $bidang)
                  <option value="{{ $bidang->id }}"> {{ $bidang->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-2">
              <label for="name" class="col-form-label" style="color: black">Jabatan</label>
              <select class="form-select" aria-label="Default select example" name="jabatan_id" id="jabatan">
                <option value="" disabled selected hidden>Pilih Jabatan</option>
                @foreach($jabatans as $jabatan)
                  <option value="{{ $jabatan->id }}"> {{ $jabatan->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-2">
              <label for="name" class="col-form-label" style="color: black">Hak Akses</label>
              <select class="form-select" aria-label="Default select example" name="hak_akses_id" id="hak_akses">
                <option value="" disabled selected hidden>Pilih Hak Akses</option>
                @foreach($hakakseses as $hakakses)
                  <option value="{{ $hakakses->id }}"> {{ $hakakses->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-2" id="inputEselon">
              <label for="name" class="col-form-label" style="color: black">Tingkatan Eselon</label>
              <select class="form-select" aria-label="Default select example" name="tingkatan_eselon" id="tingkatEselon">
                <option value="" disabled selected hidden>Pilih Tingkatan</option>
                  <option value="2"> Tingkatan II</option>
                  <option value="3"> Tingkatan III</option>
                  <option value="4"> Tingkatan IV</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-md waves-effect rounded waves-light btnCancel" title="Batal">Batal</button>
          <button class="btn btn-primary btn-md waves-effect rounded waves-light" data-bs-target="#exampleModalToggle2" id="saveButton" data-bs-toggle="modal">Save</button>
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

        $(document).ready(function() {

//---------------------------------------------------- CSRF

          var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
            });
//---------------------------------------------------- Table

            $('#myTable').DataTable({
                responsive: true, 
                processing: true,
                serverSide: true,
                ajax: '{{ route("user.show") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, responsivePriority: 1 },
                    { data: 'NIP', name: 'NIP' },
                    { data: 'name', name: 'name', responsivePriority: 1},
                    { data: 'bidang', name: 'bidang'},
                    { data: 'jabatan', name: 'jabatan'},
                    { data: 'hak_akses', name: 'hak_akses' },
                    { data: 'default_password', name: 'default_password' },
                    { data: 'action', name: 'action',  responsivePriority: 1, orderable: false, searchable: false}
                ],
                columnDefs: [
                    { targets: [3], className: 'wrap' } // Apply wrap class to all columns
                ]
            });

            function handleResponsive() {
            var isMobile = window.innerWidth <= 486;

            if (isMobile) {
              $('#myTable').removeClass('nowrap');
            } else {
              $('#myTable').addClass('nowrap');
            }
          }

          handleResponsive();

          $(window).resize(function() {
            handleResponsive();
          });

//---------------------------------------------------- Tambah

            $('#newUser').click(function () {
              $('#Modal').modal('show');
              $('#labelModal').html("Tambah Data User");
              $('#saveButton').text('Save');
              $('#action').val('tambah');
              $('#id').val('');
              $('#nip').val('');
              $('#name').val('');
              $('#password-input').css('display', 'block');
              $('#password').val('');
              $('#bidang').val('');
              $('#jabatan').val('');
              $('#hak_akses').val('');
              $('#tingkatEselon').val('');
              $("#name-error").html('');
            });

//---------------------------------------------------- Submit

            $('#saveButton').click(function (e) {
                e.preventDefault();

                var form = document.getElementById('userForm');

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
                        type: "POST",
                        url: "{{ route('user.create_update') }}",
                        data: new FormData(form),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (response) {
                            if(response.status == true) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.pesan,
                                    icon: "success",
                                    confirmButtonText: 'Ok'
                                });
                                $('#userForm').trigger("reset");
                                $('#Modal').modal('hide').fadeOut();
                                $('#myTable').DataTable().draw();
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    html: response.error,
                                    icon: "error",
                                    confirmButtonText: 'Redo'
                                })
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            swal.fire({
                                title: 'Gagal!',
                                text: 'Error: ' + errorThrown,
                                icon: 'error',
                                confirmButtonText: 'Redo'
                            })
                        }
                    });
                }, 800);
            });

            $('#inputEselon').hide();
            
            $('#hak_akses').on('change', function() {
              var selectedValue = $(this).val()

              if (selectedValue == 3) {
                $('#tingkatEselon').val('');
                $('#inputEselon').removeClass('animate__animated animate__fadeOutDown').addClass('animate__animated animate__fadeInDown').show();
              } else {
                $('#inputEselon').removeClass('animate__animated animate__fadeInDown').addClass('animate__animated animate__fadeOutDown').hide();
              }
            });


//---------------------------------------------------- Edit

            $(document).on('click', '.btnEdit', function(e) {
              e.preventDefault();

              let id = $(this).data('id');
              let url = '{{ route('user.edit', ':id') }}';
              url = url.replace(':id', id);
              
              $('#password-input').css('display', 'none');

              $('#Modal').modal("show");
              $('#id').val('');
              $('#nip').val('');
              $('#name').val('');
              $('#bidang').val('');
              $('#jabatan').val('');
              $('#hak_akses').val('');
              $('#tingkatEselon').val('');

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
                          $('#id').val(response.encryptedID);
                          var nip = response.data.NIP;

                          var formattedNIP = nip.slice(0, 8) + ' ' + nip.slice(8, 14) + ' ' + nip.slice(14, 15) + ' ' + nip.slice(15);
    
                          $('#nip').val(formattedNIP);
                          // $('#nip').val(response.data.NIP);
                          $('#name').val(response.data.name);
                          $('#bidang option[value="' + response.data.bidang_id + '"]').prop('selected',true);
                          $('#jabatan option[value="' + response.data.jabatan_id + '"]').prop('selected',true);
                          $('#hak_akses option[value="' + response.data.hak_akses_id + '"]').prop('selected',true);
                          if (response.data.hak_akses_id == 3) {
                            $('#tingkatEselon').val('');
                            $('#inputEselon').removeClass('animate__animated animate__fadeOutDown').addClass('animate__animated animate__fadeInDown').show();
                            $('#tingkatEselon option[value="' + response.data.tingkatan_eselon + '"]').prop('selected',true);
                          }else {
                            $('#inputEselon').removeClass('animate__animated animate__fadeInDown').addClass('animate__animated animate__fadeOutDown').hide();
                          }

                          $('#action').val('edit');
                          $('#saveButton').text('Update');
                          $('#labelModal').text('Edit Data User');
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

//---------------------------------------------------- Delete

            $(document).on('click', '.btnDelete', function (e) {
              e.preventDefault();
              var id = $(this).data("id");
              let nama = $(this).data('name');
              Swal.fire({
                  title: 'Yakin ?',
                  html: '<p>Apakah anda yakin ingin menghapus User :</p>' +
                      '<p><b>' + nama + '</b></p>' + 
                      '<p>Semua surat yang menyangkut dari akun ini juga akan ikut terhapus!</p>',
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
                      let url = '{{ route('user.delete', ':id') }}';
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
//---------------------------------------------------- Reset
            $(document).on('click', '.btnReset', function (e) {
              e.preventDefault();
              var id = $(this).data("id");
              let nama = $(this).data('name');
              Swal.fire({
                html: "<h3>Masukkan Password Baru Untuk</h3><h2>" + nama + "</h2>",
                input: "password",
                inputPlaceholder: "Masukkan password baru",
                inputAttributes: {
                  autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Reset Password",  
              }).then((result) => {
                  if (result.isConfirmed) {
                      let url = '{{ route('user.reset', ':id') }}';
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
                            type: 'POST',
                            data: {
                              password: result.value,
                            },
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
        });
    </script>
@endsection