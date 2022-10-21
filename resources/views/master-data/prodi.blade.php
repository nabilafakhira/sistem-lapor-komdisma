@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Data Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
            </ol>
        </nav>
        <!-- Tambah Mahasiswa -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Program Studi</h6>

            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col text-right">
                        <button type="button" class="btn btn-primary btn-sm" id="rowAddProdi"><i
                                class="fa fa-plus mr-2"></i>Tambah Input</button>
                    </div>
                </div>
                <form method="POST" action="{{ route('store.prodi') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-row align-items-end" id="inputRowJProdi">
                        <div class="form-group col-md-6">
                            <label>Nama Program Studi</label>
                            <input type="text" class="form-control" name="nama[]"
                                placeholder="Masukkan nama program studi" required>
                            <div class="invalid-tooltip">Kode program studi tidak boleh kosong</div>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Kode Program Studi</label>
                            <input type="text" class="form-control" name="kode[]"
                                placeholder="Masukkan kode program studi" maxlength="10" required>
                            <div class="invalid-tooltip">Kode program studi tidak boleh kosong</div>
                        </div>
                    </div>
                    <div id="newRowProdi"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-3 col-sm-12">
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Table Akun -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Program Studi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="tableData" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prodi as $row)
                                <tr>
                                    <td></td>
                                    <td>{{ $row->nama  }}</td>
                                    <td>{{ $row->kode }}</td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editProdi"
                                            data-id="{{ $row->id }}" data-nama="{{ $row->nama }}" data-kode="{{ $row->kode }}"
                                            class="btn rounded-circle btn-outline-primary btn-sm btnUpdateProdi"><i
                                                class="fas fa-pen"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Moadl edit --}}
    <div class="modal fade" id="editProdi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Program Studi</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form  method="POST" action="{{ route('update.prodi') }}" class="needs-validation" novalidate>
                @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                        <div class="invalid-feedback">Nama program studi tidak boleh kosong</div>
                    </div>
                    <div class="form-group">
                        <label>Kode Program Studi</label>
                        <input type="text" class="form-control" name="kode" id="kode" maxlength="10" required>
                        <div class="invalid-feedback">Kode program studi tidak boleh kosong</div>
                    </div>
                    <div class="form-group text-right">
                        <a class="btn" data-dismiss="modal">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $("#rowAddProdi").click(function () {
        var html = '';
        html +=  '<div class="form-row align-items-end" id="inputRowProdi">';
        html +=  '<div class="form-group col-md-6"><label>Nama Program Studi</label>';
        html +=  '<input type="text" class="form-control" name="nama[]" placeholder="Masukkan nama program studi" required>';
        html +=  '<div class="invalid-tooltip">Kode program studi tidak boleh kosong</div></div>';
        html +=  '<div class="form-group col-md-5"><label>Kode Program Studi</label>';
        html +=  '<input type="text" class="form-control" name="kode[]" placeholder="Masukkan kode program studi" maxlength="10" required>';
        html +=  '<div class="invalid-tooltip">Kode program studi tidak boleh kosong</div></div>'
        html += '<div class="form-group col-md-auto col-sm-12 align-self-md-end align-self-sm-start">';
        html += '<button type="button" class="btn btn-danger mt-0 btn-block" data-toggle="tooltip" data-placement="top" title="Hapus Input" id="rowRemProdi"><i class="fa fa-trash"></i></button>';
        html += '</div>';
        html +=  '</div>';
        $('#newRowProdi').append(html);
    });

    // remove row
    $(document).on('click', '#rowRemProdi', function () {
        $(this).closest('#inputRowProdi').remove();
    });

    $('.btnUpdateProdi').on('click', function() {
        $('#editProdi #id').val($(this).data('id'));
        $('#editProdi #nama').val($(this).data('nama'));
        $('#editProdi #kode').val($(this).data('kode'));
    });
</script>
@endpush
