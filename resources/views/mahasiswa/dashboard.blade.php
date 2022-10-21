@extends('main')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Dashboard Mahasiswa</h1>
        @if ($alertLapor == true)
            @if ($alertSkors == true)
                <div class="alert alert-danger shadow" role="alert">
                    <div class="d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <i class='bx bx-info-circle h2 mb-0'></i>
                        </div>
                        <div>
                            <span class="font-weight-bold h6 mb-0">Anda sedang diskors hari ini!</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning shadow" role="alert">
                    <div class="d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <i class='bx bx-info-circle h2 mb-0'></i>
                        </div>
                        <div>
                            <span class="font-weight-bold h6 mb-0">Anda belum melakukan lapor hari ini!</span>
                            <div>Sanksi berikutnya akan diberikan bila lapor tidak diselesaikan tepat waktu</div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Detail Content -->
        <!-- card -->
        <div class="row">
            <div class="col-sm-4 mb-3">
                <div class="card bg-indigo">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-ban mr-2"></i>{{ $countPelanggaran }}</h2>
                        <p class="card-text">Total Pelanggaran</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="card bg-teal">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-user-check mr-2"></i>{{ $countStatus[1] }}</h2>
                        <p class="card-text">Pelanggaran Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="card bg-yellow">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-user-clock mr-2"></i>{{ $countStatus[0] }}</h2>
                        <p class="card-text">Pelanggaran Belum Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($kemajuanLapor != null)
            @foreach ($kemajuanLapor as $key => $value)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div>
                                    <span class="small text-gray-500">Kemajuan lapor untuk pelanggaran ditanggal
                                        {{ $kemajuanLapor[$key][0] }}</span>
                                    <div class="progress mt-1">
                                        <div class="progress-bar bg-primary" id="kemajuanLapor{{ $key }}"
                                            role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                            style="width: {{ $kemajuanLapor[$key][1] }}%;">{{ $kemajuanLapor[$key][1] }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="card">
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
        </div>
    @endsection

    @push('script')
        <script type="text/javascript">
            $.holdReady(true);
            $.getJSON("{!! route('mahasiswa.chart.json') !!}", function(data) {
                // console.log(data);
                var ctx = document.getElementById("myBarChart");
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data["pelanggaranTerakhir"][0],
                        datasets: [{
                            label: "Total Pelanggaran",
                            backgroundColor: "#e83e8c",
                            data: data["pelanggaranTerakhir"][1],
                            maxBarThickness: 50,
                        }],
                    },
                    options: {
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
                            position: 'top'
                        },
                    }
                });

                $.holdReady(false);
            });

            function kemajuanLapor() {
                $.ajax({
                    url: "{!! route('mahasiswa.chart.json') !!}",
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.kemajuanLapor) {
                            $.each(data.kemajuanLapor, function(key, value) {
                                $('#kemajuanLapor' + key).html(data.kemajuanLapor[key][1] + "%");
                                $('#kemajuanLapor' + key).css('width', data.kemajuanLapor[key][1] + "%");
                            })
                        }
                    }
                });
            }

            setInterval(function() {
                // console.log(kemajuanLapor())
                kemajuanLapor(); // user paging is not reset on reload
            }, 5000);

            // });
        </script>
    @endpush
