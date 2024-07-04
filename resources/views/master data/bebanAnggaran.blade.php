@extends('layout.main')

@section('title', 'Lembaga Negara')

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
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-white shadow-lg text-center rounded p-4">
      <div class="justify-content-between">
        <h3 class="mb-0">Data Lembaga Nagara</h3>
        <a href="javascript:void(0)" class="btn btn-primary my-3" id="newLembagaNegara">
          Tambah Data
        </a>
      </div>
      <div>
        <table id="myTable" class="table table-striped table-hover border responsive nowrap" width="100%">
          <thead>
            <tr>
              <th scope="col"  class="col-md-1">#</th>
              <th scope="col"  class="col-md-6">Nama Lembaga Nagara</th>
              <th scope="col"  class="col-md-3">Jenis Lembaga Nagara</th>
              <th scope="col"  class="col-md-5">Aksi</th>
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
          <h1 class="modal-title fs-5" id="labelModal" style="color: black">Tambah Data Lembaga Negara</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" id="lembagaForm" action="javascript:void(0)">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="action" id="action">
            
            <label for="name" class="col-form-label" style="color: black">Jenis Lembaga Negara</label>
            {{-- @php
                $nomor1 = Crypt::encryptString(1);
                $nomor2 = Crypt::encryptString(2);
            @endphp --}}
            <select class="form-select" id="jenisLembagaNegara" name="jenisLembagaNegara" aria-label="Default select example">
              <option value="" hidden disabled selected>Pilih Jenis Lembaga Negara</option>
              <option value="1">Dipa</option>
              <option value="2">Mitra</option>
            </select>


            <label for="name" class="col-form-label" style="color: black">Nama Lembaga Negara</label>
            <input type="text" class="form-control" id="namaLembagaNegara" name="namaLembagaNegara" placeholder="Isi Lembaga Negara" required>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-md waves-effect rounded waves-light btnCancel" title="Batal">Batal</button>
          <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" id="saveButton" data-bs-toggle="modal">Save</button>
        </div>
      </div>
    </div>
  </div>
  
@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>\
    <script src="{{ asset('assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.11/dist/sweetalert2.all.js"></script>


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

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("master_data.lembaga_negara.show") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_lembaga', name: 'nama_lembaga' },
                    { data: 'jenis_lembaga', name: 'jenis_lembaga' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { targets: [1], className: 'wrap' } // Apply wrap class to all columns
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

            $('#newLembagaNegara').click(function () {
              $('#Modal').modal('show');
              $('#labelModal').html("Tambah Data Lembaga Negara");
              $('#saveButton').text('Save');
              $('#action').val('tambah');
              $('#id').val('');
              $('#jenisLembagaNegara').val('');
              $('#namaLembagaNegara').val('');
            });

//---------------------------------------------------- Submit

            $('#saveButton').click(function (e) {
                e.preventDefault();

                var form = document.getElementById('lembagaForm');

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
                        url: "{{ route('master_data.lembaga_negara.create_update') }}",
                        data: new FormData(form),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);
                            if(response.status == true) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.pesan,
                                    icon: "success",
                                    confirmButtonText: 'Ok'
                                });
                                $('#lembagaForm').trigger("reset");
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


//---------------------------------------------------- Edit

            $(document).on('click', '.btnEdit', function(e) {
              e.preventDefault();

              let id = $(this).data('id');
              let url = '{{ route('master_data.lembaga_negara.edit', ':id') }}';
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
                        console.log(response);
                          Swal.close(); 
                          $('#id').val(response.encryptedID);
                          $('#jenisLembagaNegara option[value="' + response.data.jenis_lembaga + '"]').prop('selected',true);
                          $('#namaLembagaNegara').val(response.data.nama_lembaga);


                          $('#action').val('edit');
                          $('#Modal').modal("show");
                          $('#saveButton').text('Update');
                          $('#labelModal').text('Edit Data Lembaga Negara');
                      },
                      error: function(error) {
                          console.error(error);
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
                  html: '<p>Apakah anda yakin ingin menghapus Lembaga Negara :</p>' +
                      '<p><b>' + nama + '</b></p>' +
                      '<p>Semua Surat yang terkait dengan Lembaga ini juga akan terhapus!</p>',
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
                      let url = '{{ route('master_data.lembaga_negara.delete', ':id') }}';
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
                                } else {
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