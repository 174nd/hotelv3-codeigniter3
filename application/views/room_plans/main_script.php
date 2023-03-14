<script>
  $(function() {
    var table_room_plans = $('#table_room_plans').DataTable({
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
          "set_tables": "room_plans",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "room_plan_name",
      }, {
        'className': "align-middle text-center",
        "data": "room_plan_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#room_plans-delete #room_plan_id\').val(\'' + data + '\')" data-target="#room_plans-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('room_plans/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_room_plans_filter').hide();
    $('#field_room_plans').keyup(function() {
      table_room_plans.columns(0).search(this.value).draw();
    });

  });
</script>