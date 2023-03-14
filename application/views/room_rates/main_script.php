<script>
  $(function() {
    $(".select2").select2({
      width: "100%",
      theme: 'bootstrap4',
    });
    $('.money_format').mask('Z000.Z000.Z000.Z000.Z000.Z000', {
      reverse: true,
      translation: {
        '0': {
          pattern: /-|\d/,
          recursive: true
        },
        'Z': {
          pattern: /[\-\+]/,
          optional: true
        }
      }
    }).addClass('text-right');

    var table_room_rates = $('#table_room_rates').DataTable({
      'paging': true,
      'lengthChange': false,
      "pageLength": 10,
      'info': true,
      "order": [
        [0, "asc"]
      ],
      'searching': true,
      'ordering': true,
      'autoWidth': false,
      "language": {
        "paginate": {
          "previous": "<",
          "next": ">"
        }
      },

      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('ajax/getTables') ?>",
        "data": {
          "set_tables": "SELECT room_plan_name, room_rate_id, CONCAT(room_type_name, IF(session_name IS NOT NULL, CONCAT(' - ',session_name), '')) AS room_type_name FROM room_rates JOIN room_types USING(room_type_id) JOIN room_plans USING(room_plan_id) LEFT JOIN sessions USING(session_id)",
          'query': true,
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "room_type_name",
      }, {
        'className': "align-middle text-center",
        "data": "room_plan_name",
        "width": "100px",
      }, {
        'className': "align-middle text-center",
        "data": "room_rate_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#room_rates-data" room_rate_id="' + data + '"><i class="fa fa-eye"></i></button></div>';
        }
      }],
    });
    $('#table_room_rates_filter').hide();
    $('#field_room_rates').keyup(function() {
      table_room_rates.columns($('#column_room_rates').val()).search(this.value).draw();
    });


    var table_rooms = $('#table_rooms').DataTable({
      'paging': false,
      'info': false,
      'searching': false,
      'ordering': true,
      'autoWidth': false,
      "columns": [{
        'className': "align-middle",
      }, {
        'className': "align-middle text-center",
        "width": "10px",
      }, ],
      "order": [
        [0, "asc"]
      ],

    });


    table_room_rates.on('click', 'button[data-target="#room_rates-data"]', function() {
      $('#room_rates-data .overlay').removeClass('invisible');
      let room_rate_id = $(this).attr('room_rate_id');
      $.ajax({
        type: "POST",
        "url": "<?= base_url('room_rates/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'get_room_rates',
          'room_rate_id': room_rate_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {

            $('#room_rates-data #room_type_name span').html(data['room_type_name']);
            $('#room_rates-data #room_plan_name span').html(data['room_plan_name']);
            $('#room_rates-data #session_name span').html(data['session_name'] ?? '-');
            $('#room_rates-data #room_price span').html(money_format(data['room_price']));


            $('#room_rates-data .set-button .set-update').attr('href', '<?= base_url('room_rates/update/') ?>' + room_rate_id);
            $('#room_rates-data .set-button .set-delete').unbind().click(function() {
              $('#room_rates-delete #room_rate_id').val(room_rate_id);
              $('#room_rates-delete').modal('show');
            });

            table_rooms.clear().draw();
            $(data['rooms']).each(function(index, hasil) {
              let buttons = '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#rooms-delete #room_id\').val(\'' + hasil['room_id'] + '\')" data-target="#rooms-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('rooms/update/') ?>' + hasil['room_id'] + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
              table_rooms.row.add([hasil['room_number'], buttons]).draw();
            });

            $('#room_rates-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

  });
</script>