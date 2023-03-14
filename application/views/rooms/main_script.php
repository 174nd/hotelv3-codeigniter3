<script>
  $(function() {
    $(".select2").select2({
      width: "100%",
      theme: 'bootstrap4',
    });

    var table_rooms = $('#table_rooms').DataTable({
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
          "set_tables": "SELECT * FROM rooms JOIN room_types USING(room_type_id) JOIN floors USING(floor_id)",
          "query": true,
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle text-center",
        "data": "room_number",
        "width": "90px",
      }, {
        'className': "align-middle text-center",
        "data": "floor_name",
        "width": "70px",
      }, {
        'className': "align-middle text-center",
        "data": "room_type_name",
      }, {
        'className': "align-middle text-center",
        "data": "room_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#rooms-delete #room_id\').val(\'' + data + '\')" data-target="#rooms-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('rooms/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_rooms_filter').hide();
    $('#field_rooms').keyup(function() {
      table_rooms.columns($('#column_rooms').val()).search(this.value).draw();
    });

  });
</script>