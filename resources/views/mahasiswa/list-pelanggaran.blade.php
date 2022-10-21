@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pelanggaran</li>
            </ol>
        </nav>
        <div class="card shadow mb-4">
            <div class="card-header bg-blue">
                <h6 class="m-0 font-weight-bold">Daftar Pelanggaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped"  width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Kategori Pelanggaran</th>
                                <th scope="col">Jenis Pelanggaran</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$pelanggaran->isEmpty())
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($pelanggaran as $row)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row->tanggal }}</td>
                                        <td>{{ $row->nama_lokasi }}</td>
                                        <td>{{ $row->nama_kategori }}</td>
                                        <td>{{ $row->nama_jenis }}</td>
                                        <td>{{ $row->keterangan }}</td>
                                        <td>
                                            @switch($row->status)
                                                @case('Menunggu')
                                                    <span class="badge badge-outline-danger">Menunggu
                                                        verifikasi</span>
                                                @break

                                                @case('Drop Out')
                                                    <span class="badge badge-outline-danger">Drop
                                                        Out</span>
                                                @break

                                                @case('Belum mengisi jadwal')
                                                    <span class="badge badge-outline-danger">{{ $row->status }}</span>
                                                @break

                                                @case('Proses')
                                                    <span class="badge badge-outline-warning">Proses</span>
                                                @break

                                                @case('Sedang diskors')
                                                    <span class="badge badge-outline-warning">Sedang
                                                        diskors</span>
                                                @break

                                                @case('Selesai')
                                                    <span class="badge badge-outline-success">Selesai</span>
                                                @break

                                                @default
                                            @endswitch
                                        </td>
                                        <td>
                                            <form action="{{ route('mahasiswa.detail.pelanggaran') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $row->id_pelanggaran }}" name="id">
                                                <button type="submit"
                                                    class="btn rounded-circle btn-outline-primary btn-sm"><i
                                                        class="fas fa-search"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada pelanggaran</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
