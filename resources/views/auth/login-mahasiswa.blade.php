@extends('auth.templates.index')

@section('content')
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-md-5 col-sm-12">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg">
                                <div class="p-5">
                                    <div class="text-center">
                                        <a class="navbar-brand mb-2" href="#">
                                            <img src="{{ asset('img/logo-pink.png') }}" width="45%" alt="">
                                        </a>
                                    </div>
                                    <div id="infoMessage">
                                        @if (session()->has('message'))
                                            <div class="errors alert alert-danger small pb-0" role="alert">
                                                <ul class="mb-2 pl-0">
                                                    <li><i class="fas fa-info-circle mr-2"></i>Login gagal! Periksa kembali
                                                        username dan password anda</li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('post.login.mahasiswa') }}" method="post"
                                        class="user mt-3 needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" name="username" id="username"
                                                class="form-control form-control-user " placeholder="Username"
                                                value="" required>
                                            <div class="invalid-feedback">
                                                Username tidak boleh kosong
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group login" id="passwordBaru">
                                                <input type="password" name="password" id="password"
                                                    class="form-control form-control-user" placeholder="Password" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><a href=""><i
                                                                class="fa fa-eye-slash text-secondary "
                                                                aria-hidden="true"></i></a></div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Password tidak boleh kosong
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <small class="form-text text-muted">
                                                Masukkan NIM sebagai username dan password
                                            </small>
                                        </div>

                                        <button href="#" class="btn btn-pink btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="row text-white justify-content-center small">
            Copyright &copy; Komisi Disiplin dan Kemahasiswaan Sekolah Vokasi IPB {{ date('Y') }}. All rights reserved.
        </div>

    </div>
@endsection
