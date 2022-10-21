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
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Pelanggran -->
    <li class="nav-item {{ request()->is('admin/pelanggaran') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('show.pelanggaran') }}">
            <i class="fas fa-users-slash"></i>
            <span>Pelanggaran Mahasiswa</span></a>
    </li>

    <li class="nav-item {{ request()->is('admin/skorsing') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('show.skorsing') }}">
            <i class="fas fa-ban"></i>
            <span>Skorsing Mahasiswa</span></a>
    </li>       

    <!-- Nav Item - Rekapan -->
    <li class="nav-item {{ request()->is('admin/rekapan') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('show.rekapan') }}">
            <i class="fas fa-download"></i>
            <span>Unduh Rekapan</span></a>
    </li>

    <li class="nav-item {{ request()->is('grafik/sv') || request()->is('grafik/prodi') ? 'active' : ''}}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData2"
            aria-expanded="true" aria-controls="collapseData2">
            <i class="fas fa-chart-pie"></i>
            <span>Grafik</span>
        </a>
        <div id="collapseData2" class="collapse" aria-labelledby="headingData" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('show.chart.sv') }}">Grafik SV</a>
                <a class="collapse-item" href="{{ route('show.chart.prodi') }}">Grafik Prodi</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>

@push('script')
    <script>
        $(document).ready(function() {


            var count_penundaan_lama = null;
            var count_penundaan_baru= null;
            var count_surat_lama = null;
            var count_surat_baru= null;
        
            function loadCount() {
                $.ajax({
                    url: "{!! route('ajax.count.notif') !!}",
                    method: "GET",
                    async: 'true',
                    dataType: "json",
                    success: function(data) {
                        $('#countLapor').html(data.countLapor)
                        $('#countVerifikasi').html(data.countVerifikasi)
                        $('#countPenundaan').html(data.countPenundaan)
                        $('#countSurat').html(data.countSurat)
                        
                        count_penundaan_baru = data.penundaan
                        count_surat_baru = data.surat
                    }
                });
            }

            loadCount();

            var penundaan = document.getElementById('alertPenundaan');
            var surat = document.getElementById('alertSurat');

            setInterval(function() {
                loadCount();
                if(count_penundaan_lama == null){
                    count_penundaan_lama = count_penundaan_baru
                }
                if (count_penundaan_baru > count_penundaan_lama){
                    if(penundaan){
                        $("#alertPenundaan").removeClass("d-none");
                    }
                }

                if(count_surat_lama == null){
                    count_surat_lama = count_surat_baru
                }
                if (count_surat_baru > count_surat_lama){
                    if(surat){
                        $("#alertSurat").removeClass("d-none");
                    }
                }
                
                // console.log([count_surat_lama,count_surat_baru])
            }, 5000);
        });
    </script>
@endpush
