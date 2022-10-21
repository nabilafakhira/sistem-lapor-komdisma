@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('show.pelanggaran') }}">Pelanggaran Mahasiswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pelanggaran Mahasiswa</li>
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
                            <ul class="text-center px-0 pt-3 profile">
                                <li>
                                    <h6 class="font-weight-bold">{{ $pelanggaran->nama_mahasiswa }}</h6>
                                </li>
                                <li>
                                    <h6>{{ $pelanggaran->nim }}</h6>
                                </li>
                                <li>
                                    <h6>{{ $pelanggaran->kode_prodi }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 col-sm-12">
                <!-- Detail Pelanggaran -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Detail Pelanggaran</h6>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post">
                            <input type="hidden" class="form-control" value="{{ $pelanggaran->id_pelanggaran }}"
                                name="id_pelanggaran">
                            <div class="form-row">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Tingkat</label>
                                    <input type="text" class="form-control" disabled
                                        value="Tingkat {{ $pelanggaran->tingkat }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Tanggal Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->tanggal_lengkap }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Jam</label>
                                    <input type="text" class="form-control" disabled value="{{ $pelanggaran->jam }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Kategori Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="Pelanggaran {{ $pelanggaran->nama_kategori }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Jenis Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->nama_jenis }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Lokasi</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->nama_lokasi }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="2" disabled>{{ $pelanggaran->keterangan }}</textarea>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="exampleInputEmail1">Pelapor</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->nama_pelapor }}">
                                </div>
                            </div>
                            @if (!empty($pelanggaran->sanksi_id) and !empty($pelanggaran->inspektur))
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="exampleInputEmail1">Sanksi</label>
                                        <input type="text" class="form-control" name="keterangan" disabled
                                            value="{{ $pelanggaran->nama_sanksi }}{{ !empty($pelanggaran->lapor) ? ' - ' . $pelanggaran->lapor . 'x lapor' : '' }}{{ !empty($pelanggaran->skorsing) ? ' - ' . $pelanggaran->skorsing . ' hari skorsing' : '' }}{{ $pelanggaran->drop_out == 1 ? ' - Drop Out' : '' }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="exampleInputEmail1">Inspektur</label>
                                        <input type="text" class="form-control" disabled
                                            value="{{ $pelanggaran->nama_inspektur }}">
                                    </div>
                                </div>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <div class="align-middle">Status
                                        @switch($pelanggaran->status)
                                            @case('Menunggu')
                                                <span class="badge badge-danger ml-3">
                                                    <div class="h6 m-0">Menunggu verifikasi</div>
                                                </span>
                                            @break

                                            @case('Drop Out')
                                                <span class="badge badge-danger ml-3">
                                                    <div class="h6 m-0">Drop Out</div>
                                                </span>
                                            @break

                                            @case('Belum mengisi jadwal')
                                                <span class="badge badge-danger ml-3">
                                                    <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                                                </span>
                                            @break

                                            @case('Proses')
                                                <span class="badge badge-warning ml-3">
                                                    <div class="h6 m-0">Proses</div>
                                                </span>
                                            @break

                                            @case('Sedang diskors')
                                                <span class="badge badge-warning ml-3">
                                                    <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                                                </span>
                                            @break

                                            @case('Selesai')
                                                <span class="badge badge-success ml-3">
                                                    <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                                                </span>
                                            @break

                                            @default
                                        @endswitch
                                    </div>
                                </div>
                                @if ($pelanggaran->tgl_surat_bebas == null)
                                    @if ($pelanggaran->status != 'Selesai' and $pelanggaran->status != 'Drop Out')
                                        <div class="form-group col-md-12 col-sm-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input loloskan"
                                                    id="loloskanPelanggaran">
                                                <label class="custom-control-label" for="loloskanPelanggaran">Loloskan
                                                    pelanggaran ini</label>
                                                <a href="{{ route('loloskan.pelanggaran', $pelanggaran->id_pelanggaran) }}"
                                                    id="loloskan"></a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group col-md-12 col-sm-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input loloskan"
                                                    id="loloskanPelanggaran" disabled checked>
                                                <label class="custom-control-label" for="loloskanPelanggaran">Loloskan
                                                    pelanggaran ini</label>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="form-group col-md-12 col-sm-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input loloskan"
                                                id="loloskanPelanggaran" disabled checked>
                                            <label class="custom-control-label" for="loloskanPelanggaran">Loloskan
                                                pelanggaran ini</label>
                                        </div>
                                    </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Lapor Pelanggaran -->
    @if (!$lapor->isEmpty())
        <div class="row justify-content-end">
            <div class="col-md-10 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Detail Lapor</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="row">No</th>
                                        <th>Tanggal Lapor</th>
                                        <th>Penerima Lapor</th>
                                        <th>Keterangan</th>
                                        <th>Status Lapor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($lapor as $l)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $l->tanggal }}</td>
                                            <td>{{ $l->nama_penerima_lapor }}</td>
                                            <td>{{ empty($l->keterangan) ? '-' : $l->keterangan }}</td>
                                            <td>
                                                @if ($l->status == 1)
                                                    <span class="badge badge-outline-success">Diterima</span>
                                                @else
                                                    <span class="badge badge-outline-warning">Proses</span>
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
        </div>
    @endif
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('input[type="checkbox"].loloskan').on('change', function(e) {
                if (e.target.checked) {
                    e.preventDefault();
                    const link = $('#loloskan').attr('href');

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger mr-3'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: 'Apakah anda yakin?',
                        text: "Status pelanggaran akan berubah menjadi selesai dan mahasiswa dapat mengunduh surat keterangan bebas lapor",
                        icon: 'warning',
                        position: 'top',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Tidak, batalkan',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.location.href = link;
                        } else {
                            $('input[type="checkbox"].loloskan').prop('checked', false);
                        }
                    })
                }
            });
        });
    </script>
@endpush
