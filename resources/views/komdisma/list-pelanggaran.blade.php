@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pelanggaran Mahasiswa</li>
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
                            <select class="form-control" id="prodi" name="prodi">
                                <option value="" selected>Semua Prodi</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode }}">{{ $p->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="kategori" id="kategori">
                                <option selected value="">Semua Kategori Pelanggaran</option>
                                @foreach ($kategoripel as $k)
                                    <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control" name="jenis" id="jenis">
                                <option selected value="">Semua Jenis Pelanggaran</option>
                                @foreach ($jenispel as $j)
                                    <option value="{{ $j->nama }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2  col-sm-12 py-1">
                            <select class="form-control" name="lokasi" id="lokasi">
                                <option value="" selected>Semua Lokasi</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->nama }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2  col-sm-12 py-1">
                            <select class="form-control" name="sanksi" id="sanksi">
                                <option value="" selected>Semua Sanksi</option>
                                @foreach ($sanksi as $s)
                                    <option value="{{ $s->nama }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control" id="status">
                                <option value="" selected>Semua Status</option>
                                <option value="Menunggu verifikasi">Menunggu verifikasi</option>
                                <option value="Sedang diskors">Sedang diskors</option>
                                <option value="Proses">Proses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Drop Out">Drop Out</option>
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
                    <table class="table table-striped" id="tableData" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Tanggal Pelanggaran</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Kategori Pelanggaran</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Lokasi</th>
                                <th>Sanksi</th>
                                <th>Status</th>
                                @if ($user->role == 'super-admin' or $user->role == 'admin')
                                    <th>Detail</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggaran as $p)
                                <tr>
                                    <td class="align-middle"></td>
                                    <td class="align-middle">{{ $p->tanggal }}</td>
                                    <td class="align-middle">{{ $p->nama_mahasiswa }}</td>
                                    <td class="align-middle">{{ $p->prodi }}</td>
                                    <td class="align-middle">{{ $p->nama_kategori }}</td>
                                    <td class="align-middle">{{ $p->nama_jenis }}</td>
                                    <td class="align-middle">{{ $p->nama_lokasi }}</td>
                                    <td class="align-middle">
                                        {{ empty($p->nama_sanksi) ? 'Perlu verifikasi' : $p->nama_sanksi }}
                                    </td>
                                    <td class="align-middle">
                                        @switch($p->status)
                                            @case('Menunggu')
                                                <span class="badge badge-outline-danger">Menunggu verifikasi</span>
                                            @break

                                            @case('Belum mengisi jadwal')
                                                <span class='badge badge-outline-warning'>Proses</span>
                                            @break

                                            @case('Proses')
                                                <span class='badge badge-outline-warning'>Proses</span>
                                            @break

                                            @case('Sedang diskors')
                                                <span class='badge badge-outline-warning'>Proses</span>
                                            @break

                                            @case('Selesai')
                                                <span class='badge badge-outline-success'>Selesai</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    @if ($user->role == 'super-admin' or $user->role == 'admin')
                                        <td class="align-middle">
                                            <form action="{{ route('detail.pelanggaran') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="<?= $p->id_pelanggaran ?>" name="id">
                                                <button type="submit"
                                                    class="btn rounded-circle btn-outline-primary btn-sm"><i
                                                        class="fas fa-search"></i></button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
