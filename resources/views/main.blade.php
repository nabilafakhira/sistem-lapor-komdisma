<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} | Sistem Lapor Komdisma SV IPB</title>

    <link rel="icon" href="{{ asset('img/favicon.png') }}" />

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom styles for this template-->

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.6/datatables.min.css" />
    <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">
    <!--Daterangepicker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include("templates.sidebar.$user->role")
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="flash-data" data-flashdata="{{ session()->has('message') ? session('message') : '' }}"></div>

            <!-- Main Content -->
            <div id="content">

                {{-- Topbar --}}
                @if ($user->role != 'mahasiswa')
                    @include('templates.topbar.topbar-pengurus')
                @else
                    @include('templates.topbar.topbar-mahasiswa')
                @endif
                {{-- End of Topbar --}}

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @if ($user->role != 'mahasiswa')
                <!-- Moda Tambah Pelanggaran -->
                <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cari NIM Mahasiswa</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('add.pelanggaran') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control"
                                            placeholder="Masukkan NIM mahasiswa yang melakukan pelanggaran"
                                            name="nim">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Cari</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span> Copyright &copy; Komisi Disiplin dan Kemahasiswaan Sekolah Vokasi IPB
                            {{ date('Y') }}. All rights
                            reserved.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/main.js') }}"></script>
    
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/sorting/date-uk.js"></script>

    <!-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script> -->
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/b-print-1.6.5/r-2.2.6/datatables.min.js">
    </script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!--DateRangePicker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>

    @stack('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('input[type="checkbox"].check-all').click(function() { // Ketika user men-cek checkbox all
                if ($(this).is(":checked")) // Jika checkbox all diceklis
                    $('input[type="checkbox"].check-item').prop("checked",
                        true); // ceklis semua checkbox data dengan class "check-item"
                else // Jika checkbox all tidak diceklis
                    $('input[type="checkbox"].check-item').prop("checked",
                        false); // un-ceklis semua checkbox data dengan class "check-item"
            });

            var tableData = $('#tableData').DataTable({
                "responsive": true,
                "columnDefs": [{
                    "targets": 1,
                    "sType": "date-uk"
                }, {
                    "targets": [0, -1],
                    "orderable": false
                }],
                dom: "<'row'<'col-sm-12 col-md-4'l><'text-right col-sm-12 col-md-6 ml-auto'f><'col-sm-12 col-md-auto mx-0 px-0'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                renderer: 'bootstrap',
                buttons: [{
                    text: 'Reload Table',
                    className: 'btn btn-secondary btn-sm btnreload',
                    action: function(e, dt, node, config) {
                        location.reload()
                    }
                }, ],
            });

            tableData.on('order.dt search.dt', function() {
                tableData.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            $('#prodi').on('change', function() {
                tableData.columns(3).search(this.value).draw();
            });
            $('#kategori').on('change', function() {
                tableData.columns(4).search(this.value).draw();
            });
            $('#jenis').on('change', function() {
                tableData.columns(5).search(this.value).draw();
            });
            $('#lokasi').on('change', function() {
                tableData.columns(6).search(this.value).draw();
            });
            $('#sanksi').on('change', function() {
                tableData.columns(7).search(this.value).draw();
            });
            $('#status').on('change', function() {
                tableData.columns(8).search(this.value).draw();
            });

            setInterval(function() {
                tableData.draw(false); //then draw it
            }, 10000);


            var tableUser = $('#tableUser').DataTable({
                "columnDefs": [{
                    "targets": [3, 4],
                    "orderable": false
                }],
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
            tableUser.on('order.dt search.dt', function() {
                tableUser.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });


        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <script>
        $("#rowAddP").click(function() {
            var html = '';
            html += '<div class="form-row align-items-end" id="inputRowP">';
            html += '<div class="form-group col-md-6">';
            html += '<label>Nama</label>';
            html +=
                '<input type="text" class="form-control" name="nama[]" placeholder="Masukkan nama lengkap beserta gelar" maxlength="70" required>';
            html += '<div class="invalid-tooltip">Nama tidak boleh kosong</div>';
            html += '</div>';
            html += '<div class="form-group col-md-5">';
            html += '<label>Id</label>';
            html +=
                '<input type="text" class="form-control" name="id[]" placeholder="Masukkan NIK/NIP/NPI" maxlength="20" required>';
            html += '<div class="invalid-tooltip">Id tidak boleh kosong</div>';
            html += '</div>';
            html += '<div class="form-group col-md-auto col-sm-12 align-self-md-end align-self-sm-start">';
            html +=
                '<button type="button" class="btn btn-danger mt-0 btn-block" data-toggle="tooltip" data-placement="top" title="Hapus Input" id="rowRemP"><i class="fa fa-trash"></i></button>';
            html += '</div>';
            html += '</div>';
            $('#newRowP').append(html);
        });

        // remove row
        $(document).on('click', '#rowRemP', function() {
            $(this).closest('#inputRowP').remove();
        });
    </script>
</body>

</html>
