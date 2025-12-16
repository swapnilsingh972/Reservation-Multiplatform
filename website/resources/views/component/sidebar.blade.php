  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          @if (auth()->user()->roles === 'admin')
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}"
                      href="{{ route('indexDashboard') }}">
                      <i class="bi bi-grid"></i>
                      <span>Dashboard</span>
                  </a>
              </li><!-- End Dashboard Nav -->

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('pengguna*') || Request::is('karyawan*') || Request::is('pelanggan*') ? '' : 'collapsed' }}"
                      data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-database"></i><span>Manajemen</span><i class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="components-nav"
                      class="nav-content collapse {{ Request::is('pengguna*') || Request::is('karyawan*') || Request::is('pelanggan*') ? 'show' : '' }}"
                      data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('indexPengguna') }}"
                              class="{{ Request::is('pengguna*') ? 'text-primary' : '' }}">
                              <i class="bi bi-person" style="font-size: 1.2rem;"></i><span>Pengguna</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('indexKaryawan') }}"
                              class="{{ Request::is('karyawan*') ? 'text-primary' : '' }}">
                              <i class="bi bi-person" style="font-size: 1.2rem;"></i><span>karyawan</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('indexPelanggan') }}"
                              class="{{ Request::is('pelanggan*') ? 'text-primary' : '' }}">
                              <i class="bi bi-person" style="font-size: 1.2rem;"></i><span>pelanggan</span>
                          </a>
                      </li>
                  </ul>
              </li><!-- End Components Nav -->

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('layanan*') ? '' : 'collapsed' }}"
                      href="{{ route('indexLayanan') }}">
                      <i class="bi bi-bag"></i>
                      <span>Layanan</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('indexreservasi*') ? '' : 'collapsed' }}"
                      href="{{ route('indexReservasiAdmin') }}">
                      <i class="bi bi-calendar-event"></i>
                      <span>Reservasi</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('sistem*') ? '' : 'collapsed' }}"
                      href="{{ route('indexSistem') }}">
                      <i class="bi bi-journal-bookmark"></i>
                      <span>Setting</span>
                  </a>
              </li>
          @elseif (auth()->user()->roles === 'karyawan')
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}"
                      href="{{ route('indexDashboard') }}">
                      <i class="bi bi-grid"></i>
                      <span>Dashboard</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('layanan*') ? '' : 'collapsed' }}"
                      href="{{ route('indexLayanan') }}">
                      <i class="bi bi-bag"></i>
                      <span>Layanan</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('indexreservasi*') ? '' : 'collapsed' }}"
                      href="{{ route('indexReservasiKaryawan') }}">
                      <i class="bi bi-calendar-event"></i>
                      <span>Data Reservasi</span>
                  </a>
              </li>
              {{-- <li class="nav-item">
                  <a class="nav-link {{ Request::is('reservasi*') ? '' : 'collapsed' }}"
                      href="{{ route('indexReservasi') }}">
                      <i class="bi bi-calendar-plus"></i>
                      <span>Tambah Reservasi</span>
                  </a>
              </li> --}}
          @elseif (auth()->user()->roles === 'pelanggan')
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}"
                      href="{{ route('indexDashboard') }}">
                      <i class="bi bi-grid"></i>
                      <span>Dashboard</span>
                  </a>
              </li><!-- End Dashboard Nav -->
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('reservasi*') ? '' : 'collapsed' }}"
                      href="{{ route('indexReservasi') }}">
                      <i class="bi bi-grid"></i>
                      <span>Reservasi</span>
                  </a>
              </li>
          @endif
      </ul>

  </aside><!-- End Sidebar-->
