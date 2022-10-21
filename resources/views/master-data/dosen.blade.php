@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Data Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dosen</li>
            </ol>
        </nav>
        <!-- Tambah Mahasiswa -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Dosen</h6>
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
                <form method="POST" action="{{ route('store.dosen') }}" class="needs-validation" novalidate>
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
        <!-- Table Dosen -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Dosen</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('delete.akun') }}" id="form-delete">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableDosen" width="100%" cellspacing="0">
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
                    <form action="{{ route('import.dosen') }}" method="POST" class="needs-validation" novalidate
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

@push('script')
    <script>
        $(document).ready(function() {
            var tableDosen = $('#tableDosen').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "{!! route('ajax.dosen') !!}",
                    "type": "POST",
                    'data': {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columnDefs": [{
                    "targets": [0, 3, 4],
                    "orderable": false
                }],
                drawCallback: function(settings) {
                    $("[data-toggle=tooltip]").tooltip();
                    $('.resetAkun').each(function(index) {
                        $(this).on("click", function(e) {
                            e.preventDefault();
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger mr-3'
                                },
                                buttonsStyling: false
                            })

                            swalWithBootstrapButtons.fire({
                                title: 'Apakah anda yakin?',
                                text: "Password dan username akan berubah menjadi NIP/NPI/NIDN/NIK dari pengguna yang bersangkutan",
                                icon: 'warning',
                                position: 'top',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, lanjutkan',
                                cancelButtonText: 'Tidak, batalkan',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var href = $(this).attr('href');
                                    window.location.href = href;
                                }
                            })
                        });
                    });

                    $('.dosenKeadmin').each(function(index) {
                        $(this).on("click", function(e) {
                            e.preventDefault();
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger mr-3'
                                },
                                buttonsStyling: false
                            })

                            swalWithBootstrapButtons.fire({
                                title: 'Apakah anda yakin?',
                                text: "Pengguna yang bersangkutan akan berubah hak akses menjadi admin",
                                icon: 'warning',
                                position: 'top',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, lanjutkan',
                                cancelButtonText: 'Tidak, batalkan',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var href = $(this).attr('href');
                                    window.location.href = href;
                                }
                            })
                        });
                    });

                },
                dom: "<'row'<'col-sm-12 col-md-4'l><'text-right col-sm-12 col-md-6 ml-auto'f><'col-sm-12 col-md-auto mx-0 px-0'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                renderer: 'bootstrap',
                buttons: [{
                    text: 'Hapus Data',
                    className: 'btn btn-danger btn-sm HapusData',
                    action: function(e, dt, node, config) {
                        e.preventDefault();
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger mr-3'
                            },
                            buttonsStyling: false
                        })

                        swalWithBootstrapButtons.fire({
                            title: 'Apakah anda yakin?',
                            text: "Seluruh data yang terkait dengan pengguna yang dipilih akan ikut terhapus",
                            icon: 'warning',
                            position: 'top',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, lanjutkan',
                            cancelButtonText: 'Tidak, batalkan',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#form-delete').submit();
                            }
                        })
                    },
                    attr: {
                        id: 'HapusUser'
                    }
                }, ],
            });

            tableDosen.on('draw.dt', function() {
                var PageInfo = tableDosen.page.info();
                tableDosen.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        });
    </script>
@endpush
