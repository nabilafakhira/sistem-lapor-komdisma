@extends('main')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Dashboard {{ ucwords(str_replace("-", " ", $user->role)) }}</h1>
        @if ($user->ttd == null && ($user->role == "admin" || $user->role == 'super-admin'))
        <div class="alert alert-danger shadow" role="alert">
            <div class="d-flex align-items-center" href="#">
                <div class="mr-3">
                    <i class='bx bx-info-circle h2 mb-0'></i>
                </div>
                <div>
                    <span class="h6 mb-0">Anda belum mengunggah foto tanda tangan. Segara <a href="{{ route('show.edit.akun.pengurus') }}" class="text-danger alert-link">unggah</a> foto tanda tangan anda!</span>
                </div>
            </div>
        </div>
        @endif
        <!-- card -->
        <div class="row">
            <div class="col-sm-3 mb-3">
                <div class="card bg-indigo">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-ban mr-2"></i>{{ $countPelanggaran }}</h2>
                        <p class="card-text">Total Pelanggaran</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 mb-3">
                <div class="card bg-teal">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-user-check mr-2"></i>{{ $countStatus[1] }}</h2>
                        <p class="card-text">Pelanggaran Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 mb-3">
                <div class="card bg-yellow">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-user-clock mr-2"></i>{{ $countStatus[0] }}</h2>
                        <p class="card-text">Pelanggaran Belum Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 mb-3">
                <div class="card bg-pink">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-user-slash mr-2"></i>{{ $countPelanggar }}</h2>
                        <p class="card-text">Total Mahasiswa Pelanggar</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Grafik-->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <!-- Bar Chart -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Total Pelanggaran dalam 6 Bulan Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Donut Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4 ">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Total Status Pelanggaran</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body ">
                        <div class="chart-bar py-3">
                            <canvas id="TotalPelanggaran"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <!-- Bar Chart -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Total Pelanggaran Prodi</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="semuaProdi"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $.holdReady(true);
        $.getJSON("{!! route('chart.json') !!}", function(data) {
            // console.log(data);
            var ctx = document.getElementById("myBarChart");
            var myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data['countLastPelanggaran'][0],
                    datasets: [{
                        label: "Total Pelanggaran",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                        data: data['countLastPelanggaran'][1],
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
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
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

            var ctx = document.getElementById("TotalPelanggaran");
            var TotalPelanggaran = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Selesai", "Belum Selesai"],
                    datasets: [{
                        data: [data['countStatus'][1], data['countStatus'][0]],
                        backgroundColor: ['#1cc88a', '#e74a3b'],
                        hoverBackgroundColor: ['#17a673', '#aa1f13'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    cutoutPercentage: 60,
                },
            });

            var ctx = document.getElementById("semuaProdi");
            var semuaProdi = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data["pelanggaranProdi"][0],
                    datasets: [{
                        label: "Total Pelanggaran",
                        backgroundColor: "#6610f2",
                        data: data["pelanggaranProdi"][1],
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
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
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

            $.holdReady(false);
        });
    </script>
@endpush
