$(function () {  
  $(document).ready(function () {
    bsCustomFileInput.init();

    $("form").submit(function (e) {
      // $(this).find('[type="submit"]').attr('disabled',true);
      // $(this).find('[type="submit"]').addClass('disabled');
      $(":disabled").each(function (e) {
        $(this).css("background-color", "#e9ecef");
        $(this).removeAttr("disabled");
      });
    });

    $(document).on("hidden.bs.modal", function (e) {
      if ($(".modal").hasClass("show")) {
        $("body").addClass("modal-open");
      }
    });

    $(document).on("show.bs.modal", ".modal", function () {
      var zIndex = 1040 + 10 * $(".modal:visible").length;
      $(this).css("z-index", zIndex);
      setTimeout(function () {
        $(".modal-backdrop")
          .not(".modal-stack")
          .css("z-index", zIndex - 1)
          .addClass("modal-stack");
      }, 0);
    });

    // $('.format_uang').mask('Z000.Z000.Z000.Z000.Z000.Z000', {
    //   reverse: true,
    //   translation: {
    //     '0': {
    //       pattern: /-|\d/,
    //       recursive: true
    //     },'Z': {pattern: /[\-\+]/, optional: true}
    //   }
    // });

    // $('.format_persentase').mask('000.0', {reverse: true});

    // $('.format_notelp').mask('(0000) 0000-00009');

    // $(".select2").select2({
    //   width: "100%",
    //   theme: 'bootstrap4',
    // });

    // $(".myclockpicker").clockpicker({
    //   placement: "top",
    //   align: "left",
    //   autoclose: true,
    //   default: "now",
    // });

    // $(".myclockbpicker").clockpicker({
    //   placement: "bottom",
    //   align: "left",
    //   autoclose: true,
    //   default: "now",
    // });

    // $(".myclocklpicker").clockpicker({
    //   placement: "top",
    //   align: "left",
    //   autoclose: true,
    //   default: "now",
    // });

    // $(".myclockrpicker").clockpicker({
    //   placement: "top",
    //   align: "right",
    //   autoclose: true,
    //   default: "now",
    // });

    // $(".mydatepicker").datepicker({
    //   autoclose: true,
    //   format: "yyyy-mm-dd",
    //   endDate: Infinity,
    //   orientation: "bottom",
    // });

    // $(".mydatetoppicker").datepicker({
    //   autoclose: true,
    //   format: "yyyy-mm-dd",
    //   endDate: Infinity,
    //   orientation: "top",
    // });

    // var currentDate = new Date();
    // const monthNames = [
    //   "January",
    //   "February",
    //   "March",
    //   "April",
    //   "May",
    //   "June",
    //   "July",
    //   "August",
    //   "September",
    //   "October",
    //   "November",
    //   "December",
    // ];
    // const monthNamesShort = [
    //   "Jan",
    //   "Feb",
    //   "Mar",
    //   "Apr",
    //   "May",
    //   "Jun",
    //   "Jul",
    //   "Aug",
    //   "Sep",
    //   "Oct",
    //   "Nov",
    //   "Dec",
    // ];
    // $(".mymonthpicker")
    //   .datepicker({
    //     autoclose: true,
    //     useCurrent: true,
    //     format: "MM yyyy",
    //     viewMode: "months",
    //     minViewMode: "months",
    //     endDate: Infinity,
    //     orientation: "bottom",
    //   })
    //   .val(
    //     monthNames[currentDate.getMonth()] + " " + currentDate.getFullYear()
    //   );

    // $(".mymonthsnpicker")
    //   .datepicker({
    //     autoclose: true,
    //     useCurrent: true,
    //     format: "M yyyy",
    //     viewMode: "months",
    //     minViewMode: "months",
    //     endDate: Infinity,
    //     orientation: "bottom",
    //   })
    //   .val(
    //     monthNamesShort[currentDate.getMonth()] +
    //     " " +
    //     currentDate.getFullYear()
    //   );

    // $(".myyearpicker").datepicker({
    //   autoclose: true,
    //   useCurrent: true,
    //   format: "yyyy",
    //   viewMode: "years",
    //   minViewMode: "years",
    //   endDate: Infinity,
    //   orientation: "bottom",
    // });

    
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
  $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move');
  });


});


function tanggal_indo(string) {
	bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
    tanggal = string.split("-")[2];
    bulan = string.split("-")[1];
    tahun = string.split("-")[0];
    return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
}

function setFullDate(string, set_month = null) {
  month = set_month ?? ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    tanggal = string.split("-")[2];
    bulan = string.split("-")[1];
    tahun = string.split("-")[0];
    return tanggal + " " + month[Math.abs(bulan) -1] + " " + tahun;
}

function setMonthDate(string, set_month = null) {
  month = set_month ?? ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    tanggal = string.split("-")[2];
    bulan = string.split("-")[1];
    tahun = string.split("-")[0];
    return tanggal + " " + month[Math.abs(bulan) -1];
}

function getFormatDate(date, kurangDate = 0) {
  var year = date.getFullYear();

  var month = (1 + date.getMonth()).toString();
  month = month.length > 1 ? month : "0" + month;

  var day = (date.getDate() - kurangDate).toString();
  day = day.length > 1 ? day : "0" + day;

  return year + "-" + month + "-" + day;
}


function format_rupiah(angka, prefix){
  let appent = parseInt(angka) < 0 ?'-':'';
  angka = angka + '';
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + appent +rupiah : '');
}

function money_format(angka, prefix = null){
  let appent = parseInt(angka) < 0 ?'-':'';
  angka = angka + '';
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return  (prefix == null ? 'Rp. ' :'')  + appent + rupiah;
}


function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}