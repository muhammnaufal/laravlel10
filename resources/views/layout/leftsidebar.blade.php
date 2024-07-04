<div class="sidebar pe-4 pb-3">
    <nav class="navbar navbar-dark">
      <a href="{{ route('dashboard')}}" class="navbar-brand mx-4 mb-3">
        <h3 class="text-white">
          <img class="me-2" src="{{ asset('img/bpkp_logo.png')}}" alt="bpkp" style="width: 40px; height: 40px;">
          BPKP Kalbar
        </h3>
      </a>
      <div class="d-flex align-items-center ms-4 mb-4">
        <div class="position-relative">
          @if (auth()->user()->path_image == null)
            <img src="{{ asset('img/user.jpg')}}" alt="" class="rounded-circle" style="width: 40px; height: 40px">
          @else
            <img src="{{ asset(auth()->user()->path_image)}}" alt="" class="rounded-circle" style="width: 40px; height: 40px">
          @endif
          </div>
        <div class="ms-3 text-white">
          <h4 class="mb-0">{{ auth()->user()->name }}</h4>
          <p style="font-size: 12px; color:rgb(202, 193, 193);">{{ auth()->user()->jabatan->name }}</p>
        </div>
      </div>
      <div class="navbar-nav w-100">
        <a href="{{ route('dashboard') }}" class="nav-item nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
          <i class="fa fa-tachometer-alt me-2"></i>
          Dashboard
        </a>
        <a href="{{ route('surat.manajemen_surat.show') }}" class=" nav-item nav-link {{ Request::is('surat*') ? 'active' : '' }}">
          <i class="fa fa-envelope" aria-hidden="true"></i>
          Surat
        </a>
        @can('admin')
        <a href="{{ route('user.show') }}" class="nav-item nav-link {{ Request::is('user') ? 'active' : '' }}">
          <i class="fa fa-user" aria-hidden="true"></i>
          User
        </a>
        <div class="nav-item dropdown">
          <a href="#" class="nav-item dropdown-toggle nav-link {{ Request::is('master-data*') ? 'active show' : '' }}" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" class="svg" width="16" height="16" fill="currentColor" class="bi bi-database-gear" viewBox="0 0 16 16">
              <path d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1 1.753.25V4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-.813-.927Q8.378 15 8 15c-1.464 0-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13h.027a4.6 4.6 0 0 1 0-1H8c-1.464 0-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10q.393 0 .774-.024a4.5 4.5 0 0 1 1.102-1.132C9.298 8.944 8.666 9 8 9c-1.464 0-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777M3 4c0-.374.356-.875 1.318-1.313C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4"/>
              <path d="M11.886 9.46c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
            </svg>
            Master Data
        </a>
          <div class="dropdown-menu bg-transparent border-0 {{ Request::is('master-data*') ? 'show' : '' }}">
            <a href="{{route('master_data.bidang.show')}}" class="dropdown-item {{ Request::is('*bidang') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#6C7293" height="20" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM609.3 512H471.4c5.4-9.4 8.6-20.3 8.6-32v-8c0-60.7-27.1-115.2-69.8-151.8c2.4-.1 4.7-.2 7.1-.2h61.4C567.8 320 640 392.2 640 481.3c0 17-13.8 30.7-30.7 30.7zM432 256c-31 0-59-12.6-79.3-32.9C372.4 196.5 384 163.6 384 128c0-26.8-6.6-52.1-18.3-74.3C384.3 40.1 407.2 32 432 32c61.9 0 112 50.1 112 112s-50.1 112-112 112z"/></svg>
              Bidang
            </a>
            <a href="{{route('master_data.jabatan.show')}}" class="dropdown-item {{ Request::is('*jabatan') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#6C7293" height="20" width="20" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M96 128a128 128 0 1 0 256 0A128 128 0 1 0 96 128zm94.5 200.2l18.6 31L175.8 483.1l-36-146.9c-2-8.1-9.8-13.4-17.9-11.3C51.9 342.4 0 405.8 0 481.3c0 17 13.8 30.7 30.7 30.7H162.5c0 0 0 0 .1 0H168 280h5.5c0 0 0 0 .1 0H417.3c17 0 30.7-13.8 30.7-30.7c0-75.5-51.9-138.9-121.9-156.4c-8.1-2-15.9 3.3-17.9 11.3l-36 146.9L238.9 359.2l18.6-31c6.4-10.7-1.3-24.2-13.7-24.2H224 204.3c-12.4 0-20.1 13.6-13.7 24.2z"/></svg>
              Jabatan
            </a>
            <a href="{{route('master_data.hak_akses.show')}}" class="dropdown-item {{ Request::is('*hak_akses') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#6C7293" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm161.5-86.1c-12.2-5.2-26.3 .4-31.5 12.6s.4 26.3 12.6 31.5l11.9 5.1c17.3 7.4 35.2 12.9 53.6 16.3v50.1c0 4.3-.7 8.6-2.1 12.6l-28.7 86.1c-4.2 12.6 2.6 26.2 15.2 30.4s26.2-2.6 30.4-15.2l24.4-73.2c1.3-3.8 4.8-6.4 8.8-6.4s7.6 2.6 8.8 6.4l24.4 73.2c4.2 12.6 17.8 19.4 30.4 15.2s19.4-17.8 15.2-30.4l-28.7-86.1c-1.4-4.1-2.1-8.3-2.1-12.6V235.5c18.4-3.5 36.3-8.9 53.6-16.3l11.9-5.1c12.2-5.2 17.8-19.3 12.6-31.5s-19.3-17.8-31.5-12.6L338.7 175c-26.1 11.2-54.2 17-82.7 17s-56.5-5.8-82.7-17l-11.9-5.1zM256 160a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
              Hak Akses
            </a>
            <a href="{{route('master_data.lembaga_negara.show')}}" class="dropdown-item {{ Request::is('*lembaga_negara') ? 'active' : '' }}">
              <i class="fa fa-institution" style="font-size:20px"></i>
              Lembaga Negara
            </a>
          </div>
        </div>
        @endcan

        {{-- <a href="#" class="nav-item nav-link"
          ><i class="fa fa-th me-2"></i>XXXX</a
        >
        <a href="#" class="nav-item nav-link"
          ><i class="fa fa-keyboard me-2"></i>XXX</a
        >
        <a href="#" class="nav-item nav-link"
          ><i class="fa fa-table me-2"></i>XXX</a
        >
        <a href="#" class="nav-item nav-link"
          ><i class="fa fa-chart-bar me-2"></i>XXXX</a
        >
      </div> --}}
    </nav>
  </div>
