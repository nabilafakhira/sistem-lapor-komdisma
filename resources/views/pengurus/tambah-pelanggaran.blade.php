@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </nav>

        <!-- Tambah Pelanggaran Mahasiswa -->
        <div class="card shadow mb-4">
            <div class="card-header bg-blue">
                <h6 class="m-0 font-weight-bold">Tambah Pelanggaran</h6>
            </div>
            <div class="card-body">
                <form id="formPelanggaran" method="POST" enctype="multipart/form-data"
                    action="{{ route('save.pelanggaran') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">NIM</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->nim : old('nim') }}" disabled>
                            <input type="hidden" class="form-control" name="nim"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->nim : old('nim') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Nama</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->nama : old('nama') }}" disabled>
                            <input type="hidden" class="form-control" name="nama"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->nama : old('nama') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Program Studi</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->prodi->nama : old('prodi') }}" disabled>
                            <input type="hidden" class="form-control" name="prodi"
                                value="{{ !empty($mahasiswa) ? $mahasiswa->prodi->nama : old('prodi') }}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Tanggal Pelanggaran</label>
                        <div class="form-group col-md-auto">
                            <input type="date" format="yy-mm-dd" class="form-control" name="tanggal" id="tanggal">
                            <div class='invalid-feedback' id="feedbacktanggal"></div>
                        </div>
                        <label class="col-md-auto col-form-label text-md-right text-sm-left">Jam Pelanggaran</label>
                        <div class="form-group col-md-2">
                            <input type="time" class="form-control" name="jam" id="jam">
                            <div class='invalid-feedback' id="feedbackjam"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Tingkat Mahasiswa</label>
                        <div class="col-md-6">
                            <select class="custom-select" name="tingkat" id="tingkat">
                                <option selected disabled>Pilih...</option>
                                <option value="1">Tingkat 1</option>
                                <option value="2">Tingkat 2</option>
                                <option value="3">Tingkat 3</option>
                                <option value="4">Tingkat 4</option>
                            </select>
                            <div class='invalid-feedback' id="feedbacktingkat"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Lokasi Pelanggaran</label>
                        <div class="col-md-6">
                            <select class="custom-select" name="lokasi" id="lokasi">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                            <div class='invalid-feedback' id="feedbacklokasi"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Kategori Pelanggaran</label>
                        <div class="col-md-6">
                            <select class="custom-select" name="kategoriP" id="kategoriP">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach ($kategoripel as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kategoriP') == $k->id ? 'selected' : '' }}>Pelanggaran {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class='invalid-feedback' id="feedbackkategoriP"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Jenis Pelanggaran</label>
                        <div class="col-md-6">
                            <select class="custom-select" name="jenisP" id="jenisP">
                                @if (empty(old('jenisP')) and empty(old('kategoriP')))
                                    <option selected disabled value="">Pilih...</option>
                                @endif
                            </select>
                            <div class='invalid-feedback' id="feedbackjenisP"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Keterangan</label>
                        <div class="col-md-6">
                            <textarea class="form-control" name="keterangan" id="keterangan" rows="2" maxlength="100"
                                placeholder="Rambut Gondrong"></textarea>
                            <div class='invalid-feedback' id="feedbackketerangan"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right text-sm-left">Bukti Foto</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control" name="bukti_foto" id="bukti_foto">
                            <div class='invalid-feedback' id="feedbackbukti_foto"></div>
                            <small class="form-text"><i class="fas fa-exclamation-circle mr-1"></i>Pastikan file foto
                                berukuran kurang dari atau sama dengan 500kb</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="container my-4 px-4">

                            <div class="accordion" id="accordionExample">
                                <!-- Daftar Pelanggaran -->
                                <div class="card">
                                    <div class="card-header p-0 " id="headingOne">
                                        <h6 class="m-0">
                                            <button class="btn btn-block text-light-blue text-left" type="button"
                                                data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <i class="fas fa-angle-down mr-3"></i>Daftar pelanggaran mahasiswa
                                            </button>
                                        </h6>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($pelanggaran->isEmpty())
                                                <h6 class="text-center">Tidak ada pelanggaran</h6>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">No</th>
                                                                <th scope="col">Id Pelanggaran</th>
                                                                <th scope="col">Tanggal</th>
                                                                <th scope="col">Lokasi</th>
                                                                <th scope="col">Kategori Pelanggaran</th>
                                                                <th scope="col">Jenis Pelanggaran</th>
                                                                <th scope="col">Keterangan</th>
                                                                <th scope="col">Bukti</th>
                                                                <th scope="col">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $no = 1; @endphp
                                                            @foreach ($pelanggaran as $p)
                                                                <tr>
                                                                    <td>{{ $no++ }}</td>
                                                                    <td>{{ $p->pelanggaran_mahasiswa_id }}</td>
                                                                    <td>{{ $p->tanggal }}</td>
                                                                    <td>{{ $p->nama_lokasi }}</td>
                                                                    <td>{{ $p->nama_kategori }}</td>
                                                                    <td>{{ $p->nama_jenis }}</td>
                                                                    <td>{{ $p->keterangan }}</td>
                                                                    <td><img src="{{ asset("storage/upload/tingkat$p->tingkat/$p->bukti_foto") }}"
                                                                            class="img-fluid" width="75"></td>
                                                                    <td>
                                                                        @switch($p->status)
                                                                            @case('Menunggu')
                                                                                <span class="badge badge-outline-danger">Menunggu
                                                                                    verifikasi</span>
                                                                            @break

                                                                            @case('Drop Out')
                                                                                <span class="badge badge-outline-danger">Drop
                                                                                    Out</span>
                                                                            @break

                                                                            @case('Belum mengisi jadwal')
                                                                                <span
                                                                                    class="badge badge-outline-warning">Proses</span>
                                                                            @break

                                                                            @case('Jadwal belum lengkap')
                                                                                <span
                                                                                    class="badge badge-outline-warning">Proses</span>
                                                                            @break

                                                                            @case('Proses')
                                                                                <span
                                                                                    class="badge badge-outline-warning">Proses</span>
                                                                            @break

                                                                            @case('Sedang diskors')
                                                                                <span class="badge badge-outline-warning">Sedang
                                                                                    diskors</span>
                                                                            @break

                                                                            @case('Selesai')
                                                                                <span
                                                                                    class="badge badge-outline-success">Selesai</span>
                                                                            @break

                                                                            @default
                                                                        @endswitch
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- End Daftar Pelanggaran -->
                                <!-- Daftar Skorsing Mahasiswa -->
                                <div class="card">
                                    <div class="card-header p-0 " id="headingOne">
                                        <h6 class="m-0">
                                            <button class="btn btn-block text-light-blue text-left" type="button"
                                                data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <i class="fas fa-angle-down mr-3"></i>Daftar skorsing mahasiswa
                                            </button>
                                        </h6>
                                    </div>

                                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($skorsing->isEmpty())
                                                <h6 class="text-center">Tidak ada skorsing</h6>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="row">No</th>
                                                                <th>Id Pelanggaran</th>
                                                                <th>Nama</th>
                                                                <th>Prodi</th>
                                                                <th>Tanggal Berakhir</th>
                                                                <th>Lama Skorsing</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $no = 1; @endphp
                                                            @foreach ($skorsing as $s)
                                                                <tr>
                                                                    <td class="align-middle">{{ $no++ }}</td>
                                                                    <td class="align-middle">
                                                                        {{ $s->pelanggaran_mahasiswa_id }}</td>
                                                                    <td class="align-middle">{{ $s->nama_mahasiswa }}</td>
                                                                    <td class="align-middle">{{ $s->prodi }}</td>
                                                                    <td class="align-middle">
                                                                        {{ !empty($s->tgl_berakhir) ? $s->tgl_berakhir : '00/00/0000' }}
                                                                    </td>
                                                                    <td class="align-middle">{{ $s->jum_hari }} Hari</td>
                                                                    <td class="align-middle">
                                                                        @switch($s->status)
                                                                            @case('Belum mengisi jadwal')
                                                                                <span
                                                                                    class='badge badge-outline-danger'>{{ $s->status }}</span>
                                                                            @break

                                                                            @case('Skorsing belum dimulai')
                                                                                <span
                                                                                    class='badge badge-outline-secondary'>{{ $s->status }}</span>
                                                                            @break

                                                                            @case('Sedang diskors')
                                                                                <span
                                                                                    class='badge badge-outline-warning'>{{ $s->status }}</span>
                                                                            @break

                                                                            @case('Selesai')
                                                                                <span
                                                                                    class='badge badge-outline-success'>{{ $s->status }}</span>
                                                                            @break

                                                                            @default
                                                                        @endswitch
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- End Daftar Skorsing Mahasiswa -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-md-6 col-sm-12 text-center">
                            <button class="btn btn-success btn-block btn-submit" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#kategoriP').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: '{{ route('json.jenispel') }}',
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

            $("#formPelanggaran").submit(function(e) {
                $('.btn-submit').prop('disabled', true);
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var form = $('#formPelanggaran')[0];

                var data = new FormData(form);
                $.ajax({
                    url: "{{ route('validation.form.pelanggaran') }}",
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data.error)
                        if ($.isEmptyObject(data.error)) {
                            e.currentTarget.submit();
                        } else {
                            $('.btn-submit').prop('disabled', false);
                            printErrorMsg(data.error);
                            removeErrorMsg(data.error);
                        }
                    }
                });
            });

            function printErrorMsg(msg) {
                $.each(msg, function(key, value) {
                    $('#' + key).addClass('is-invalid');
                    $('#feedback' + key).text(value[0]);
                });
            }

            function removeErrorMsg(msg) {
                var keys = (Object.keys(msg));
                var input = ["tanggal", "jam", "tingkat", "lokasi", "kategoriP", "jenisP", "keterangan",
                    "bukti_foto"
                ];
                var differ = input.filter(x => !keys.includes(x));
                $.each(differ, function(key, value) {
                    $('#' + value).removeClass('is-invalid');
                    $('#' + value).removeClass('is-invalid');
                })
            }
        });
    </script>
@endpush
