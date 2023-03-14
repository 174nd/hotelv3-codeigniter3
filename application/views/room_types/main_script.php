<script>
  $(function() {
    var table_room_types = $('#table_room_types').DataTable({
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
          "set_tables": "room_types",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "room_type_name",
      }, {
        'className': "align-middle text-center",
        "data": "room_type_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#room_types-data" room_type_id="' + data + '"><i class="fa fa-eye"></i></button></div>';
        }
      }],
    });
    $('#table_room_types_filter').hide();
    $('#field_room_types').keyup(function() {
      table_room_types.columns(0).search(this.value).draw();
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


    table_room_types.on('click', 'button[data-target="#room_types-data"]', function() {
      $('#room_types-data .overlay').removeClass('invisible');
      let room_type_id = $(this).attr('room_type_id');
      $.ajax({
        type: "POST",
        "url": "<?= base_url('room_types/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'get_room_types',
          'room_type_id': room_type_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#room_types-data #room_type_name span').html(data['room_type_name']);
            $('#room_types-data #total_rooms span').html(data['total_rooms'] + ' <?= $this->lang->line('table-rooms') ?>');
            $('#room_types-data .set-button .set-update').attr('href', '<?= base_url('room_types/update/') ?>' + room_type_id);
            $('#room_types-data .set-button .set-delete').unbind().click(function() {
              $('#room_types-delete #room_type_id').val(room_type_id);
              $('#room_types-delete').modal('show');
            });

            table_rooms.clear().draw();
            $(data['rooms']).each(function(index, hasil) {
              let buttons = '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#rooms-delete #room_id\').val(\'' + hasil['room_id'] + '\')" data-target="#rooms-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('rooms/update/') ?>' + hasil['room_id'] + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
              table_rooms.row.add([hasil['room_number'], buttons]).draw();
            });

            $('#room_types-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

  });
</script>