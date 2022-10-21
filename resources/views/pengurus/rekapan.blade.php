@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Unduh Rekapan Data</li>
            </ol>
        </nav>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form>
                    <div class="form-row align-items-center justify-content-start">
                        <div class="col-md-auto col-sm-12 pt-2">
                            <h6>Filter</h6>
                        </div>
                        <div class="col-md-2 col-sm-12 py-1">
                            <select class="form-control" id="prodi">
                                <option value="" selected>Semua Prodi</option>
                                @foreach ($prodi as $row)
                                    <option value="{{ $row->kode }}">{{ $row->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="kategori" id="kategori">
                                <option selected value="">Semua Kategori Pelanggaran</option>
                                @foreach ($kategori as $row)
                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="jenis" id="jenis">
                                <option selected value="">Semua Jenis Pelanggaran</option>
                                @foreach ($jenis as $row)
                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="lokasi" id="lokasi">
                                <option value="" selected>Semua Lokasi</option>
                                @foreach ($lokasi as $row)
                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="sanksi" id="sanksi">
                                <option value="" selected>Semua Sanksi</option>
                                @foreach ($sanksi as $row)
                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 py-1">
                            <select class="form-control" id="status">
                                <option value="" selected>Semua Status</option>
                                <option value="Menunggu verifikasi">Menunggu verifikasi</option>
                                <option value="Sedang diskors">Sedang diskors</option>
                                <option value="Proses">Proses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Drop Out">Drop Out</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 py-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control fix-rounded-right" id="datesearch"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableRekapan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Kategori Pelanggaran</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Lokasi</th>
                                <th>Pelapor</th>
                                <th>Sanksi</th>
                                <th>Inspektur</th>
                                <th>Tanggal Surat Bebas Lapor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td class="align-middle">{{ $row->tanggal }}</td>
                                    <td class="align-middle">{{ $row->nama }}</td>
                                    <td class="align-middle">{{ $row->nim }}</td>
                                    <td class="align-middle">{{ $row->prodi }}</td>
                                    <td class="align-middle">{{ $row->nama_kategori }}</td>
                                    <td class="align-middle">{{ $row->nama_jenis }}</td>
                                    <td class="align-middle">{{ $row->nama_lokasi }}</td>
                                    <td class="align-middle">{{ $row->nama_pelapor }}</td>
                                    <td class="align-middle">{{ !empty($row->nama_sanksi) ? $row->nama_sanksi : '-' }}</td>
                                    <td class="align-middle">
                                        {{ !empty($row->nama_inspektur) ? $row->nama_inspektur : '-' }}</td>
                                    <td class="align-middle">
                                        {{ !empty($row->tgl_surat_bebas) ? $row->tgl_surat_bebas : '-' }}</td>
                                    <td class="align-middle">
                                        @switch($row->status)
                                            @case('Menunggu')
                                                <span class="badge badge-outline-danger">Menunggu verifikasi</span>
                                            @break

                                            @case('Drop Out')
                                                <span class="badge badge-outline-danger">Drop Out</span>
                                            @break

                                            @case('Proses')
                                                <span class="badge badge-outline-warning">Proses</span>
                                            @break

                                            @case('Sedang diskors')
                                                <span class="badge badge-outline-warning">Sedang diskors</span>
                                            @break

                                            @case('Selesai')
                                                <span class="badge badge-outline-success">Selesai</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "date-uk-pre": function(a) {
                var ukDatea = a.split('/');
                return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
            },

            "date-uk-asc": function(a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "date-uk-desc": function(a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });

        //filter rekapan
        var start_date;
        var end_date;
        var DateFilterFunction = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);
            //Kolom tanggal yang akan kita gunakan berada dalam urutan 2, karena dihitung mulai dari 0
            //nama depan = 0
            //nama belakang = 1
            //tanggal terdaftar =2
            var evalDate = parseDateValue(aData[0]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
        function parseDateValue(rawDate) {
            var dateArray = rawDate.split("/");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
            0]); // -1 because months are from 0 to 11   
            return parsedDate;
        }

        var tableRekapan = $('#tableRekapan').DataTable({
            'responsive': true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            renderer: 'bootstrap',
            buttons: [{
                extend: 'excel',
                text: 'Unduh Excel',
                className: 'btn btn-success',
                title: 'Data Pelanggaran Mahasiswa SV IPB'
            }, ],
            "aoColumns": [{
                "sType": "date-uk"
            }, null, null, null, null, null, null, null, null, null, {
                "sType": "date-uk"
            }, null]
        });

        //konfigurasi daterangepicker pada input dengan id datesearch
        $('#datesearch').daterangepicker({
            autoUpdateInput: false
        });

        //menangani proses saat apply date range
        $('#datesearch').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            start_date = picker.startDate.format('DD/MM/YYYY');
            end_date = picker.endDate.format('DD/MM/YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            tableRekapan.draw();
        });

        $('#datesearch').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            tableRekapan.draw();
        });

        $('#prodi').on('change', function() {
            tableRekapan.columns(3).search(this.value).draw();
        });
        $('#kategori').on('change', function() {
            tableRekapan.columns(4).search(this.value).draw();
        });
        $('#jenis').on('change', function() {
            tableRekapan.columns(5).search(this.value).draw();
        });
        $('#lokasi').on('change', function() {
            tableRekapan.columns(6).search(this.value).draw();
        });
        $('#sanksi').on('change', function() {
            tableRekapan.columns(8).search(this.value).draw();
        });
        $('#status').on('change', function() {
            tableRekapan.columns(11).search(this.value).draw();
        });
    </script>
@endpush
