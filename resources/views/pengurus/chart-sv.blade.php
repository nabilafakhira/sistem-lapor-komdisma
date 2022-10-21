@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Grafik</a></li>
                <li class="breadcrumb-item active" aria-current="page">Grafik Sekolah Vokasi</li>
            </ol>
        </nav>


        <!-- chart pelanggaran perbulan -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- chart pelanggaran perbulan berdasarkan kategori & jenis pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-row">
                    <label class="col-md-auto col-sm-12 col-form-label">Kategori Pelanggaran</label>
                    <div class="col-md-3 col-sm-12">
                        <select class="custom-select" name="kategoriP" id="kategoriP" required>
                            <option value="" selected disabled>Pilih...</option>
                            @foreach ($kategoripel as $k)
                                <option value="{{ $k->id }}">Pelanggaran {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-md-auto col-sm-12 col-form-label">Jenis Pelanggaran</label>
                    <div class="col-md-3 col-sm-12">
                        <select class="custom-select" name="jenisP" id="jenisP" required>
                            <option selected disabled value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="from-group col-md-auto mt-sm-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-block" id="btnKategori">Terapkan</button>
                    </div>
                </div>
                <div class="chart-bar">
                    <canvas id="pelanggaranKategori"></canvas>
                </div>
            </div>
        </div>

        <!-- chart pelanggaran perbulan berdasarkan lokasi-->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-auto col-sm-12 col-form-label">Lokasi Pelanggaran</label>
                    <div class="col-md-3 col-sm-12">
                        <select class="custom-select" name="lokasi" id="lokasi" required>
                            <option selected disabled value="">Pilih...</option>
                            @foreach ($lokasi as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="from-group col-md-auto mt-sm-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-block" id="btnLokasi">Terapkan</button>
                    </div>
                </div>
                <div class="chart-bar">
                    <canvas id="pelanggaranLokasi"></canvas>
                </div>
            </div>
        </div>

        <!-- chart pelanggaran perbulan berdasarkan sanksi-->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-auto col-sm-12 col-form-label">Sanksi</label>
                    <div class="col-md-3 col-sm-12">
                        <select class="custom-select" name="sanksi" id="sanksi" required>
                            <option selected disabled value="">Pilih...</option>
                            @foreach ($sanksi as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="from-group col-md-auto mt-sm-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-block" id="btnSanksi">Terapkan</button>
                    </div>
                </div>
                <div class="chart-bar">
                    <canvas id="pelanggaranSanksi"></canvas>
                </div>
            </div>

        </div>
    @endsection

    @push('script')
        <script type="text/javascript">
            $('#kategoriP').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: '{!! route('json.jenispel') !!}',
                    method: "POST",
                    data: {
                        id: id,
                        _token: '{!! csrf_token() !!}'
                    },
                    async: true,
                    dataType: 'json',
                    success: function(data) {
                        var html = '<option selected disabled value="">Pilih...</option>';
                        $('#jenisP').html(html + data);

                    }
                });
                return false;
            });
            window.onload = function() {
                getMonthlyChart()
                getChartByKategori()
                getChartByLokasi()
                getChartBySanksi()
            }

            setInterval(function() {
                getMonthlyChart()
                getChartByKategori()
                getChartByLokasi()
                getChartBySanksi()
            }, 500000);

            $('#btnKategori').click(getChartByKategori);
            $('#btnLokasi').click(getChartByLokasi);
            $('#btnSanksi').click(getChartBySanksi);

            function getMonthlyChart() {
                $.ajax({
                    url: "{!! route('getMonthlyChart') !!}",
                    method: "GET",
                    data: {
                        _token: '{!! csrf_token() !!}'
                    },
                    async: 'true',
                    dataType: "json",
                    success: function(data) {
                        // console.log(data)

                        var ctx = document.getElementById("myBarChart");
                        var myBarChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.bulan,
                                datasets: [{
                                    label: "Total Pelanggaran",
                                    backgroundColor: "#6610f2",
                                    data: data.jumlah,
                                    maxBarThickness: 50,
                                }],
                            },
                            options: {
                                tooltips: {
                                    enabled: true
                                },
                                hover: {
                                    animationDuration: 1
                                },
                                animation: {
                                    onComplete: function() {
                                        var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;
                                        ctx.textAlign = 'center';
                                        ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
                                        ctx.textBaseline = 'bottom';
                                        // Loop through each data in the datasets
                                        this.data.datasets.forEach(function(dataset, i) {
                                            var meta = chartInstance.controller.getDatasetMeta(
                                                i);
                                            meta.data.forEach(function(bar, index) {
                                                var data = dataset.data[index];
                                                ctx.fillText(data, bar._model.x, bar
                                                    ._model
                                                    .y - 5);
                                            });
                                        });
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Total Pelanggaran Mahasiswa Per Bulan",
                                    padding: 30,
                                },
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 0,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'Bulan'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            userCallback: function(label, index, labels) {
                                                // when the floored value is the same as the value we have a whole number
                                                if (Math.floor(label) === label) {
                                                    return label;
                                                }
                                            }
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                            }
                        });
                    }
                });
            }

            function getChartByKategori() {
                $.ajax({
                    url: "{!! route('getChartByKategori') !!}",
                    method: "POST",
                    data: {
                        kategoriP: $('#kategoriP').val(),
                        jenisP: $('#jenisP').val(),
                        _token: '{!! csrf_token() !!}'
                    },
                    async: 'true',
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);

                        var ctx = document.getElementById("pelanggaranKategori");
                        var pelanggaranKategori = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.bulan,
                                datasets: [{
                                    label: "Total Pelanggaran",
                                    backgroundColor: "#e83e8c",
                                    data: data.jumlah,

                                    maxBarThickness: 50,
                                }],
                            },
                            options: {
                                tooltips: {
                                    enabled: true
                                },
                                hover: {
                                    animationDuration: 1
                                },
                                animation: {
                                    onComplete: function() {
                                        var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;
                                        ctx.textAlign = 'center';
                                        ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
                                        ctx.textBaseline = 'bottom';
                                        // Loop through each data in the datasets
                                        this.data.datasets.forEach(function(dataset, i) {
                                            var meta = chartInstance.controller.getDatasetMeta(
                                                i);
                                            meta.data.forEach(function(bar, index) {
                                                var data = dataset.data[index];
                                                ctx.fillText(data, bar._model.x, bar
                                                    ._model.y - 5);
                                            });
                                        });
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Total Pelanggaran Mahasiswa Per Bulan dengan Kategori Pelanggaran " +
                                        data.kategori + " dan Jenis Pelanggaran " + data.jenis,
                                    padding: 30,
                                },
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 5,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'Bulan'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            userCallback: function(label, index, labels) {
                                                // when the floored value is the same as the value we have a whole number
                                                if (Math.floor(label) === label) {
                                                    return label;
                                                }
                                            }
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                            }
                        });
                    }
                });
            };

            function getChartByLokasi() {
                $.ajax({
                    url: "{!! route('getChartByLokasi') !!}",
                    method: "POST",
                    data: {
                        lokasi: $('#lokasi').val(),
                        _token: '{!! csrf_token() !!}'
                    },
                    async: 'true',
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);

                        var ctx = document.getElementById("pelanggaranLokasi");
                        var pelanggaranLokasi = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.bulan,
                                datasets: [{
                                    label: "Total Pelanggaran",
                                    backgroundColor: "#fd7e14",
                                    data: data.jumlah,
                                    maxBarThickness: 50,
                                }],
                            },
                            options: {
                                tooltips: {
                                    enabled: true
                                },
                                hover: {
                                    animationDuration: 1
                                },
                                animation: {
                                    onComplete: function() {
                                        var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;
                                        ctx.textAlign = 'center';
                                        ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
                                        ctx.textBaseline = 'bottom';
                                        // Loop through each data in the datasets
                                        this.data.datasets.forEach(function(dataset, i) {
                                            var meta = chartInstance.controller.getDatasetMeta(
                                                i);
                                            meta.data.forEach(function(bar, index) {
                                                var data = dataset.data[index];
                                                ctx.fillText(data, bar._model.x, bar
                                                    ._model.y - 5);
                                            });
                                        });
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Total Pelanggaran Mahasiswa Per Bulan di " + data.lokasi,
                                    padding: 30,
                                },
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 0,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'Bulan'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            userCallback: function(label, index, labels) {
                                                // when the floored value is the same as the value we have a whole number
                                                if (Math.floor(label) === label) {
                                                    return label;
                                                }
                                            }
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                            }
                        });
                    }
                });
            };

            function getChartBySanksi() {
                $.ajax({
                    url: "{!! route('getChartBySanksi') !!}",
                    method: "POST",
                    data: {
                        sanksi: $('#sanksi').val(),
                        _token: '{!! csrf_token() !!}'
                    },
                    async: 'true',
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);

                        var ctx = document.getElementById("pelanggaranSanksi");
                        var pelanggaranSanksi = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.bulan,
                                datasets: [{
                                    label: "Total Pelanggaran",
                                    backgroundColor: "#20c9a6",
                                    data: data.jumlah,
                                    maxBarThickness: 50,
                                }],
                            },
                            options: {
                                tooltips: {
                                    enabled: true
                                },
                                hover: {
                                    animationDuration: 1
                                },
                                animation: {
                                    onComplete: function() {
                                        var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;
                                        ctx.textAlign = 'center';
                                        ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
                                        ctx.textBaseline = 'bottom';
                                        // Loop through each data in the datasets
                                        this.data.datasets.forEach(function(dataset, i) {
                                            var meta = chartInstance.controller.getDatasetMeta(
                                                i);
                                            meta.data.forEach(function(bar, index) {
                                                var data = dataset.data[index];
                                                ctx.fillText(data, bar._model.x, bar
                                                    ._model.y - 5);
                                            });
                                        });
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Total Pelanggaran Mahasiswa Per Bulan dengan Sanksi "+data.sanksi,
                                    padding: 30,
                                },
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 0,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'Bulan'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            userCallback: function(label, index, labels) {
                                                // when the floored value is the same as the value we have a whole number
                                                if (Math.floor(label) === label) {
                                                    return label;
                                                }
                                            }
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                            }
                        });
                    }
                });
            };

        </script>
    @endpush
