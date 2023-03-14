<script>
  $(function() {
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


    var table_requests = $('#table_requests').DataTable({
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
          "set_tables": "requests",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "request_name",
      }, {
        'className': "align-middle text-center",
        "data": "request_price",
        "width": "100px",
        "render": function(data, type, row, meta) {
          return money_format(row['request_price']);
        }
      }, {
        'className': "align-middle text-center",
        "data": "request_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#requests-delete #request_id\').val(\'' + data + '\')" data-target="#requests-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('requests/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_requests_filter').hide();
    $('#field_requests').keyup(function() {
      table_requests.columns($('#column_requests').val()).search(this.value).draw();
    });

  });
</script>