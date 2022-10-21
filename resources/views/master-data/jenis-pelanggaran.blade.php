@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Data Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jenis Pelanggaran</li>
            </ol>
        </nav>

        <!-- Tambah jenis pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Jenis Pelanggaran</h6>

            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col text-right">
                        <button type="button" class="btn btn-primary btn-sm" id="rowAddJ"><i
                                class="fa fa-plus mr-2"></i>Tambah Input</button>
                    </div>
                </div>
                <form method="POST" action="{{ route('store.jenis.pelanggaran') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-row align-items-end" id="inputRowJ">
                        <div class="form-group col-md-6">
                            <label>Kategori Pelanggaran</label>
                            <select class="custom-select" name="kategori[]" required>
                                <option value="" selected disabled>Pilih...</option>
                                @foreach ($kategoripel as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-tooltip">Silahkan pilih kategori pelangaran</div>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Jenis Pelanggaran</label>
                            <input type="text" class="form-control" name="jenis[]"
                                placeholder="Masukkan jenis pelanggaran" maxlength="30" required>
                            <div class="invalid-tooltip">Jenis pelanggaran tidak boleh kosong</div>
                        </div>
                    </div>
                    <div id="newRowJ"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-3 col-sm-12">
                            <button type="submit" class="btn btn-success btn-block">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table jenis pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Jenis Pelanggaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="tableData" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Kategori Pelanggaran</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenispel as $row)
                                <tr>
                                    <td></td>
                                    <td>{{ $row->kategorip->nama }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editJenispel" data-id="{{ $row->id }}" data-jenis="{{  $row->nama  }}" data-kategori={{ $row->kategori_pelanggaran_id }}
                                            class="btn rounded-circle btn-outline-primary btn-sm btnUpdateJenis"><i
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

    <!-- Modal edit jenis pelanggaran -->
    <div class="modal fade" id="editJenispel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Jenis Pelanggaran</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update.jenis.pelanggaran') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="jenis_id" id="jenis_id">
                    <div class="form-group">
                        <label>Kategori Pelanggaran</label>
                        <select class="custom-select" name="kategoriP" id="kategoriP" required>
                        <option value="" disabled>Pilih...</option>
                        @foreach ($kategoripel as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }}</option>
                        @endforeach
                        </select>
                        <div class="invalid-feedback">Silahkan pilih kategori pelangaran</div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Pelanggaran</label>
                        <input type="text" class="form-control" name="jenisP" id="jenisP" placeholder="Masukkan jenis pelanggaran" maxlength="30" required>
                        <div class="invalid-feedback">Jenis pelanggaran tidak boleh kosong</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn" data-dismiss="modal">Batal</a>
                    <button type="submit" id="btn-lokasi" class="btn btn-success">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#rowAddJ").click(function() {
                var html = '';
                html += '<div class="form-row align-items-end" id="inputRowJ">';
                html += '<div class="form-group col-md-6">';
                html += '<label>Kategori Pelanggaran</label>';
                html += '<select class="custom-select" name="kategori[]" required>';
                html += '<option value="" selected disabled>Pilih...</option>';
                html += '@foreach ($kategoripel as $row) <option value="{{ $row->id }}">{{ $row->nama }}</option> @endforeach';
                html += '</select>';
                html += '<div class="invalid-tooltip">Silahkan pilih kategori pelanggaran</div>';
                html += '</div>';
                html += '<div class="form-group col-md-5">';
                html += '<label>Jenis Pelanggaran</label>';
                html += '<input type="text" class="form-control" name="jenis[]" placeholder="Masukkan jenis pelanggaran" maxlength="30" required>';
                html += '<div class="invalid-tooltip">Jenis pelanggaran tidak boleh kosong</div>';
                html += '</div>';
                html += '<div class="form-group col-md-auto col-sm-12 align-self-md-end align-self-sm-start">';
                html += '<button type="button" class="btn btn-danger mt-0 btn-block" data-toggle="tooltip" data-placement="top" title="Hapus Input" id="rowRemJ"><i class="fa fa-trash"></i></button>';
                html += '</div>';
                html += '</div>';
                $('#newRowJ').append(html);
            });

            // remove row
            $(document).on('click', '#rowRemJ', function() {
                $(this).closest('#inputRowJ').remove();
            });

            function getAjaxKategori() {
                var result = null
                $.ajax({
                    url: '{{ route('ajax.kategori.pelanggaran') }}',
                    async: false,
                    success: function(response) {
                        result = response;
                    }
                });
                return result;
            }
            const kategori = getAjaxKategori();

            $('.btnUpdateJenis').on('click', function() {
                $('#editJenispel #jenis_id').val($(this).data('id'));
                $('#editJenispel #jenisP').val($(this).data('jenis'));
                const id_kategori = $(this).data('kategori');

                $.each(kategori, function(key, value) {
                    if(id_kategori === value){
                        $('#editJenispel #kategoriP option[value='+id_kategori+']').attr('selected', 'selected');
                    }
                });
            });

            $('#editJenispel').on('hidden.bs.modal', function (e) {
                var modal = $(this);
                modal.find('#kategoriP option').removeAttr("selected")
            })


            
            

        });
    </script>
@endpush
