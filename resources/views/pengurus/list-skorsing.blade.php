@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
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
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control" id="prodi">
                                <option value="" selected>Semua Prodi</option>
                                @foreach ($prodi as $row)
                                    <option value="{{ $row->kode }}">{{ $row->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control" id="status">
                                <option value="" selected>Semua Status</option>
                                <option value="Belum mengisi jadwal">Belum mengisi jadwal</option>
                                <option value="Skorsing belum dimulai">Skorsing belum dimulai</option>
                                <option value="Mengajukan penundaan">Mengajukan penundaan</option>
                                <option value="Sedang diskors">Sedang diskors</option>
                                <option value="Skorsing Selesai">Skorsing selesai</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableSkorsing" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Id Pelanggaran</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Tanggal Berakhir</th>
                                <th>Lama Skorsing</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skorsing as $row)
                                <tr>
                                    <td class="align-middle"></td>
                                    <td>
                                        <form action="{{ route('detail.pelanggaran') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $row->id_pelanggaran }}" name="id">
                                            <button class="btn btn-link ">{{ $row->id_pelanggaran }}</button>
                                        </form>
                                    </td>
                                    <td class="align-middle">{{ $row->nama_mahasiswa }}</td>
                                    <td class="align-middle">{{ $row->prodi }}</td>
                                    <td class="align-middle">
                                        {{ !empty($row->tgl_berakhir) ? $row->tgl_berakhir : '00/00/0000' }}</td>
                                    <td class="align-middle">{{ $row->jum_hari }} Hari</td>
                                    <td class="align-middle">
                                        @switch($row->status)
                                            @case('Belum mengisi jadwal')
                                                <span class='badge badge-outline-danger'>{{ $row->status }}</span>
                                            @break

                                            @case('Skorsing belum dimulai')
                                                <span class='badge badge-outline-secondary'>{{ $row->status }}</span>
                                            @break

                                            @case('Mengajukan penundaan')
                                                <span class='badge badge-outline-secondary'>{{ $row->status }}</span>
                                            @break

                                            @case('Sedang diskors')
                                                <span class='badge badge-outline-warning'>{{ $row->status }}</span>
                                            @break

                                            @case('Selesai')
                                                <span class='badge badge-outline-success'>Skorsing selesai</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td class="align-middle">
                                        @if ($row->status != 'Belum mengisi jadwal' and $row->status != 'Mengajukan penundaan')
                                            <form action="{{ route('detail.skorsing') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $row->id_pelanggaran }}" name="id">
                                                <input type="hidden" value="{{ $row->nim_mahasiswa }}" name="nim">
                                                <button type="submit"
                                                    class="btn rounded-circle btn-outline-primary btn-sm"><i
                                                        class="fas fa-search"></i></button>
                                            </form>
                                        @else
                                            <button class="btn rounded-circle btn-outline-secondary btn-sm" disabled><i
                                                    class="fas fa-search"></i></button>
                                        @endif
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
        $(document).ready(function() {

            var tableSkorsing = $('#tableSkorsing').DataTable({
                "responsive": true,
                "columnDefs": [{
                    "targets": 4,
                    "sType": "date-uk"
                }, {
                    "targets": [0, -1],
                    "orderable": false
                }],
                dom: "<'row'<'col-sm-12 col-md-4'l><'text-right col-sm-12 col-md-6 ml-auto'f><'col-sm-12 col-md-auto mx-0 px-0'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                renderer: 'bootstrap',
                buttons: [{
                    text: 'Reload Table',
                    className: 'btn btn-secondary btn-sm btnreload',
                    action: function(e, dt, node, config) {
                        location.reload()
                    }
                }, ],
            });

            tableSkorsing.on('order.dt search.dt', function() {
                tableSkorsing.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            $('#prodi').on('change', function() {
                tableSkorsing.columns(3).search(this.value).draw();
            });
            $('#status').on('change', function() {
                tableSkorsing.columns(6).search(this.value).draw();
            });
        });
    </script>
@endpush
