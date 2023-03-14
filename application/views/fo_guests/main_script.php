<?php $this->lang->load('calendar'); ?><script>
  $(function() {
    $(".datepicker").datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      endDate: Infinity,
      orientation: "top",
    });


    var table_guests = $('#table_guests').DataTable({
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
          "set_tables": "guests",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle text-center",
        "data": "identity_number",
        "width": "110px",
      }, {
        'className': "align-middle",
        "data": "guest_name",
      }, {
        'className': "align-middle text-center",
        "data": "guest_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#guests-data" guest_id="' + data + '"><i class="fa fa-eye"></i></button></div>';
        }
      }],
    });
    $('#table_guests_filter').hide();
    $('#field_guests').keyup(function() {
      table_guests.columns($('#column_guests').val()).search(this.value).draw();
    });


    table_guests.on('click', 'button[data-target="#guests-data"]', function() {
      $('#guests-data .overlay').removeClass('invisible');
      let guest_id = $(this).attr('guest_id');
      $.ajax({
        type: "POST",
        "url": "<?= base_url('fo_guests/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'get_guests',
          'guest_id': guest_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#guests-data #guest_name span').html(data['guest_name']);
            $('#guests-data #national span').html(data['national']);
            $('#guests-data #identity span').html(data['identity_type'] + ' / ' + data['identity_number']);
            $('#guests-data #birth_date span').html(data['birth_date'] != null ? setFullDate(data['birth_date']) : '-');
            $('#guests-data #phone_number span').html(data['phone_number']);
            $('#guests-data #email span').html(data['email']);
            $('#guests-data #guest_address span').html(data['guest_address']);

            $('#guests-data .set-button .set-update').attr('href', '<?= base_url('fo_guests/update/') ?>' + guest_id);

            $('#guests-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });
  });
</script>