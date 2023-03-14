<script>
  $(function() {
    var table_floors = $('#table_floors').DataTable({
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
          "set_tables": "floors",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "floor_name",
      }, {
        'className': "align-middle text-center",
        "data": "floor_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#floors-delete #floor_id\').val(\'' + data + '\')" data-target="#floors-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('floors/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_floors_filter').hide();
    $('#field_floors').keyup(function() {
      table_floors.columns(0).search(this.value).draw();
    });

  });
</script>