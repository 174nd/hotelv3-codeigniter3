<?php $this->lang->load('calendar'); ?><script>
  $(function() {
    $(".datepicker").datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      endDate: Infinity,
      orientation: "top",
    });

    var table_sessions = $('#table_sessions').DataTable({
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
          "set_tables": "sessions",
        },
        "type": "POST"
      },
      "columns": [{
        'className': "align-middle",
        "data": "session_name",
      }, {
        'className': "align-middle text-center",
        "data": "session_id",
        "width": "10px",
        "render": function(data, type, row, meta) {
          return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sessions-data" session_id="' + data + '"><i class="fa fa-eye"></i></button></div>';
        }
      }],
    });
    $('#table_sessions_filter').hide();
    $('#field_sessions').keyup(function() {
      table_sessions.columns($('#column_sessions').val()).search(this.value).draw();
    });


    table_sessions.on('click', 'button[data-target="#sessions-data"]', function() {
      $('#sessions-data .overlay').removeClass('invisible');
      let session_id = $(this).attr('session_id');
      $.ajax({
        type: "POST",
        "url": "<?= base_url('sessions/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'get_sessions',
          'session_id': session_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#sessions-data #session_name span').html(data['session_name']);
            $('#sessions-data #session_length span').html(setMonthDate(data['start_session']) + ' - ' + setMonthDate(data['end_session']));

            $('#sessions-data .set-button .set-update').attr('href', '<?= base_url('sessions/update/') ?>' + session_id);
            $('#sessions-data .set-button .set-delete').unbind().click(function() {
              $('#sessions-delete #session_id').val(session_id);
              $('#sessions-delete').modal('show');
            });

            $('#sessions-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });
  });
</script>