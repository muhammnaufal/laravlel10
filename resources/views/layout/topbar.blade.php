<nav class="navbar navbar-expand bg-dark navbar-dark sticky-top px-4 py-0 shadow-lg">
    {{-- <a href="{{ route('dashboard')}}" class="navbar-brand d-flex d-lg-none me-4">
      <h2 class="text-white mb-0">
        <img class="me-2" src="{{ asset('img/bpkp_logo.png')}}" alt="bpkp" style="width: 40px; height: 40px">
      </h2>
    </a> --}}
    {{-- @if (auth()->user()->hak_akses_id == 1 || auth()->user()->hak_akses_id == 3) --}}
    <a href="#" style="text-decoration:none;" class="sidebar-toggler text-white flex-shrink-0">
      <i style="font-size: 20px;" class="fa fa-bars"></i>
    </a>
    {{-- @endif --}}

    @yield('navtop')   

    <div class="navbar-nav align-items-center ms-auto">

      <div class="nav-item dropdown">
        <a
          href="#"
          class="nav-link dropdown-toggle"
          data-bs-toggle="dropdown"
        >
        @if (auth()->user()->path_image == null)
          <img src="{{ asset('img/user.jpg')}}" alt="" class="rounded-circle me-lg-2" style="width: 40px; height: 40px">
        @else
          <img src="{{ asset(auth()->user()->path_image)}}" alt="" class="rounded-circle me-lg-2" style="width: 40px; height: 40px">
        @endif
        
          <span class="d-none d-lg-inline-flex">{{ auth()->user()->name}}</span>
        </a>
        <div
          class="dropdown-menu dropdown-menu-end bg-dark border-0 rounded-0 rounded-bottom m-0"
        >
        @php
            $id = Crypt::encryptString(auth()->user()->id);
        @endphp
        
          <a href="{{ route('profile.show', ['id' => $id]) }}" class="dropdown-item">
              <i class="bi bi-person-circle"></i> 
              My Profile
          </a>
          <a href="{{ route('logout') }}" class="dropdown-item">
            <i class="bi bi-box-arrow-in-right"></i>
            Log Out
          </a>
        </div>
      </div>
    </div>
  </nav>
