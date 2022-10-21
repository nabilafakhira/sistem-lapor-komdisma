@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Akun</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('update.akun.pengurus') }}" id="formEditPengurus" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" class="form-control" value="{{ $user->user_id }}" name="user_id">
                            <input type="hidden" class="form-control" value="{{ $user->id }}" name="id">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" value="{{ $user->nama }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Id</label>
                                <input type="text" class="form-control" value="{{ $user->id }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" name="username"
                                    id="username" maxlength="30">
                                <div class="invalid-feedback" id="feedbackusername"></div>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $user->email }}" maxlength="50">
                                <div class="invalid-feedback" id="feedbackemail"></div>
                            </div>
                            @if ($user->ttd == null && ($user->role == "admin" || $user->role == 'super-admin'))
                                <div class="form-group ">
                                    <label class="">TTD</label>
                                    <input type="file" class="form-control" name="ttd" id="ttd"
                                        accept="image/png, image/jpg, image/jpeg">
                                    <div class='invalid-feedback' id="feedbackttd"></div>
                                    <small class="form-text"><i class="fas fa-exclamation-circle mr-1"></i>Upload hanya bisa dilakukan sekali. Pastikan file
                                        foto benar dan berukuran kurang dari atau sama dengan 500kb!</small>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Password Lama</label>
                                <div class="input-group" id="passwordLama">
                                    <input type="password" name="oldPassword" id="oldPassword" class="form-control"
                                        placeholder="Masukkan password lama jika ingin mengubah password" minlength="6">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><a href=""><i
                                                    class="fa fa-eye-slash text-secondary " aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="feedbackoldPassword"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Password Baru</label>
                                <div class="input-group" id="passwordBaru">
                                    <input type="password" name="newPassword" id="newPassword" class="form-control"
                                        placeholder="Masukkan password baru jika ingin mengubah password">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><a href=""><i
                                                    class="fa fa-eye-slash text-secondary " aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="feedbacknewPassword"></div>
                                </div>
                            </div>
                            <button class="btn btn-success btn-submit">Simpan perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#formEditPengurus").submit(function(e) {
                $('.btn-submit').prop('disabled', true);
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var form = $('#formEditPengurus')[0];

                var data = new FormData(form);
                $.ajax({
                    url: "{{ route('validation.edit.akun.pengurus') }}",
                    type: 'POST',
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
                    $('#feedback' + key).text(value);
                });
            }

            function removeErrorMsg(msg) {
                var keys = (Object.keys(msg));
                var input = ["username", "email", "oldPassword", "newPassword", "ttd"];
                var differ = input.filter(x => !keys.includes(x));
                $.each(differ, function(key, value) {
                    $('#' + value).removeClass('is-invalid');
                    $('#' + value).removeClass('is-invalid');
                })
            }
        });
    </script>
@endpush
