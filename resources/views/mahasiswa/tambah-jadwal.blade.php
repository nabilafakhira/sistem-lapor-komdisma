@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mahasiswa.detail.pelanggaran') }}"><i
                            class="fas fa-angle-left mr-2"></i>Kembali</a></li>
            </ol>
        </nav>


        <div class="alert alert-light shadow border-left-warning" role="alert">
            <div class="d-flex align-items-center" href="#">
                <div class="mr-3">
                    <i class='bx bx-info-circle h2 text-warning mb-0'></i>
                </div>
                <div>
                    <span class="font-weight-bold text-warning">Tambahkan jadwal selama {{ $jum_sanksi->skorsing }} hari, tidak
                        kurang atau pun lebih</span>
                </div>
            </div>
        </div>


        <!-- Tambah Jadwal Matkul -->
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Tambah Jadwal Mata Kuliah</h6>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-end mb-3">
                            <button type="button" class="btn btn-success btn-sm mr-1" id="rowAdd"><i
                                    class="fa fa-plus mr-2"></i>Tambah Input</button>
                        </div>
                        <form method="POST" action="{{ route('mahasiswa.store.jadwal') }}" class="needs-validation" novalidate
                            id="form-tambah">
                            @csrf
                            <input type="hidden" name="id_pelanggaran" id="id_pelanggaran" value= "{{ $id_pelanggaran }}">
                            <div class="form-row align-items-end justify-content-center" id="inputRow">
                                <div class="form-group col-md-2 col-sm-12">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal[]" id="tanggal[]" required>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label>Mata Kuliah (MK)</label>
                                    <input type="type" class="form-control" name="matkul[]" maxlength="100" required>
                                </div>
                                <div class="form-group col-md-auto col-sm-12">
                                    <label>Jam Mulai</label>
                                    <input type="time" class="form-control" name="jam_mulai[]" required>
                                </div>
                                <div class="form-group col-md-auto col-sm-12">
                                    <label>Jam Selesai</label>
                                    <input type="time" class="form-control" name="jam_selesai[]" required>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label>Dosen</label>
                                    <select class="custom-select" name="dosen[]" required>
                                        <option value="" selected disabled>Pilih...</option>
                                        @foreach ($dosen as $row)
                                            <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label>Koordinator (optional)</label>
                                    <select class="custom-select" name="koordinator[]" required>
                                        <option value="" selected disabled>Pilih...</option>
                                        @foreach ($dosen as $row)
                                            <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-auto col-sm-12 align-self-md-end align-self-sm-start">
                                    <button type="button" class="btn btn-danger mt-0 btn-block disabled"  disabled data-toggle="tooltip"
                                        data-placement="top" title="Hapus Input" id="rowRem"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                            </div>

                            <div id="newRow"></div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 col-sm-12 text-center">
                                    <button class="btn btn-primary btn-block" id="btn-tambah-jadwal">Simpan</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $("#rowAdd").click(function() {
            var html = '';
            html += '<div class="form-row align-items-end justify-content-center" id="inputRow">';
            html += '<div class="form-group col-md-2 col-sm-12">';
            html += '<label>Tanggal</label>';
            html += '<input type="date" class="form-control" name="tanggal[]" id="tanggal[]" required>';
            html += '</div>';
            html += '<div class="form-group col-md-2 col-sm-12">';
            html += '<label>Mata Kuliah (MK)</label>';
            html += '<input type="type" class="form-control" name="matkul[]" maxlength="100" required>';
            html += '</div>';
            html += '<div class="form-group col-md-auto col-sm-12">';
            html += '<label>Jam Mulai</label>';
            html += '<input type="time" class="form-control" name="jam_mulai[]" required>';
            html += '</div>';
            html += '<div class="form-group col-md-auto col-sm-12">';
            html += '<label>Jam Selesai</label>';
            html += '<input type="time" class="form-control" name="jam_selesai[]" required>';
            html += '</div>';
            html += '<div class="form-group col-md-2 col-sm-12">';
            html += '<label>Dosen MK</label>';
            html += '<select class="custom-select" name="dosen[]" required>';
            html += '<option value="" selected disabled>Pilih...</option>';
            html +=
                '@foreach ($dosen as $row) <option value="{{ $row->id }}">{{ $row->nama }}</option> @endforeach';
            html += '</select>';
            html += '</div>';
            html += '<div class="form-group col-md-2 col-sm-12">';
            html += '<label>Koordinator (MK)</label>';
            html += '<select class="custom-select" name="koordinator[]" required>';
            html += '<option value="" selected disabled>Pilih...</option>';
            html +=
                '@foreach ($dosen as $row) <option value="{{ $row->id }}">{{ $row->nama }}</option> @endforeach';
            html += '</select>';
            html += '</div>';
            html += '<div class="form-group col-md-auto col-sm-12 align-self-md-end align-self-sm-start">';
            html +=
                '<button type="button" class="btn btn-danger mt-0 btn-block" data-toggle="tooltip" data-placement="top" title="Hapus Input" id="rowRem"><i class="fa fa-trash"></i></button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#rowRem', function() {
            $(this).closest('#inputRow').remove();
        });
    </script>
    <script>
        var categories = document.getElementsByName('tanggal[]').values();

        console.log(categories);
    </script>
@endpush
