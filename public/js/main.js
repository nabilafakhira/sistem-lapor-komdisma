
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
  //show hide password 
  $("#passwordBaru .input-group-append .input-group-text a").on('click', function(event) {
    event.preventDefault();
    if($('#passwordBaru input').attr("type") == "text"){
        $('#passwordBaru input').attr('type', 'password');
        $('#passwordBaru .input-group-append .input-group-text i').addClass( "fa-eye-slash" );
        $('#passwordBaru .input-group-append .input-group-text i').removeClass( "fa-eye" );
    }else if($('#passwordBaru input').attr("type") == "password"){
        $('#passwordBaru input').attr('type', 'text');
        $('#passwordBaru .input-group-append .input-group-text i').removeClass( "fa-eye-slash" );
        $('#passwordBaru .input-group-append .input-group-text i').addClass( "fa-eye" );
    }
  });
  $("#passwordLama .input-group-append .input-group-text a").on('click', function(event) {
    event.preventDefault();
    if($('#passwordLama input').attr("type") == "text"){
        $('#passwordLama input').attr('type', 'password');
        $('#passwordLama .input-group-append .input-group-text i').addClass( "fa-eye-slash" );
        $('#passwordLama .input-group-append .input-group-text i').removeClass( "fa-eye" );
    }else if($('#passwordLama input').attr("type") == "password"){
        $('#passwordLama input').attr('type', 'text');
        $('#passwordLama .input-group-append .input-group-text i').removeClass( "fa-eye-slash" );
        $('#passwordLama .input-group-append .input-group-text i').addClass( "fa-eye" );
    }
  });
});

(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
      
    };
    
    // Toggle the side navigation when window is resized below 480px
    if ($(window).width() < 768 && !$("body").hasClass("sidebar-toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
      $('.sidebar .collapse').collapse('hide');
    };
  });

  if ($(window).width() < 768 && !$("body").hasClass("sidebar-toggled")) {
    $("body").addClass("sidebar-toggled");
    $(".sidebar").addClass("toggled");
    $('.sidebar .collapse').collapse('hide');
  };

  if($('#accordionSidebar')){
  const ps = new PerfectScrollbar('#accordionSidebar');
  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });
  }
  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict

$("#btn-collapse, #btn-hidden").on('click', function(e) {
  $("#form-normal").toggleClass("d-none");
  $("#form-collapse").toggleClass("d-none");
});




const flashData = $('.flash-data').data('flashdata');
const splitString = flashData.split(",");

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

