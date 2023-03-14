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
        url: "<?= base_url('housekeeping/get_data') ?>",
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


    $('button[data-target="#check-rooms"]').click(function() {
      $('#check-rooms .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('housekeeping/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-check_rooms',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#check-rooms .small-box').removeClass('bg-primary').removeClass('bg-orange').removeClass('bg-danger').removeClass('bg-warning').removeClass('bg-success').removeClass('bg-dark');
            $(data['rooms']).each(function(index, r1) {
              let bg_set;
              switch (r1['room_status']) {
                case 'VR':
                  bg_set = 'bg-primary';
                  break;
                case 'VC':
                  bg_set = 'bg-orange';
                  break;
                case 'VD':
                  bg_set = 'bg-danger';
                  break;
                case 'OD':
                  bg_set = 'bg-warning';
                  break;
                case 'OC':
                  bg_set = 'bg-success';
                  break;
                default:
                  bg_set = 'bg-dark';
              }
              $('#check-rooms .small-box[room_id="' + r1['room_id'] + '"]').addClass(bg_set);
            });
            $('#check-rooms .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });


    $('#check-rooms .small-box').click(function() {
      let room_id = $(this).attr('room_id');

      $('#room-status .overlay').removeClass('invisible');
      $('#room-status').modal('show');
      $.ajax({
        type: "POST",
        url: "<?= base_url('housekeeping/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-room_status',
          'room_id': room_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            console.log(data);
            $("#room-status #room_id").val(room_id);
            $("#room-status #room_status span").html(data['room_status']);
            $("#room-status #cleaning span").html(data['cleaning_description']);
            $("#room-status #rooms span").html(data['room_type_name'] + ' No. ' + data['room_number']);
            $('#room-status #change_status').empty().select2({
              data: data['change_status'],
              width: "100%",
              theme: 'bootstrap4',
            });

            if (data['room_status'] != '<?= $this->lang->line('text-OO') ?>') {
              $('#room-status #cleaning').addClass('d-none');
            } else {
              $('#room-status #cleaning').removeClass('d-none');
            }

            $('#room-status .cleaning_description').addClass('d-none').find('textarea').val('');
            $('#room-status .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });



    $('#room-status #change_status').change(function() {
      if ($(this).val() == 'OO') {
        $('#room-status .cleaning_description').removeClass('d-none');
      } else {
        $('#room-status .cleaning_description').addClass('d-none');
      }
    });

    $('#room-status #save').click(function() {
      let room_id = $("#room-status #room_id").val();
      let change_status = $("#room-status #change_status").val();
      let cleaning_description = $("#room-status #cleaning_description").val();

      if (room_id == '' || change_status == '' || (change_status == 'OO' && cleaning_description == '')) {
        toastr.warning('<?= $this->lang->line('text-room_status_empty') ?>');
      } else {
        $('#room-status .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('housekeeping/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-room_status',
            'room_id': room_id,
            'change_status': change_status,
            'cleaning_description': cleaning_description,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              toastr.success('<?= $this->lang->line('toast-room_status_change') ?>');
              $('#room-status .overlay').addClass('invisible');
              $('.modal').modal('hide');
            } else {
              if (data['status'] != 'none') $('#room-status .overlay').addClass('invisible');
              toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
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
          url: "<?= base_url('housekeeping/get_data') ?>",
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