<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion fixed-top" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
        </div>
        <div class="sidebar-brand-text mx-3">LAPOR KOMDISMA</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('/') ? 'active' : ''}}">
        <a class="nav-link " href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    
    <li class="nav-item {{ request()->is('mahasiswa/pelanggaran') || request()->is('mahasiswa/pelanggaran/detail') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('mahasiswa.show.pelanggaran') }}">
            <i class="fas fa-file"></i>
            <span>Pelanggaran</span></a>
    </li>
    <li class="nav-item {{ request()->is('surat-kelakuan-baik') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('add.surat.kelakuan.baik') }}">
            <i class="fas fa-download"></i>
            <span>Surat Kelakuan Baik</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>