if (flashData == "NimNotFound"){
    Swal.fire({
        title: 'Data mahasiswa tidak ada',
        text: 'Silahkan masukkan NIM dengan benar!',
        icon: 'warning',
        position: 'center',
        showConfirmButton: false,
        timer: 2500
      });
} else if (flashData == "DataGagalEdit"){
  Swal.fire({
      title: 'Data gagal disimpan',
      text: 'Pastikan data yang dimasukkan bersifat unik!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "VerifikasiBerhasil"){
    Toast.fire({
      title: 'Verifikasi berhasil dilakukan!',
      icon: 'success',
    });
} else if (flashData == "VerifikasiGagal"){
  Swal.fire({
    title: 'Verifikasi gagal dilakukan!',
    text: 'Pastikan nama sanksi yang dimasukkan bersifat unik',
    icon: 'warning',
    position: 'center',
    showConfirmButton: false,
    timer: 3000
  });
} else if (flashData == "AddPelanggaranBerhasil"){
    Toast.fire({
      title: 'Pelanggaran berhasil ditambahkan!',
      icon: 'success',
    });
} else if (flashData == "laporBerhasil"){
    Toast.fire({
      title: 'Laporan berhasil diajukan!',
      icon: 'success',
    });
} else if (flashData == "terimaLaporBerhasil"){
    Toast.fire({
      title: 'Laporan diterima!',
      icon: 'success',
    });
} else if (flashData == "DataBerhasilEdit"){
    Toast.fire({
      title: 'Data berhasil diubah!',
      icon: 'success',
    });
} else if (flashData == "AddJadwalBerhasil"){
    Toast.fire({
      title: 'Jadwal berhasil ditambahkan!',
      icon: 'success',
    });
} else if (splitString[0] == "jumSkorsNotSame"){
    Swal.fire({
        title: 'Perhatikan jumlah jadwal!',
        text: 'Jadwal tidak boleh lebih atau kurang dari '+splitString[1]+' hari',
        icon: 'warning',
        position: 'center',
        showConfirmButton: false,
        timer: 3000
    });
} else if (flashData == "JadwalBerhasilEdit"){
    Toast.fire({
      title: 'Jadwal berhasil diubah!',
      icon: 'success',
    });
} else if (flashData == "PengajuanTundaBerhasil"){
    Toast.fire({
      title: 'Penundaan Skorsing berhasil diajukan!',
      icon: 'success',
    });
} else if (flashData == "PengajuanSuratBerhasil"){
  Toast.fire({
    title: 'Surat Keterangan Berkelakuan Baik berhasil diajukan!',
    icon: 'success',
  });
} else if (flashData == "PelanggaranDiloloskan"){
  Toast.fire({
    title: 'Pelanggaran berhasil diloloskan!',
    icon: 'success',
  });
} else if (flashData == "EditAkunBerhasil"){
  Toast.fire({
    title: 'Akun berhasil diedit!',
    icon: 'success',
  });
} else if (flashData == "TambahDataBerhasil"){
  Toast.fire({
    title: 'Data berhasil ditambahkan!',
    icon: 'success',
  });
} else if (flashData == "HapusDataBerhasil"){
  Toast.fire({
    title: 'Data berhasil dihapus!',
    icon: 'success',
  });
} else if (flashData == "PilihData"){
  Swal.fire({
      title: 'Tidak ada data yang dipilih',
      text: 'Silahkan pilih data terlebih dahulu!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "UbahRoleBerhasil"){
  Toast.fire({
    title: 'Role berhasil diubah!',
    icon: 'success',
  });
} else if (flashData == "JadwalTanggalSekarang"){
  Swal.fire({
      title: 'Tanggal tidak valid',
      text: 'Anda tidak diperbolehkan memasukkan jadwal hari ini atau sebelum hari ini!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "JadwalBukanTerbaru"){
  Swal.fire({
      title: 'Jadwal tidak valid',
      text: 'Jadwal yang anda masukan sama dengan jadwal lama!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "UsernameTerdaftar"){
  Swal.fire({
      title: 'Username sudah terdaftar',
      text: 'Silahkan masukkan username lain!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "EmailTerdaftar"){
  Swal.fire({
      title: 'Email sudah terdaftar',
      text: 'Silahkan masukkan email lain!',
      icon: 'warning',
      position: 'center',
      showConfirmButton: false,
      timer: 2500
    });
} else if (flashData == "ResetAkunBerhasil"){
  Toast.fire({
    title: 'Akun berhasil direset!',
    icon: 'success',
  });
}


const jadwalBaruForm = document.getElementById('jadwal-baru');
if (jadwalBaruForm){
  jadwalBaruForm.addEventListener('submit', function(e){
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
      text: "Seluruh jadwal sebelumnya akan terhapus jika jadwal baru ditambahkan",
      icon: 'warning',
      position: 'top',
      showCancelButton: true,
      confirmButtonText: 'Ya, lanjutkan',
      cancelButtonText: 'Tidak, batalkan',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $('#jadwal-baru').submit();
      }
    })
  });
}

const jadikanSA = document.getElementById('jadikanSA');
if(jadikanSA) {
  $('.jadikanSA').each(function (index){
    $(this).on("click", function(e){
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
      text: "Pengguna yang bersangkutan akan berubah hak akses menjadi super admin",
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
}

const jadikanAdmin = document.getElementById('jadikanAdmin');
if(jadikanAdmin) {
  $('.jadikanAdmin').each(function (index){
    $(this).on("click", function(e){
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
}

const jadikanDosen = document.getElementById('jadikanDosen');
if(jadikanDosen) {
  $('.jadikanDosen').each(function (index){
    $(this).on("click", function(e){
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
      text: "Pengguna yang bersangkutan akan berubah hak akses menjadi dosen",
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
}


const resetAkun = document.getElementById('resetAkun');
if(resetAkun) {
  $('.resetAkun').each(function (index){
    $(this).on("click", function(e){
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
}






