@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('show.skorsing') }}">Skorsing Mahasiswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Skorsing</li>
            </ol>
        </nav>

        <!-- Profile -->
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Profile</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center justify-content-center">
                            <img src="{{ asset('img/default-profile.png') }}" class="img-thumbnail border-0">
                            <ul class="text-center px-2 pt-3 profile">
                                <li>
                                    <h6 class="font-weight-bold">{{ $mahasiswa->nama }}</h6>
                                </li>
                                <li>
                                    <h6>{{ $mahasiswa->nim }}</h6>
                                </li>
                                <li>
                                    <h6>{{ $mahasiswa->prodi->kode }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 col-sm-12">
                <!-- Detail Skorsing -->
                @if ($jadwal != null)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-blue">
                            <h6 class="m-0 font-weight-bold">Detail Skorsing</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">Status
                                @switch($status)
                                    @case('Belum mengisi jadwal')
                                        <span class='badge badge-danger ml-3'>
                                            <div class='h6 m-0'>{{ $status }}</div>
                                        </span>
                                    @break

                                    @case('Skorsing belum dimulai')
                                        <span class='badge badge-secondary ml-3'>
                                            <div class='h6 m-0'>{{ $status }}</div>
                                        </span>
                                    @break

                                    @case('Mengajukan penundaan')
                                        <span class='badge badge-secondary ml-3'>
                                            <div class='h6 m-0'>{{ $status }}</div>
                                        </span>
                                    @break

                                    @case('Sedang diskors')
                                        <span class='badge badge-warning ml-3'>
                                            <div class='h6 m-0'>{{ $status }}</div>
                                        </span>
                                    @break

                                    @case('Selesai')
                                        <span class='badge badge-success ml-3'>
                                            <div class='h6 m-0'>{{ $status }}</div>
                                        </span>
                                    @break

                                    @default
                                @endswitch
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="tb_detailSkors" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Hari</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Mata Kuliah (MK)</th>
                                            <th>Dosen MK</th>
                                            <th>Koordinator MK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($jadwal == null or $status == 'Mengajukan penundaan')
                                            <tr>
                                                <td colspan="7" class="text-center">Data Kosong</td>
                                            </tr>
                                        @else
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($jadwal as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row->hari }}</td>
                                                    <td>{{ $row->tanggal_matkul }}</td>
                                                    <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                                    <td>{{ $row->matkul }}</td>
                                                    <td>{{ $row->nama_dosen }}</td>
                                                    <td>{{ $row->nama_koordinator }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
