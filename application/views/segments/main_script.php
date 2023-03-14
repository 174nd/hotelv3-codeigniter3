<script>
  $(function() {
    var table_segments = $('#table_segments').DataTable({
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
          "set_tables": "segments",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "segment_name",
      }, {
        'className': "align-middle text-center",
        "data": "segment_type",
        "width": "90px",
      }, {
        'className': "align-middle text-center",
        "data": "segment_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#segments-delete #segment_id\').val(\'' + data + '\')" data-target="#segments-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('segments/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_segments_filter').hide();
    $('#field_segments').keyup(function() {
      table_segments.columns($('#column_segments').val()).search(this.value).draw();
    });

  });
</script>