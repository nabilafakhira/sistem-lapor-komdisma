@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Data Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Akademik</li>
            </ol>
        </nav>
        <!-- Tambah Mahasiswa -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Akademik</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#importExcel"><i class="fa fa-upload mr-2"></i>Import Excel</button>
                            </div>
                            <div class="btn-group mr-2" role="group" aria-label="Second group">
                                <a class="btn btn-primary btn-sm" href="{{ asset('excel/Format Import Pengurus.xlsx') }}"
                                    download data-toggle="tooltip" data-placement="top" title="Unduh Format Excel"><i
                                        class="fas fa-file"></i></a>
                            </div>
                            <div class="btn-group ml-auto" role="group" aria-label="Third group">
                                <button type="button" class="btn btn-primary btn-sm" id="rowAddP"><i
                                        class="fa fa-plus mr-2"></i>Tambah Input</button>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('store.akademik') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-row align-items-end" id="inputRowP">
                        <div class="form-group col-md-6">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama[]"
                                placeholder="Masukkan nama lengkap beserta gelar" maxlength="70" required>
                            <div class="invalid-tooltip">Nama tidak boleh kosong</div>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Id</label>
                            <input type="text" class="form-control" name="id[]"
                                placeholder="Masukkan NIK/NIP/NPI/NIDN" maxlength="20" required>
                            <div class="invalid-tooltip">Id tidak boleh kosong</div>
                        </div>
                    </div>
                    <div id="newRowP"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-3 col-sm-12">
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Table Akademik -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Akademik</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('delete.akun') }}" id="form-delete">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableUser" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="row">No</th>
                                    <th>Id</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input check-all" id="check-all">
                                            <label class="custom-control-label" for="check-all"></label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($akademik as $row)
                                    <tr>
                                        <td></td>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->nama }}</td>
                                        <td>
                                            <a href="{{ route('reset.akun.pengurus', ['id' => $row->user_id]) }}" class="btn rounded-circle btn-outline-success btn-sm resetAkademik"
                                                data-toggle="tooltip" data-placement="top" title="Reset Akun"
                                                id="resetAkademik"><i class="fas fa-redo-alt"></i></a>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input check-item"
                                                    name='id[]' value="{{ $row->user_id }}"
                                                    id="checkitem{{ $row->user_id }}">
                                                <label class="custom-control-label"
                                                    for="checkitem{{ $row->user_id }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Import Excel -->
    <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Data Excel</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('import.akademik') }}" method="POST" class="needs-validation" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Upload file excel</label>
                            <input type="file" class="form-control" name="fileExcel" required accept=".xls, .xlsx">
                            <div class="invalid-feedback">Silahkan pilih file dengan format .xls atau .xlsx</div>
                        </div>
                </div>
                <div class="modal-footer">
                    <a class="btn" data-dismiss="modal">Batal</a>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
