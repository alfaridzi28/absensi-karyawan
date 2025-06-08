<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="text-nowrap logo-img mt-4">
        <img src="{{ asset('assets/images/logos/MorpTechnology.png') }}" alt="" alt="" width="180" height="50"/>
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Home</span>
        </li>

        @if ($user->isEmployee())
          {{-- Menu khusus employee --}}
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
              <i class="ti ti-atom"></i>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('absen.riwayat') }}" aria-expanded="false">
              <i class="ti ti-history"></i>
              <span class="hide-menu">Riwayat Absen</span>
            </a>
          </li>
        @elseif ($user->isAdmin())
          {{-- Menu khusus admin --}}
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
              <i class="ti ti-atom"></i>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('absen.riwayat') }}" aria-expanded="false">
              <i class="ti ti-report"></i>
              <span class="hide-menu">Laporan Absen</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
              <i class="ti ti-edit"></i>
              <span class="hide-menu">Manajemen User</span>
            </a>
          </li>
        @endif
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->