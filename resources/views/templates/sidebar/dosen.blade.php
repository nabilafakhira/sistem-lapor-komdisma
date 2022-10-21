<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion fixed-top" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
        </div>
        <div class="sidebar-brand-text mx-3">LAPOR KOMDISMA</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item ">
        <a class="nav-link btn-pelanggaran" href="#" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus"></i>
            <span>Tambah Pelanggaran</span></a>

    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item ">
        <a class="nav-link " href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('show.laporan') }}">
            <i class="fas fa-clipboard-check"></i>
            <span>Terima Laporan</span>
            <span id="countLapor"></span>
        </a>
    </li>

</ul>

@push('script')
    <script>
        $(document).ready(function() {
            function loadCount() {
                $.ajax({
                    url: "{!! route('ajax.count.notif') !!}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#countLapor').html(data.countLapor)
                    }
                });
            }

            loadCount();

            setInterval(function() {
                loadCount(); // user paging is not reset on reload
            }, 10000);
            
        });
    </script>
@endpush
