<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>BPKP Kalbar | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- icon LOgo bpkp -->
    <link href="{{asset('img/bpkp_logo.ico')}}" rel="icon" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"\rel="stylesheet"/>

    <!-- untuk panggil icon disini jak carek classnye Icon boostrap same font awesome kalo font-awesome yang fa-laptop tu manggil iconnye -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet"/>

    <!-- Libraries Style -->
    <link href="{{asset('css/owl.carousel.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/tempusdominus-bootstrap-4.min.css')}}" rel="stylesheet"/>

    <!-- Custom Bootstrap disini je-->
    {{-- <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- Stylesheet -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" />
    <style>
     .svg {
          width: 30px;
          height: 30px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          background: var(--dark);
          border-radius: 40px;
      }
      .bg-dark {
          --bs-bg-opacity: 1;
          background-color: #191c24 !important;
      }
      .content .navbar .dropdown-item {
          color: #ffffff !important;
      }
      body{
          padding-right: 0 !important;
      }
      /* table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
        background-color: #eb1616 !important;
      }
      table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
        background-color: #ffffff00 !important;
      }
      table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
        background-color: #ffffff00 !important;
      }
      .table > :not(caption) > * > * {
        padding: 0.5rem 0.5rem;
        background-color: #191C24;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
      }
      .dataTables_wrapper .dataTables_filter input {
        background-color: white !important;
      }
      .form-select {
        background-color: #fff !important;
        color: black !important;
      }
      .form-control {
        background-color: #fff !important;
        color: black !important;
      }  */
    </style>
    @yield('css')
  </head>

  <body>
    <div class="container-fluid position-relative d-flex p-0">
      <!-- Spinner Awal -->
      <div
        id="spinner"
        class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
      >
        <div
          class="spinner-border text-info"
          style="width: 3rem; height: 3rem"
          role="status"
        >
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <!-- Spinner Akhir -->

      <!-- Sidebar Awal -->
      {{-- @if (auth()->user()->hak_akses_id == 1 || auth()->user()->hak_akses_id == 3) --}}
      @include('layout.leftsidebar')
      {{-- @endif --}}
      <!-- Sidebar Akhir -->

      <!-- Konten Awal -->
      <div class="content">
        
        <!-- Navbar Awal -->
        @include('layout.topbar')
        <!-- Navbar Akhir -->

        <!-- sini a kalo mo buat isinye pake -->
        @yield('content')
        <!-- akhir nye -->

        <!-- Footer Awal -->
        @include('layout.footer')
        <!-- Footer Akhir -->

      </div>
      <!-- Konten Akhir -->

      <!-- Tombol pencet ke atas -->
      {{-- <a href="#" class="btn btn-lg btn-secondary btn-lg-square back-to-top"
        ><i class="bi bi-arrow-up"></i
      ></a> --}}
    </div>

    <!-- JS Library coy-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- <link href="{{ asset ('assets/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset ('assets/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />


    <script src="{{asset('js/chart.min.js')}}"></script>
    <script src="{{asset('js/easing.min.js')}}"></script>
    <script src="{{asset('js/waypoints.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/moment-timezone.min.js')}}"></script>
    <script src="{{asset('js/tempusdominus-bootstrap-4.min.js')}}"></script>

    <!--  JS Carek di web masokan sini -->
    <script src="{{asset('js/main.js')}}"></script>

    <script>  
    </script>
      @yield('js')
  </body>
</html>
