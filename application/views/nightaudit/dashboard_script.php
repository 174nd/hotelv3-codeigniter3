<!-- page script -->
<script>
  $(function() {
    $(".select2").select2({
      width: "100%",
      theme: 'bootstrap4',
    });
    $(".datepicker").datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      endDate: Infinity,
      orientation: "top",
    });

    function refreshDashboard() {
      $.ajax({
        type: "POST",
        url: "<?= base_url('nightaudit/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'refresh_dashboard',
        },
        success: function(data) {
          let set_rooms = ' <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup>';
          $(".small-box.vacant_ready h3").html(data['vacant_ready'] + set_rooms);
          $(".small-box.vacant_clean h3").html(data['vacant_clean'] + set_rooms);
          $(".small-box.occupied h3").html(data['occupied'] + set_rooms);
          $(".small-box.vacant_room h3").html(data['vacant_room'] + set_rooms);
          $(".small-box.vacant_dirty h3").html(data['vacant_dirty'] + set_rooms);
          $(".small-box.occupied_dirty h3").html(data['occupied_dirty'] + set_rooms);
          $(".small-box.occupied_clean h3").html(data['occupied_clean'] + set_rooms);
          $(".small-box.out_of_service h3").html(data['out_of_service'] + set_rooms);

          $(".small-box.expected_departure h3").html(data['expected_departure'] + ' <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup>');
          $(".small-box.expected_arrival h3").html(data['expected_arrival'] + ' <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup>');
        },
      });
    }

    setInterval(function() {
      refreshDashboard();
    }, 1000);


    $('#export-nightaudit').click(function() {
      $('#night_audit .overlay').removeClass('invisible');
      let date = $('#night_audit #date_report').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('nightaudit/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-nightaudit',
          'date': date,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            if (data['room_status'] == 'done') {
              $("#night_audit").submit();
            } else {
              $('#confirm_nightaudit').modal('show');
              $('#confirm_nightaudit b').html(moment(date).format('DD MMMM YYYY'));
              $('#confirm_nightaudit #date').val(date);
            }
            $('#night_audit .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });


    $('#confirm_nightaudit #submit_nightaudit').click(function() {
      let date = $("#confirm_nightaudit #date").val();
      if (date == '') {
        toastr.warning('<?= $this->lang->line('text-nightaudit_empty') ?>');
      } else {
        $('#confirm_nightaudit .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('nightaudit/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-submit_nightaudit',
            'date': date,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#confirm_nightaudit .overlay').addClass('invisible');
              $('#confirm_nightaudit').modal('hide');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });


    //////////////////////////////////////////////////////////////////////////////////////////////////

    var table_room_status_data = $('#table_room_status_data').DataTable({
      'paging': false,
      'info': true,
      'searching': true,
      'ordering': true,
      'autoWidth': false,
      "columns": [{
        'className': "align-middle text-center",
        "width": "30px",
      }, {
        'className': "align-middle text-center",
        "width": "50px",
      }, {
        'className': "align-middle text-center",
      }, {
        'className': "align-middle text-center",
        "width": "50p x",
      }, ],
      "order": [
        [0, "asc"]
      ],
    });
    $('#table_room_status_data_filter').hide();
    $('#field_room_status_data').keyup(function() {
      table_room_status_data.columns($('#column_room_status_data').val()).search(this.value).draw();
    });


    $('.col-6.col-md-3 .small-box').click(function() {
      let status = $(this).hasClass('vacant_ready') ? 'VR' : ($(this).hasClass('vacant_clean') ? 'VC' : ($(this).hasClass('vacant_dirty') ? 'VD' : ($(this).hasClass('occupied_clean') ? 'OC' : ($(this).hasClass('occupied_dirty') ? 'OD' : ($(this).hasClass('out_of_service') ? 'OO' : false)))));
      if (status) {
        $('#room-status-data').modal('show').find('.overlay').removeClass('invisible');
        console.log(status);
        $.ajax({
          type: "POST",
          url: "<?= base_url('nightaudit/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-room_status_data',
            'status': status,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              table_room_status_data.clear().draw();
              $(data['rooms']).each(function(index, hasil) {
                table_room_status_data.row.add([hasil['room_number'], hasil['floor_name'], hasil['room_type_name'], data['types']]).draw().node();
              });
              $('#room-status-data .overlay').addClass('invisible');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

  });
</script>