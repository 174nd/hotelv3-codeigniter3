<script>
  $(function() {
    var table_users = $('#table_users').DataTable({
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
          "set_tables": "SELECT * FROM users WHERE user_id!='<?= $this->session->userdata('user_id') ?>'",
          "query": true
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle text-center",
        "data": "username",
        "width": "50px",
      }, {
        'className': "align-middle",
        "data": "user_fullname",
      }, {
        'className': "align-middle text-center",
        "data": "user_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#users-delete #user_id\').val(\'' + data + '\')" data-target="#users-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('users/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_users_filter').hide();
    $('#field_users').keyup(function() {
      table_users.columns($('#column_users').val()).search(this.value).draw();
    });

  });
</script>