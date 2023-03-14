<script>
  $(function() {
    var table_payments = $('#table_payments').DataTable({
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
          "set_tables": "payments",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "payment_name",
      }, {
        'className': "align-middle text-center",
        "data": "payment_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#payments-delete #payment_id\').val(\'' + data + '\')" data-target="#payments-delete"><i class="fa fa-trash-alt"></i></button><a href="<?= base_url('payments/update/') ?>' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
        }
      }],
    });
    $('#table_payments_filter').hide();
    $('#field_payments').keyup(function() {
      table_payments.columns(0).search(this.value).draw();
    });

  });
</script>