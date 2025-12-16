  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

      <div class="d-flex align-items-center justify-content-between">
          <i class="bi bi-list toggle-sidebar-btn"></i>
          <a href="{{ route('indexDashboard') }}" class="logo d-flex align-items-center  mx-3">
              <img src="{{ asset('img/DataLogo/' . $sistemData->logo) }}" alt="Logo" style="max-width: 200px;">
              <span class="d-none d-lg-block">{{ $sistemData->nama }}</span>
          </a>
      </div><!-- End Logo -->

      <nav class="header-nav ms-auto">
          <ul class="d-flex align-items-center">

              <li class="nav-item dropdown pe-3">

                  <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                      data-bs-toggle="dropdown">
                      @if (auth()->user()->roles === 'karyawan')
                          <img src="{{ asset('img/DataKaryawan/' . auth()->user()->karyawans->foto) }}" alt="Profile"
                              class="rounded-circle">
                      @elseif (auth()->user()->roles === 'pelanggan')
                          <img src="{{ asset('img/DataPelanggan/' . auth()->user()->pelanggans->foto) }}" alt="Profile"
                              class="rounded-circle">
                      @endif
                      <span class="d-none d-md-block dropdown-toggle ps-2">
                          @if (auth()->user()->roles === 'admin')
                              {{ auth()->user()->admins->nama ?? '' }}
                          @elseif (auth()->user()->roles === 'karyawan')
                              {{ auth()->user()->karyawans->nama ?? '' }}
                          @elseif (auth()->user()->roles === 'pelanggan')
                              {{ auth()->user()->pelanggans->nama ?? '' }}
                          @endif
                      </span>
                  </a>

                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                      <li class="dropdown-header">
                          @if (auth()->user()->roles === 'admin')
                              <h6>{{ auth()->user()->admins->nama ?? '' }}</h6>
                              <span>{{ auth()->user()->roles }}</span>
                          @elseif (auth()->user()->roles === 'karyawan')
                              <h6>{{ auth()->user()->karyawans->nama ?? '' }}</h6>
                              <span>{{ auth()->user()->roles }}</span>
                          @elseif (auth()->user()->roles === 'pelanggan')
                              <h6>{{ auth()->user()->pelanggans->nama ?? '' }}</h6>
                              <span>{{ auth()->user()->roles }}</span>
                          @endif
                      </li>
                      <li>
                          <hr class="dropdown-divider">
                      </li>

                      {{-- <li>
                          <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                              <i class="bi bi-gear"></i>
                              <span>Account Settings</span>
                          </a>
                      </li> --}}
                      <li>
                          <hr class="dropdown-divider">
                      </li>

                      <li>
                          <form action="{{ route('outAuth') }}" method="post">
                              @csrf
                              <button type="submit" class="dropdown-item d-flex align-items-center"> <i
                                      class="bi bi-box-arrow-right"></i>
                                  <span>Logout</span></button>
                          </form>
                      </li>

                  </ul><!-- End Profile Dropdown Items -->
              </li><!-- End Profile Nav -->

          </ul>
      </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
