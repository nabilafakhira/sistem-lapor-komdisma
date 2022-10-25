<!DOCTYPE html>

<head>
    <title>Surat Keterangan Kelakuan Baik</title>
    <meta charset="utf-8">

</head>

<body>
    <!--Surat-->
    <div class="surat" style="margin : 0 25px">
        <br>
        <table align='center'>
            <tr>
                <td><img src="https://4.bp.blogspot.com/-kg9B6SrjXvA/WazwA8sn6EI/AAAAAAAAAD8/7MaFbH1120sAozsSwsTVmju4ywhkKbQNQCLcBGAs/s1600/logo_IPB.svg-image4144-4294966727.png"
                        style="width:90px;"> </td>
                <td>
                    <p align="center" style="line-height: 1px;margin-left:35px;"> KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN
                    </p>
                    <p align="center" style="line-height: 1px;margin-left:25px;"> INSTITUT PERTANIAN BOGOR </p>
                    <p align="center" style="line-height: 1px;margin-left:25px;"> SEKOLAH VOKASI</p>
                    <p align="center" style="line-height: 1px;margin-left:25px;"> Kampus IPB Cilibende, Jl. Kumbang
                        No.14 Bogor 16151</p>
                    <p align="center" style="line-height: 1px;margin-left:25px;"> Telp. (0251) 8329101, 8329101, Fax
                        (0251) 8348007</p>
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <p class="judul" align="center"><u><b>SURAT KETERANGAN BERKELAKUAN BAIK</b></u></p>
        <p class="judul" style="margin-top:-15px" align="center">Nomor : 260/Komdisma/XII/{{ date('Y') }}</p>
        <br>
        <br>
        <div class="isi-surat">
            <table class="tabel-surat-mahasiswa" style="padding-left:30px;">
                <tr>
                    <td class="data-mahasiswa" colspan="3" style="width: 100%; padding-bottom: 20px;">
                        Yang bertanda tangan dibawah ini, menerangkan bahwa mahasiswa :
                    </td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" style="width: 30%;">Nama</td>
                    <td class="data-mahasiswa" style="width: 1%">:</td>
                    <td class="data-mahasiswa" style="width: 65%;">{{ $user->nama }}</td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" style="width: 30%;">NIM</td>
                    <td class="data-mahasiswa" style="width: 1%">:</td>
                    <td class="data-mahasiswa" style="width: 65%;">{{ $user->nim }}</td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" style="width: 30%; vertical-align: top;">Program Studi</td>
                    <td class="data-mahasiswa" style="width: 1%">:</td>
                    <td class="data-mahasiswa" style="width: 65%;">{{ $prodi }}</td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" style="width: 30%; vertical-align: top;">Tempat, tanggal lahir</td>
                    <td class="data-mahasiswa" style="width: 1%;">:</td>
                    <td class="data-mahasiswa" style="width: 65%;">{{ $ttl }}</td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" style="width: 30%; vertical-align: top;">Alamat</td>
                    <td class="data-mahasiswa" style="width: 1%; vertical-align: top;">:</td>
                    <td class="data-mahasiswa" style="width: 65%;">{{ $alamat }}</td>
                </tr>
                <tr>
                    <td class="data-mahasiswa" colspan="3" style="width: 100%; padding-top: 10px;">
                        <p>Selama dalam pengawasan kami sampai surat keterangan ini diterbitkan tidak pernah terlibat
                            dalam pelanggaran Tatatertib Kehidupan Kampus.</p>
                        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
                    </td>
                </tr>
            </table>


        </div>
        <table style="padding-left:30px;">
            <tr>
                <td colspan="2" style="text-align: left; width:50%">
                    <p style="padding-bottom:0px; padding-top:40px;">Mengetahui,</p>
                    <p>Wakil Direktur Bidang Akademik dan Kemahasiswaan</p>
                    <p style="margin-top:-15px;">Sekolah Vokasi IPB</p>
                    <p><img src="{{ public_path("img/ttd-null.jpeg") }}"
                            style="height:125px;"></p>
                    <p><u>Dr.Ir. Bagus Priyo Purwanto, MAgr</u></p>
                    <p style="margin-top:-10px;">NIP 19600503 198503 1 003</p>


                </td>
                <td colspan="2" style="text-align: left; width:50%; padding-left:0px;">
                    <p style="padding-bottom:40px; top:-10px;">Bogor, {{ $tgl_pengajuan }}</p>
                    <p>Koordinator Komisi Disiplin dan Kemahasiswaan</p>
                    <p style="margin-top:-15px;">Sekolah Vokasi IPB</p>
                    @if ($koordinator != null && $koordinator->ttd != null)
                        <p><img src="{{ public_path("img/ttd/$koordinator->ttd") }}"
                                style="height:125px;"></p>
                    @else
                    <p><img src="{{ public_path("img/ttd-null.jpeg") }}"
                            style="height:125px;"></p>
                    @endif
                    <p><u>Dr. drh. Aryani Sismin Satyaningtijas, M.Sc.</u></p>
                    <p style="margin-top:-10px;">NIP 19600914 198603 2 001</p>
                </td>
            </tr>
        </table>
    </div>
    <!-- end surat -->
</body>
