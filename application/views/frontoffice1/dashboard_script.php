<!-- page script -->
<script>
  $(function() {
    let room_plans,
      checkin_date,
      checkout_date,
      in_house,
      reservation_id,
      set_rooms;

    $(".datepicker").datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      endDate: Infinity,
      orientation: "bottom",
    });

    $(".select2").select2({
      width: "100%",
      theme: 'bootstrap4',
    });

    $(".clockpicker").clockpicker({
      placement: "bottom",
      align: "right",
      autoclose: true,
      default: "now",
    }).addClass('text-center');

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

    function refreshDashboard() {
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'refresh_dashboard',
        },
        success: function(data) {
          let set_rooms = ' <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup>';
          $(".small-box.vacant_ready h3").html(data['vacant_ready'] + set_rooms);
          $(".small-box.vacant_clean h3").html(data['vacant_clean'] + set_rooms);
          $(".small-box.occupied h3").html(data['occupied'] + set_rooms);
          $(".small-box.vacant_room h3").html(data['vacant_room'] + set_rooms);
          $(".small-box.vacant_dirty h3").html(data['vacant_dirty'] + set_rooms);
          $(".small-box.occupied_dirty h3").html(data['occupied_dirty'] + set_rooms);
          $(".small-box.occupied_clean h3").html(data['occupied_clean'] + set_rooms);
          $(".small-box.out_of_service h3").html(data['out_of_service'] + set_rooms);

          $(".small-box.expected_departure h3").html(data['expected_departure'] + ' <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup>');
          $(".small-box.expected_arrival h3").html(data['expected_arrival'] + ' <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup>');
        },
      });
    }

    setInterval(function() {
      refreshDashboard();
    }, 1000);


    // Add Reservation
    $('button[data-target="#add-reservation"]').click(function() {
      $('#add-reservation .overlay').removeClass('invisible');
      $('#add-reservation').modal('show');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-add_reservation',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#add-reservation #checkin').datepicker("setDate", moment().format('YYYY-MM-DD'));
            $('#add-reservation #checkout').datepicker("setDate", moment().add(1, 'day').format('YYYY-MM-DD'));
            $('#add-reservation #room_plans').empty().select2({
              data: data['room_plans'],
              width: "100%",
              theme: 'bootstrap4',
            });
            $('#add-reservation .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#add-reservation #find_rooms').click(function() {
      room_plans = $('#add-reservation #room_plans').val();
      checkin_date = $('#add-reservation #checkin').val();
      checkout_date = $('#add-reservation #checkout').val();
      if (checkin_date == 0 || checkout_date == "") {
        toastr.warning("<?= $this->lang->line('text-checkin_checkout_empty') ?>");
      } else if (checkin_date == checkout_date) {
        toastr.warning("<?= $this->lang->line('text-checkin_checkout_same') ?>");
      } else if (checkin_date > checkout_date) {
        toastr.warning("<?= $this->lang->line('text-checkin_checkout_bigger') ?>");
      } else {
        $('#rooms-data .small-box').removeClass('active').removeClass('bg-primary').removeClass('bg-info').removeClass('bg-warning').removeClass('bg-danger').removeClass('bg-navy').removeClass('bg-dark').addClass('bg-dark');
        $('#rooms-data .overlay').removeClass('invisible');

        $('#rooms-data').modal('show');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-find_rooms',
            'room_plans': room_plans,
            'checkin': checkin_date,
            'checkout': moment(checkout_date, 'YYYY-MM-DD').add(-1, 'day').format('YYYY-MM-DD'),
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $(data['rooms']).each(function(index, result) {
                let bg_set;
                switch (result['room_status']) {
                  case 'ready':
                    bg_set = 'bg-primary';
                    break;
                  case 'clean':
                    bg_set = 'bg-info';
                    break;
                  case 'dirty':
                    bg_set = 'bg-warning';
                    break;
                  case 'occupied':
                    bg_set = 'bg-danger';
                    break;
                  case 'no_room_plans':
                    bg_set = 'bg-navy';
                    break;
                  case 'out_of_service':
                    bg_set = 'bg-dark';
                }
                $('#rooms-data .small-box[room_id="' + result['room_id'] + '"]').removeClass('bg-dark').addClass(bg_set).attr('room_rate_id', result['room_rate_id']);
                $('#rooms-data .small-box[room_id="' + result['room_id'] + '"] span').html(result['session_name']);
              });
              $('#rooms-data .overlay').addClass('invisible');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    $('#rooms-data .small-box').click(function() {
      if ($(this).hasClass('bg-primary') || $(this).hasClass('bg-info') || $(this).hasClass('bg-warning')) {
        $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
      } else if ($(this).hasClass('bg-danger')) {
        toastr.warning("<?= $this->lang->line('text-room_occupied') ?>");
      } else if ($(this).hasClass('bg-navy')) {
        toastr.error("<?= $this->lang->line('text-room_no_room_plans') ?>");
      } else if ($(this).hasClass('bg-dark')) {
        toastr.error("<?= $this->lang->line('text-room_out_of_service') ?>");
      }
    });

    $('#rooms-data #choose_rooms').click(function() {
      var rooms = [];
      $("#rooms-data .small-box.active").each(function() {
        rooms.push({
          'room_id': $(this).attr('room_id'),
          'room_rate_id': $(this).attr('room_rate_id'),
        });
      });
      if (rooms.length == 0) {
        toastr.warning("<?= $this->lang->line('text-room_no_choose') ?>");
      } else {
        $('#reservation-fill .overlay').removeClass('invisible');
        $('#reservation-fill').modal('show');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-choose_rooms',
            'rooms': rooms,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#reservation-fill #guest_id').empty().select2({
                data: data['guests'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#reservation-fill #segment_id').empty().select2({
                data: data['segments'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#reservation-fill #payment_id').empty().select2({
                data: data['payments'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#add-additional_costs_fill #room_id').empty().select2({
                data: data['room'],
                width: "100%",
                theme: 'bootstrap4',
              });

              in_house = parseInt(moment(checkout_date, "YYYY-MM-DD").diff(moment(checkin_date, "YYYY-MM-DD"), 'days'));

              $('#reservation-fill #in_house span').html(tanggal_indo(checkin_date) + ' - ' + tanggal_indo(checkout_date) + ' || ' + in_house + ' <?= $this->lang->line('text-night') ?>');

              let total_money = 0;
              $('#reservation-fill table tbody').html('');
              $(data['rooms']).each(function(index, result) {
                total_money += parseInt(result['room_price']) * in_house;
                let sessions = result['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + result['session_name'] + '</span>' : '';
                $('#reservation-fill table tbody').append('<tr class="room_' + result['room_id'] + '"><td colspan="3" class="align-middle">Room reserved type : ' + result['room_type_name'] + ' No. ' + result['room_number'] + sessions + '</td><td class="align-middle text-center"><div class="input-group input-group-sm"><div class="input-group-append"><span class="input-group-text">Rp.</span></div><input type="text" class="form-control money_format text-right room_price"  room_id="' + result['room_id'] + '" room_rate_id="' + result['room_rate_id'] + '" placeholder="<?= $this->lang->line('field-room_price') ?>" value="' + result['room_price'] + '"></div></td><td class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td class="align-middle text-center">' + money_format(parseInt(result['room_price']) * in_house) + '</td></tr>');
              });
              $('#reservation-fill table tbody').append('<tr><th class="text-center total_price" colspan="5"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total_money) + '</td></tr>');

              $('#reservation-fill table tbody .money_format').unbind().mask('Z000.Z000.Z000.Z000.Z000.Z000', {
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

              $('#reservation-fill #deposit').val('');
              $('#reservation-fill #checkin_time').val('');
              $('#reservation-fill #adult_guest').val('');
              $('#reservation-fill #child_guest').val('');
              $('#reservation-fill #status').val('Check-In').trigger('change');
              $('#reservation-fill .overlay').addClass('invisible');
            } else {
              toastr.error('Terjadi Kesalahan!');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    $('#reservation-fill table tbody').on('keyup', '.room_price', function() {
      $(this).closest('tr').find('td:eq(3)').html($(this).val() == '' ? 'Rp. 0' : money_format(parseInt($(this).val().replaceAll(".", "")) * in_house));
      setReservationFillTotalPrice();
    });

    function setReservationFillTotalPrice() {
      var total_payment = 0;
      $("#reservation-fill table tbody .room_price").each(function() {
        total_payment += ($(this).val().replaceAll(".", "") * in_house);
      });

      $('#reservation-fill table tbody .additional_costs').each(function() {
        let additional_cost_type = $(this).attr('additional_cost_type');
        let additional_cost_price = $(this).attr('additional_cost_price');
        total_payment = additional_cost_type == 'request' ? total_payment + parseInt(additional_cost_price) : total_payment - parseInt(additional_cost_price);
      });

      $('#reservation-fill table tbody tr').last().find('td').html(money_format(total_payment));
    }

    $('#reservation-fill #status').change(function() {
      if ($(this).val() == 'Check-In') {
        let val = $('#reservation-fill #segment_id').find("option:contains('Walk In')").val();
        $('#reservation-fill #segment_id').val(val).trigger('change.select2');
        $('#reservation-fill #segment_id').attr('disabled', true);
        $('#reservation-fill .checkin_support').removeClass('d-none');
      } else {
        $('#reservation-fill #segment_id').prop('selectedIndex', 0).trigger('change.select2');
        $('#reservation-fill #segment_id').attr('disabled', false);
        $('#reservation-fill .checkin_support').addClass('d-none');
      }
    });

    $('#reservation-fill #choose_rooms').click(function() {
      var rooms = [];
      $("#rooms-data .small-box.active").each(function() {
        rooms.push({
          'room_id': $(this).attr('room_id'),
          'room_rate_id': $(this).attr('room_rate_id'),
        });
      });
      if (rooms.length == 0) {
        toastr.warning("<?= $this->lang->line('text-room_no_choose') ?>");
      } else {
        $('#reservation-fill .overlay').removeClass('invisible');
        $('#reservation-fill').modal('show');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-choose_rooms',
            'rooms': rooms,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#reservation-fill #guest_id').empty().select2({
                data: data['guests'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#reservation-fill #segment_id').empty().select2({
                data: data['segments'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#add-additional_costs #room_id').empty().select2({
                data: data['room'],
                width: "100%",
                theme: 'bootstrap4',
              });

              in_house = parseInt(moment(checkout_date, "YYYY-MM-DD").diff(moment(checkin_date, "YYYY-MM-DD"), 'days'));

              $('#reservation-fill #in_house span').html(setFullDate(checkin_date) + ' - ' + setFullDate(checkout_date) + ' || ' + in_house + ' <?= $this->lang->line('text-night') ?>');

              let total_money = 0;
              $('#reservation-fill table tbody').html('');
              $(data['rooms']).each(function(index, result) {
                total_money += parseInt(result['room_price']) * in_house;
                let sessions = result['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + result['session_name'] + '</span>' : '';
                $('#reservation-fill table tbody').append('<tr class="room_' + result['room_id'] + '"><td colspan="3" class="align-middle">Room reserved type : ' + result['room_type_name'] + ' No. ' + result['room_number'] + sessions + '</td><td class="align-middle text-center"><div class="input-group input-group-sm"><div class="input-group-append"><span class="input-group-text">Rp.</span></div><input type="text" class="form-control money_format text-right room_price"  room_id="' + result['room_id'] + '" room_rate_id="' + result['room_rate_id'] + '" placeholder="room_price" value="' + result['room_price'] + '"></div></td><td class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td class="align-middle text-center">' + money_format(parseInt(result['room_price']) * in_house) + '</td></tr>');
              });
              $('#reservation-fill table tbody').append('<tr><th class="text-center total_price" colspan="5"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total_money) + '</td></tr>');

              $('#reservation-fill table tbody .money_format').unbind().mask('Z000.Z000.Z000.Z000.Z000.Z000', {
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

              $('#reservation-fill #deposit').val('');
              $('#reservation-fill #checkin_time').val('');
              $('#reservation-fill #adult_guest').val('');
              $('#reservation-fill #child_guest').val('');
              $('#reservation-fill #status').val('Check-In').trigger('change');
              $('#reservation-fill .overlay').addClass('invisible');
            } else {
              toastr.error('Terjadi Kesalahan!');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    $('#reservation-fill #save').click(function() {
      let rooms = [];
      let additional_costs = [];
      let guest_id = $("#reservation-fill #guest_id").val();
      let status = $("#reservation-fill #status").val();
      let segment_id = $("#reservation-fill #segment_id").val();
      let adult_guest = $("#reservation-fill #adult_guest").val();
      let child_guest = $("#reservation-fill #child_guest").val();
      let payment_id = $("#reservation-fill #payment_id").val();
      let deposit = $("#reservation-fill #deposit").val().replaceAll(".", "");
      let checkin_time = $("#reservation-fill #checkin_time").val();

      $("#reservation-fill table tbody .room_price").each(function() {
        rooms.push({
          'room_id': $(this).attr('room_id'),
          'room_rate_id': $(this).attr('room_rate_id'),
          'room_price': $(this).val().replaceAll(".", ""),
        });
      });

      $("#reservation-fill table tbody .additional_costs").each(function() {
        additional_costs.push({
          'room_id': $(this).attr('room_id'),
          'additional_cost_type': $(this).attr('additional_cost_type'),
          'additional_cost_description': $(this).attr('additional_cost_description'),
          'additional_cost_price': $(this).attr('additional_cost_price'),
        });
      });

      if (rooms.length <= 0 || guest_id == '' || segment_id == '' || adult_guest == '' || child_guest == '' || (status == 'Check-In' && (payment_id == '' || deposit == '' || checkin_time == ''))) {
        toastr.warning('<?= $this->lang->line('text-reservation_empty') ?>');
      } else {
        $('#reservation-fill .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-reservations',
            'rooms': rooms,
            'additional_costs': additional_costs,
            'checkin_date': checkin_date,
            'checkout_date': checkout_date,
            'guest_id': guest_id,
            'status': status,
            'segment_id': segment_id,
            'adult_guest': adult_guest,
            'child_guest': child_guest,
            'payment_id': payment_id,
            'deposit': deposit,
            'checkin_time': checkin_time,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              //print folio 
              window.open('<?= base_url('export/reservation/') ?>' + data['reservation_number'], '_blank');
              if (status == 'Check-In') window.open('<?= base_url('export/receipt/') ?>' + data['payment_number'], '_blank');
              toastr.success('<?= $this->lang->line('toast-add_reservation') ?>');
              $('#reservation-fill .overlay').addClass('invisible');
              $('.modal').modal('hide');
            } else {
              if (data['status'] != 'none') $('#reservation-fill .overlay').addClass('invisible');
              toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });



    // Add Guests
    $('#reservation-fill button[data-target="#add-guests"]').click(function() {
      $('#add-guests #guest_name').val('');
      $('#add-guests #national').val('Indonesia');
      $('#add-guests #identity_type').prop('selectedIndex', 0).trigger('change');
      $('#add-guests #identity_number').val('');
      $('#add-guests #phone_number').val('');
      $('#add-guests #birth_date').val('');
      $('#add-guests #email').val('');
      $('#add-guests #guest_address').val('');
    });

    $('#add-guests #save').click(function() {
      let guest_name = $('#add-guests #guest_name').val();
      let national = $('#add-guests #national').val();
      let identity_type = $('#add-guests #identity_type').val();
      let identity_number = $('#add-guests #identity_number').val();
      let phone_number = $('#add-guests #phone_number').val();
      let birth_date = $('#add-guests #birth_date').val();
      let email = $('#add-guests #email').val();
      let guest_address = $('#add-guests #guest_address').val();
      if (guest_name == '' || national == '' || identity_type == '' || identity_number == '') {
        toastr.warning('<?= $this->lang->line('text-guest_empty') ?>');
      } else {
        $('#add-guests .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-guests',
            'guest_name': guest_name,
            'national': national,
            'identity_type': identity_type,
            'identity_number': identity_number,
            'phone_number': phone_number,
            'birth_date': birth_date,
            'email': email,
            'guest_address': guest_address,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#reservation-fill #guest_id').empty().select2({
                data: data['guests'],
                width: "100%",
                theme: 'bootstrap4',
              });
              toastr.success('<?= $this->lang->line('toast-add_guests') ?>');
              $('#add-guests .overlay').addClass('invisible');
              $('#add-guests').modal('hide');
            } else {
              if (data['status'] != 'none') $('#add-guests .overlay').addClass('invisible');
              toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });



    // Add Additional Costs
    $('#reservation-fill button[data-target="#add-additional_costs_fill"]').click(function() {
      $('#add-additional_costs_fill .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-add_additional_costs',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#add-additional_costs_fill #request_id').empty().select2({
              data: data['requests'],
              width: "100%",
              theme: 'bootstrap4',
            });

            $('#add-additional_costs_fill #room_id').prop('selectedIndex', 0).trigger('change');
            $('#add-additional_costs_fill #additional_cost_description').val('');
            $('#add-additional_costs_fill #additional_cost_price').val('');
            $('#add-additional_costs_fill #additional_cost_type').val('request').trigger('change');
            $('#add-additional_costs_fill .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#add-additional_costs_fill #additional_cost_type').change(function() {
      if ($(this).val() == 'request') {
        $('#add-additional_costs_fill .price').addClass('d-none');
        $('#add-additional_costs_fill .description').addClass('d-none');
        $('#add-additional_costs_fill .requests').removeClass('d-none');
      } else {
        $('#add-additional_costs_fill .price').removeClass('d-none');
        $('#add-additional_costs_fill .description').removeClass('d-none');
        $('#add-additional_costs_fill .requests').addClass('d-none');
      }
    });

    $('#add-additional_costs_fill #save').click(function() {
      room_id = $('#add-additional_costs_fill #room_id').val();
      additional_cost_type = $('#add-additional_costs_fill #additional_cost_type').val();
      additional_cost_description = $('#add-additional_costs_fill #additional_cost_description').val();
      additional_cost_price = $('#add-additional_costs_fill #additional_cost_price').val();
      request_id = $('#add-additional_costs_fill #request_id').val();
      if (room_id == "" || request_id == "" || (additional_cost_type != 'request' && (additional_cost_price == '' || additional_cost_description == ''))) {
        toastr.warning("<?= $this->lang->line('text-additional_costs_empty') ?>");
      } else {
        $('#add-additional_costs_fill .overlay').removeClass('invisible');
        let result = additional_cost_type == 'request' ? request_id.split("||") : [additional_cost_description, additional_cost_price.replaceAll(".", "")];
        let text = additional_cost_type == 'request' ? '<?= $this->lang->line('text-request') ?>' : '<?= $this->lang->line('text-discount') ?>';
        $('<tr><td style="width: 0px;"><button type="button" class="btn btn-sm btn-danger delete-additional_costs"><i class="fa fa-trash-alt"></i></button></td><td style="width: 40px;" class="align-middle text-center">' + text + '</td><td class="align-middle" colspan="3">' + result[0] + '</td><td class="align-middle text-center"><input type="hidden" class="additional_costs" room_id="' + room_id + '" additional_cost_type="' + additional_cost_type + '" additional_cost_description="' + result[0] + '" additional_cost_price="' + result[1] + '">' + money_format(result[1]) + '</td></tr>').insertAfter('#reservation-fill table tbody .room_' + room_id);
        toastr.success('<?= $this->lang->line('toast-add_additional_costs') ?>');
        setReservationFillTotalPrice();
        $('#add-additional_costs_fill').modal('hide');
        $('#add-additional_costs_fill .overlay').addClass('invisible');
      }
    });

    $('#reservation-fill table tbody').on('click', '.delete-additional_costs', function() {
      $(this).closest('tr').remove();
      toastr.warning('<?= $this->lang->line('toast-delete_additional_costs') ?>');
      setReservationFillTotalPrice();
    });


    //////////////////////////////////////////////////////////////////////////////////////////////////


    // Reservation Data
    var table_reservation_data = $('#table_reservation_data').DataTable({
      'paging': false,
      'info': false,
      'searching': true,
      'ordering': true,
      'autoWidth': false,
      "columns": [{
        'className': "align-middle text-center",
        "width": "130px",
      }, {
        'className': "align-middle",
      }, {
        'className': "align-middle text-center",
        "width": "150px",
      }, {
        'className': "align-middle text-center",
        "width": "30px",
      }, ],
      "order": [
        [2, "asc"]
      ],
    });
    $('#table_reservation_data_filter').hide();
    $('#field_reservation_data').keyup(function() {
      table_reservation_data.columns($('#column_reservation_data').val()).search(this.value).draw();
    });

    function setTableReservationDetail(room_data) {
      let total = 0;
      let rooms = [];
      $('#reservation-detail table tbody').html('');
      $(room_data['rooms']).each(function(index, r1) {
        rooms.push({
          'id': r1['room_reservation_id'],
          'text': r1['room_type_name'] + ' No. ' + r1['room_number'],
        });

        let i = 0;
        let last_columm = '';
        $(r1['room_data']).each(function(index, r2) {
          let in_house = parseInt(moment(r2['checkout'], "YYYY-MM-DD").diff(moment(r2['checkin'], "YYYY-MM-DD"), 'days'));
          let sessions = r2['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + r2['session_name'] + '</span>' : '';
          total += parseInt(r2['room_price']) * in_house;
          if (i == 0) {
            $('#reservation-detail table tbody').append('<tr><td colspan="3" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
          } else {
            $('#reservation-detail table tbody').append('<tr><td class="align-middle text-center" rowspan="' + r2['row_number'] + '" style="width: 40px;"><?= $this->lang->line('text-change_rooms') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle" colspan="2">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
          }
          $(r2['price_change']).each(function(index, r3) {
            $('#reservation-detail table tbody').append('<tr><td class="align-middle text-center">' + money_format(r3) + '</td></tr>');
          });
          i++;
        });

        $(r1['additional_costs']).each(function(index, r2) {
          let cost_type;
          if (r2['additional_cost_type'] == 'discount') {
            total = total - parseInt(r2['additional_cost_price']);
            cost_type = "<?= $this->lang->line('text-discount') ?>";
          } else {
            total = total + parseInt(r2['additional_cost_price']);
            cost_type = r2['additional_cost_type'] == 'request' ? "<?= $this->lang->line('text-request') ?>" : "<?= $this->lang->line('text-loss_or_damage') ?>";;
          }
          $('#reservation-detail table tbody').append('<tr><td class="align-middle text-center" style="width: 40px;"><button type="button" class="btn btn-sm btn-danger delete-additional_costs" additional_cost_id="' + r2['additional_cost_id'] + '" reservation_id="' + r1['reservation_id'] + '"><i class="fa fa-trash-alt"></i></td><td class="align-middle text-center" style="width: 40px;">' + cost_type + '</td><td colspan="3" class="align-middle">' + r2['additional_cost_description'] + '</td><td class="align-middle text-center">' + money_format(r2['additional_cost_price']) + '</td></tr>');
        });
      });
      $('#reservation-detail table tbody').append('<tr><th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total) + '</td></tr>');
      return rooms;
    }

    $('button[data-target="#reservation-data"]').click(function() {
      $('#reservation-data .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-reservation_data',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            table_reservation_data.clear().draw();
            $(data['reservations']).each(function(index, hasil) {
              let hasil_button = '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" reservation_id="' + hasil['reservation_id'] + '" data-toggle="modal" data-target="#reservation-detail"><i class="fa fa-eye"></i></button></div>';
              // table_reservation_data.row.add([hasil['nm_tamu'], hasil['lama_inap'], format_rupiah(hasil['harga_tkamar'], 'Rp.'), hasil_button]).draw().node();
              table_reservation_data.row.add([hasil['reservation_number'], hasil['guest_name'], hasil['in_house'], hasil_button]).draw().node();
            });
            $('#reservation-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    table_reservation_data.on('click', 'button[data-target="#reservation-detail"]', function() {
      $('#reservation-detail .overlay').removeClass('invisible');
      let reservation_id = $(this).attr('reservation_id');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-reservation_detail',
          'reservation_id': reservation_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            let reservations = data['reservations'];
            let in_house = parseInt(moment(reservations['checkout_schedule'], "YYYY-MM-DD").diff(moment(reservations['checkin_schedule'], "YYYY-MM-DD"), 'days'));
            $('#reservation-detail #guest_name span').html(reservations['guest_name']);
            $('#reservation-detail #reservation_number span').html(reservations['reservation_number']);
            $('#reservation-detail #segment span').html(reservations['segment_name'] + ' - ' + reservations['segment_type']);
            $('#reservation-detail #in_house span').html(setFullDate(reservations['checkin_schedule']) + ' - ' + setFullDate(reservations['checkout_schedule']) + ' || ' + in_house + ' <?= $this->lang->line('text-night') ?>');

            $('#reservation-detail #total_guest span').html(reservations['adult_guest'] + ' <?= $this->lang->line('text-adult') ?>' + (reservations['child_guest'] != null ? ' & ' + reservations['child_guest'] + ' <?= $this->lang->line('text-child') ?>' : ''));

            $('#reservation-detail #room_plan_name span').html(reservations['room_plan_name']);
            $('#reservation-detail #reservation_time span').html(reservations['reservation_time']);

            let rooms = setTableReservationDetail(data['rooms']);


            //print folio
            $('#reservation-detail #print_folio').attr('href', '<?= base_url('export/reservation/') ?>' + reservations['reservation_number']);


            $('#add-additional_costs_data #room_id').empty().select2({
              data: rooms,
              width: "100%",
              theme: 'bootstrap4',
            });
            $('#checkin-reservation #checkin_date').val(reservations['checkin_schedule']);
            $('#add-additional_costs_data #reservation_id').val(reservation_id);
            $('#checkin-reservation #reservation_id').val(reservation_id);
            $('#cancel-reservation #reservation_id').val(reservation_id);
            $('#reservation-detail .overlay').addClass('invisible');
          } else {
            toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    // Check-In Reservation Data
    $('#reservation-detail button[data-target="#checkin-reservation"]').click(function() {
      $('#checkin-reservation .overlay').removeClass('invisible');
      $('#checkin-reservation').modal('show');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-checkin_reservation',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#checkin-reservation #payment_id').empty().select2({
              data: data['payments'],
              width: "100%",
              theme: 'bootstrap4',
            });

            $('#checkin-reservation #deposit').val('');
            $('#checkin-reservation #checkin_time').val('');
            $('#checkin-reservation .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#checkin-reservation #save').click(function() {
      let reservation_id = $('#checkin-reservation #reservation_id').val();
      let deposit = $('#checkin-reservation #deposit').val().replaceAll(".", "");
      let checkin_date = $('#checkin-reservation #checkin_date').val();
      let checkin_time = $('#checkin-reservation #checkin_time').val();
      let payment_id = $('#checkin-reservation #payment_id').val();
      if (reservation_id == "" || deposit == "" || checkin_time == "" || payment_id == '') {
        toastr.warning("<?= $this->lang->line('text-checkin_reservation_empty') ?>");
      } else {
        $('#checkin-reservation .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-checkin_reservation',
            'reservation_id': reservation_id,
            'deposit': deposit,
            'checkin_date': checkin_date,
            'checkin_time': checkin_time,
            'payment_id': payment_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              window.open('<?= base_url('export/receipt/') ?>' + data['payment_number'], '_blank');
              toastr.success('<?= $this->lang->line('toast-add_checkin_reservation') ?>');
              $('#checkin-reservation .overlay').addClass('invisible');
              $('.modal').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Cancel Reservation Data
    $('#cancel-reservation #save').click(function() {
      let reservation_id = $('#cancel-reservation #reservation_id').val();
      if (reservation_id == "") {
        toastr.warning("<?= $this->lang->line('text-checkin_reservation_empty') ?>");
      } else {
        $('#cancel-reservation .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-cancel_reservation',
            'reservation_id': reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              toastr.success('<?= $this->lang->line('toast-cancel_reservation') ?>');
              $('#cancel-reservation .overlay').addClass('invisible');
              $('.modal').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Add Additional Costs
    $('#reservation-detail button[data-target="#add-additional_costs_data"]').click(function() {
      $('#add-additional_costs_data .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-add_additional_costs',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#add-additional_costs_data #request_id').empty().select2({
              data: data['requests'],
              width: "100%",
              theme: 'bootstrap4',
            });

            $('#add-additional_costs_data #room_id').prop('selectedIndex', 0).trigger('change');
            $('#add-additional_costs_data #additional_cost_description').val('');
            $('#add-additional_costs_data #additional_cost_price').val('');
            $('#add-additional_costs_data #additional_cost_type').val('request').trigger('change');
            $('#add-additional_costs_data .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#add-additional_costs_data #additional_cost_type').change(function() {
      if ($(this).val() == 'request') {
        $('#add-additional_costs_data .price').addClass('d-none');
        $('#add-additional_costs_data .description').addClass('d-none');
        $('#add-additional_costs_data .requests').removeClass('d-none');
      } else {
        $('#add-additional_costs_data .price').removeClass('d-none');
        $('#add-additional_costs_data .description').removeClass('d-none');
        $('#add-additional_costs_data .requests').addClass('d-none');
      }
    });

    $('#add-additional_costs_data #save').click(function() {
      let room_id = $('#add-additional_costs_data #room_id').val();
      let reservation_id = $('#add-additional_costs_data #reservation_id').val();
      let additional_cost_type = $('#add-additional_costs_data #additional_cost_type').val();
      let additional_cost_description = $('#add-additional_costs_data #additional_cost_description').val();
      let additional_cost_price = $('#add-additional_costs_data #additional_cost_price').val().replaceAll(".", "");
      let request_id = $('#add-additional_costs_data #request_id').val();
      if (reservation_id == "" || room_id == "" || request_id == "" || (additional_cost_type != 'request' && (additional_cost_price == '' || additional_cost_description == ''))) {
        toastr.warning("<?= $this->lang->line('text-additional_costs_empty') ?>");
      } else {
        $('#add-additional_costs_data .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-add_additional_costs',
            'room_id': room_id,
            'reservation_id': reservation_id,
            'additional_cost_type': additional_cost_type,
            'additional_cost_description': additional_cost_description,
            'additional_cost_price': additional_cost_price,
            'request_id': request_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableReservationDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-add_additional_costs') ?>');
              $('#add-additional_costs_data .overlay').addClass('invisible');
              $('#add-additional_costs_data').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Delete Additional Costs
    $('#reservation-detail').on('click', '.delete-additional_costs', function() {
      $('#delete-additional_costs_data').modal('show');
      $('#delete-additional_costs_data #additional_cost_id').val($(this).attr('additional_cost_id'));
      $('#delete-additional_costs_data #reservation_id').val($(this).attr('reservation_id'));
    });

    $('#delete-additional_costs_data #save').click(function() {
      let additional_cost_id = $('#delete-additional_costs_data #additional_cost_id').val();
      let reservation_id = $('#delete-additional_costs_data #reservation_id').val();
      if (additional_cost_id == "" || reservation_id == "" || reservation_id == "undefined") {
        toastr.warning("<?= $this->lang->line('text-delete_additional_costs_empty') ?>");
      } else {
        $('#delete-additional_costs_data .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-delete_additional_costs',
            'additional_cost_id': additional_cost_id,
            'reservation_id': reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableReservationDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-cancel_reservation') ?>');
              $('#delete-additional_costs_data .overlay').addClass('invisible');
              $('#delete-additional_costs_data').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });


    //////////////////////////////////////////////////////////////////////////////////////////////////


    var table_checkin_data = $('#table_checkin_data').DataTable({
      'paging': false,
      'info': false,
      'searching': true,
      'ordering': true,
      'autoWidth': false,
      "columns": [{
        'className': "align-middle text-center",
        "width": "130px",
      }, {
        'className': "align-middle",
      }, {
        'className': "align-middle text-center",
        "width": "150px",
      }, {
        'className': "align-middle text-center",
        "width": "30px",
      }, ],
      "order": [
        [2, "asc"]
      ],
    });
    $('#table_checkin_data_filter').hide();
    $('#field_checkin_data').keyup(function() {
      table_checkin_data.columns($('#column_checkin_data').val()).search(this.value).draw();
    });

    function setTableCheckinDetail(room_data) {
      let total = 0;
      let rooms = [];
      $('#checkin-detail table tbody').html('');
      $('#change-rooms .small-box').removeClass('active').removeClass('bg-success').removeClass('bg-primary').removeClass('bg-info').removeClass('bg-warning').removeClass('bg-danger').removeClass('bg-navy').removeClass('bg-dark').removeAttr('room_reservation_id').removeAttr('checkout');
      $(room_data['rooms']).each(function(index, r1) {
        rooms.push({
          'id': r1['room_reservation_id'],
          'text': r1['room_type_name'] + ' No. ' + r1['room_number'],
        });

        $('#change-rooms .small-box[room_id="' + r1['room_id'] + '"]').removeClass('bg-dark').addClass('bg-success').attr('room_reservation_id', r1['room_reservation_id']).attr('checkout', r1['room_data'][0]['checkout']);
        let i = 0;
        let last_columm = '';
        $(r1['room_data']).each(function(index, r2) {
          let in_house = parseInt(moment(r2['checkout'], "YYYY-MM-DD").diff(moment(r2['checkin'], "YYYY-MM-DD"), 'days'));
          let sessions = r2['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + r2['session_name'] + '</span>' : '';
          total += parseInt(r2['room_price']) * in_house;
          if (i == 0) {
            $('#checkin-detail table tbody').append('<tr><td colspan="4" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
          } else {
            $('#checkin-detail table tbody').append('<tr><td class="align-middle text-center" rowspan="' + r2['row_number'] + '" style="width: 40px;"><?= $this->lang->line('text-change_rooms') ?></td><td colspan="3" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
          }
          $(r2['price_change']).each(function(index, r3) {
            $('#checkin-detail table tbody').append('<tr><td class="align-middle text-center">' + money_format(r3) + '</td></tr>');
          });
          i++;
        });

        $(r1['additional_costs']).each(function(index, r2) {
          let cost_type;
          if (r2['additional_cost_type'] == 'discount') {
            total = total - parseInt(r2['additional_cost_price']);
            cost_type = "<?= $this->lang->line('text-discount') ?>";
          } else {
            total = total + parseInt(r2['additional_cost_price']);
            cost_type = r2['additional_cost_type'] == 'request' ? "<?= $this->lang->line('text-request') ?>" : "<?= $this->lang->line('text-loss_or_damage') ?>";;
          }
          $('#checkin-detail table tbody').append('<tr><td class="align-middle text-center" style="width: 40px;"><button type="button" class="btn btn-sm btn-danger delete-additional_costs" additional_cost_id="' + r2['additional_cost_id'] + '" reservation_id="' + r1['reservation_id'] + '"><i class="fa fa-trash-alt"></i></td><td class="align-middle text-center" style="width: 40px;">' + cost_type + '</td><td colspan="4" class="align-middle">' + r2['additional_cost_description'] + '</td><td class="align-middle text-center">' + money_format(r2['additional_cost_price']) + '</td></tr>');
        });
      });
      $('#checkin-detail table tbody').append('<tr><th class="text-center align-middle" colspan="6"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total) + '</td></tr>');

      if (room_data['payment_histories'].length > 0) {
        $(room_data['payment_histories']).each(function(index, r1) {
          total = total - parseInt(r1['total_payment']);
          $('#checkin-detail table tbody').append('<tr><td colspan="3" class="align-middle text-center" style="width: 100px;">' + setFullDate(r1['payment_date']) + '</td><td colspan="3" class="align-middle">' + r1['payment_desciption'] + '</td><td class="align-middle text-center">' + money_format(r1['total_payment']) + '</td></tr>');
        });

        let line = total > 0 ? '<?= $this->lang->line('text-remaining_payment') ?>' : '<?= $this->lang->line('text-remaining_return') ?>';
        $('#checkin-detail table tbody').append('<tr><th class="text-center align-middle" colspan="6">' + line + '</th><td class="text-center">' + money_format(Math.abs(total)) + '</td></tr>');
      }

      $('#checkout-reservation #remaining_payment').val(total).trigger('input');
      return {
        'rooms': rooms,
        'total': total,
        'room_plan_id': room_data['room_plan_id'],
      };
    }

    $('button[data-target="#checkin-data"]').click(function() {
      $('#checkin-data .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-checkin_data',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            table_checkin_data.clear().draw();
            $(data['reservations']).each(function(index, hasil) {
              let hasil_button = '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" reservation_id="' + hasil['reservation_id'] + '" data-toggle="modal" data-target="#checkin-detail"><i class="fa fa-eye"></i></button></div>';
              // table_checkin_data.row.add([hasil['nm_tamu'], hasil['lama_inap'], format_rupiah(hasil['harga_tkamar'], 'Rp.'), hasil_button]).draw().node();
              table_checkin_data.row.add([hasil['reservation_number'], hasil['guest_name'], hasil['in_house'], hasil_button]).draw().node();
            });
            $('#checkin-data .overlay').addClass('invisible');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    table_checkin_data.on('click', 'button[data-target="#checkin-detail"]', function() {
      $('#checkin-detail .overlay').removeClass('invisible');
      let reservation_id = $(this).attr('reservation_id');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-checkin_detail',
          'reservation_id': reservation_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            let reservations = data['reservations'];
            let in_house = parseInt(moment(reservations['checkout_schedule'], "YYYY-MM-DD").diff(moment(reservations['checkin_schedule'], "YYYY-MM-DD"), 'days'));
            $('#checkin-detail #guest_name span').html(reservations['guest_name']);
            $('#checkin-detail #reservation_number span').html(reservations['reservation_number']);
            $('#checkin-detail #segment span').html(reservations['segment_name'] + ' - ' + reservations['segment_type']);
            $('#checkin-detail #in_house span').html(setFullDate(reservations['checkin_schedule']) + ' - ' + setFullDate(reservations['checkout_schedule']) + ' || ' + in_house + ' <?= $this->lang->line('text-night') ?>');

            $('#checkin-detail #total_guest span').html(reservations['adult_guest'] + ' <?= $this->lang->line('text-adult') ?>' + (reservations['child_guest'] != null ? ' & ' + reservations['child_guest'] + ' <?= $this->lang->line('text-child') ?>' : ''));

            $('#checkin-detail #checkin_time span').html(reservations['checkin_time']);
            $('#checkin-detail #room_plan_name span').html(reservations['room_plan_name']);
            $('#checkin-detail #reservation_time span').html(reservations['reservation_time']);


            //print folio
            $('#checkin-detail #print_folio').attr('href', '<?= base_url('export/reservation/') ?>' + reservations['reservation_number']);

            let xresult = setTableCheckinDetail(data['rooms']);

            $('#checkin-detail button[data-target="#change-rooms"]').attr('room_plans', data['rooms']['room_plan_id']);
            $('#checkin-detail button[data-target="#change-rooms"]').attr('checkout_time', reservations['checkout_schedule']);

            $('#extend-days #reservation_id').val(reservation_id);
            $('#extend-days #checkout_schedule').val(reservations['checkout_schedule']);
            $('#add-additional_costs_checkin #room_id').empty().select2({
              data: xresult['rooms'],
              width: "100%",
              theme: 'bootstrap4',
            });
            $('#add-additional_costs_checkin #reservation_id').val(reservation_id);
            $('#add-payment #reservation_id').val(reservation_id);
            $('#checkin-detail .overlay').addClass('invisible');
          } else {
            toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    // Check-Out Reservation Data
    $('#checkin-detail button[data-target="#checkout-reservation"]').click(function() {
      $('#checkout-reservation .overlay').removeClass('invisible');
      $('#checkout-reservation').modal('show');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-checkout_reservation',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#checkout-reservation #payment_id').empty().select2({
              data: data['payments'],
              width: "100%",
              theme: 'bootstrap4',
            });

            $('#checkout-reservation #deposit').val('');
            $('#checkout-reservation #checkout_time').val('');
            $('#checkout-reservation .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#checkout-reservation #save').click(function() {
      let checkout_time = $('#checkout-reservation #checkout_time').val();
      let checkout_date = $('#checkout-reservation #checkout_date').val();
      let payment_id = $('#checkout-reservation #payment_id').val();
      let receipt_type = $('#checkout-reservation #receipt_type').val();
      let reservation_id = $('#checkout-reservation #reservation_id').val();
      let remaining_payment = $('#checkout-reservation #remaining_payment').val().replaceAll('.', '');
      if (checkout_time == "" || checkout_date == "" || payment_id == "" || receipt_type == "" || reservation_id == '' || remaining_payment == '') {
        toastr.warning("<?= $this->lang->line('text-checkout_reservation_empty') ?>");
      } else {
        $('#checkout-reservation .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-checkout_reservation',
            'checkout_time': checkout_time,
            'checkout_date': checkout_date,
            'payment_id': payment_id,
            'receipt_type': receipt_type,
            'reservation_id': reservation_id,
            'remaining_payment': remaining_payment,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              toastr.success('<?= $this->lang->line('toast-checkout_reservation') ?>');
              window.open((receipt_type == 'invoice' ? '<?= base_url('export/invoice/') ?>' : '<?= base_url('export/bill/') ?>') + data['bill_number'], '_blank');
              window.open('<?= base_url('export/receipt/') ?>' + data['payment_number'], '_blank');
              $('#checkout-reservation .overlay').addClass('invisible');
              $('.modal').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Change Rooms
    $('#checkin-detail button[data-target="#change-rooms"]').click(function() {
      $('#change-rooms .overlay').removeClass('invisible');
      let room_plans = $(this).attr('room_plans');
      let checkout_time = $(this).attr('checkout_time');
      $('#change-rooms').modal('show');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-change_rooms',
          'room_plans': room_plans,
          'checkout': moment(checkout_date, 'YYYY-MM-DD').add(-1, 'day').format('YYYY-MM-DD'),
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#change-rooms .small-box[room_rate_id]').removeClass('active').removeClass('bg-primary').removeClass('bg-info').removeClass('bg-warning').removeClass('bg-danger').removeClass('bg-navy').removeClass('bg-dark');
            $(data['rooms']).each(function(index, result) {
              let bg_set;
              switch (result['room_status']) {
                case 'ready':
                  bg_set = 'bg-primary';
                  break;
                case 'clean':
                  bg_set = 'bg-info';
                  break;
                case 'dirty':
                  bg_set = 'bg-warning';
                  break;
                case 'occupied':
                  bg_set = 'bg-danger';
                  break;
                case 'no_room_plans':
                  bg_set = 'bg-navy';
                  break;
                case 'out_of_service':
                  bg_set = 'bg-dark';
              }
              if (!$('#change-rooms .small-box[room_id="' + result['room_id'] + '"]').hasClass('bg-success')) {
                $('#change-rooms .small-box[room_id="' + result['room_id'] + '"]').removeClass('bg-dark').addClass(bg_set).attr('room_rate_id', result['room_rate_id']);
              }
              $('#change-rooms .small-box[room_id="' + result['room_id'] + '"] span').html(result['session_name']);
            });
            $('#change-rooms .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#change-rooms .small-box').click(function() {
      let last_rules = ($(this).hasClass('bg-success') && $('#change-rooms .small-box.bg-success.active').length == 0) || (!$(this).hasClass('bg-success') && (($('#change-rooms .small-box.bg-success.active').length == 0 && $('#change-rooms .small-box.active').length == 0) || ($('#change-rooms .small-box.bg-success.active').length == 1 && $('#change-rooms .small-box.active').length == 1)));
      if ($(this).hasClass('bg-danger')) {
        toastr.warning("<?= $this->lang->line('text-room_occupied') ?>");
      } else if ($(this).hasClass('bg-navy')) {
        toastr.error("<?= $this->lang->line('text-room_no_room_plans') ?>");
      } else if ($(this).hasClass('bg-dark')) {
        toastr.error("<?= $this->lang->line('text-room_out_of_service') ?>");
      } else if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      } else if (last_rules) {
        $(this).addClass('active');
        if ($('#change-rooms .small-box.active').length == 2) {
          //////// xxxxxxxxxxxxx
          let room_id = $('#change-rooms .small-box.active[room_rate_id]').attr('room_id');
          let room_rate_id = $('#change-rooms .small-box.active[room_rate_id]').attr('room_rate_id');
          let checkout = $('#change-rooms .small-box.bg-success.active').attr('checkout');
          let room_reservation_id = $('#change-rooms .small-box.bg-success.active').attr('room_reservation_id');
          $('#check-rooms-change').modal('show');
          $('#check-rooms-change .overlay').removeClass('invisible');
          $.ajax({
            type: "POST",
            url: "<?= base_url('frontoffice/get_data') ?>",
            dataType: "JSON",
            data: {
              'set': 'start-check_change_rooms',
              'room_id': room_id,
              'room_rate_id': room_rate_id,
              'room_reservation_id': room_reservation_id,
            },
            success: function(data) {
              if (data['status'] == 'done') {
                let total = 0;
                let sessions;
                let in_house = parseInt(moment(checkout, "YYYY-MM-DD").diff(moment(), 'days')) + 1;
                $('#check-rooms-change table tbody').html('');

                let room_before = data['room_before'];
                sessions = room_before['sessions'] != null ? '<br><span class="font-italic">Sessions : ' + room_before['sessions'] + '</span>' : '';
                $('#check-rooms-change table tbody').append('<tr><td colspan="3" class="align-middle">Room reserved type : ' + room_before['room_type_name'] + ' No. ' + room_before['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(room_before['room_price']) + '</td><td class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td class="align-middle text-center">' + money_format(parseInt(room_before['room_price']) * in_house) + '</td></tr>');

                let room_change = data['room_change'];
                sessions = room_change['sessions'] != null ? '<br><span class="font-italic">Sessions : ' + room_change['sessions'] + '</span>' : '';
                $('#check-rooms-change table tbody').append('<tr><td colspan="3" class="align-middle">Room reserved type : ' + room_change['room_type_name'] + ' No. ' + room_change['room_number'] + sessions + '</td><td class="align-middle text-center"><div class="input-group input-group-sm"><div class="input-group-append"><span class="input-group-text">Rp.</span></div><input type="text" class="form-control money_format text-right room_price"  room_id="' + room_change['room_id'] + '" room_rate_id="' + room_change['room_rate_id'] + '" room_reservation_id="' + room_reservation_id + '" in_house="' + in_house + '" price_before="' + room_before['room_price'] + '" placeholder="<?= $this->lang->line('text-room_price') ?>" value="' + room_change['room_price'] + '"></div></td><td class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td class="align-middle text-center total_change">' + money_format(parseInt(room_change['room_price']) * in_house) + '</td></tr>');

                $('#check-rooms-change table tbody').append('<tr><td colspan="3"></td><td class="align-middle text-center price_difference">' + money_format(parseInt(room_change['room_price']) - parseInt(room_before['room_price'])) + '</td><td></td><td class="align-middle text-center total_difference">' + money_format((parseInt(room_change['room_price']) - parseInt(room_before['room_price'])) * in_house) + '</td></tr>');



                $('#check-rooms-change table tbody .money_format').mask('Z000.Z000.Z000.Z000.Z000.Z000', {
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

                $('#check-rooms-change .overlay').addClass('invisible');
                $('#check-rooms-change').modal('hide');
              } else {
                toastr.error('<?= $this->lang->line('text-toast-error') ?>');
              }
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        }
      }
    });

    $('#check-rooms-change  table tbody').on('keyup', '.room_price', function() {
      let in_house = parseInt($(this).attr('in_house'));
      let room_price = parseInt($(this).attr('price_before'));
      let price_change = $(this).val() == '' ? 0 : parseInt($(this).val().replaceAll('.', ''));
      $('#check-rooms-change .total_change').html(money_format(price_change * in_house));
      $('#check-rooms-change .price_difference').html(money_format(Math.abs(price_change - room_price)));
      $('#check-rooms-change .total_difference').html(money_format(Math.abs((price_change - room_price) * in_house)));
    });

    $('#check-rooms-change #save').click(function() {
      let room_price = $('#check-rooms-change .room_price').val().replaceAll('.', '');
      let room_id = $('#check-rooms-change .room_price').attr('room_id');
      let room_rate_id = $('#check-rooms-change .room_price').attr('room_rate_id');
      let room_reservation_id = $('#check-rooms-change .room_price').attr('room_reservation_id');
      if (room_price == "" || room_id == "" || room_rate_id == "" || room_reservation_id == "") {
        toastr.warning("<?= $this->lang->line('text-check_rooms_change_empty') ?>");
      } else {
        $('#check-rooms-change .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-check_rooms_change',
            'room_id': room_id,
            'room_price': room_price,
            'room_rate_id': room_rate_id,
            'room_reservation_id': room_reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-change_rooms') ?>');
              $('#check-rooms-change .overlay').addClass('invisible');
              $('#check-rooms-change').modal('hide');
              $('#change-rooms').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });


    // Extend Days
    $('#extend-days #check_price').click(function() {
      let extend_days = $('#extend-days #extend_days').val();
      let reservation_id = $('#extend-days #reservation_id').val();
      let checkout_schedule = $('#extend-days #checkout_schedule').val();
      if (reservation_id == "" || checkout_schedule == "" || extend_days == "") {
        toastr.warning("<?= $this->lang->line('text-extend_days_empty') ?>");
      } else if (checkout_schedule >= extend_days) {
        toastr.warning("<?= $this->lang->line('text-extend_days_same') ?>");
      } else {
        $('#check-extend-days').modal('show');
        $('#check-extend-days .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-check_extend_days',
            'extend_days': extend_days,
            'reservation_id': reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              let total = 0;
              $('#check-extend-days table tbody').html('');
              $(data['rooms']).each(function(index, r1) {
                let i = 0;
                $(r1['room_data']).each(function(index, r2) {
                  let in_house = parseInt(moment(r2['checkout'], "YYYY-MM-DD").diff(moment(r2['checkin'], "YYYY-MM-DD"), 'days'));
                  let sessions = r2['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + r2['session_name'] + '</span>' : '';
                  total += parseInt(r2['room_price']) * in_house;
                  if (i == 0) {
                    $('#check-extend-days table tbody').append('<tr><td colspan="3" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
                  } else {
                    $('#check-extend-days table tbody').append('<tr><td class="align-middle text-center" rowspan="' + r2['row_number'] + '" style="width: 40px;"><?= $this->lang->line('text-change_rooms') ?></td><td colspan="2" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
                  }
                  $(r2['price_change']).each(function(index, r3) {
                    $('#check-extend-days table tbody').append('<tr><td class="align-middle text-center">' + money_format(r3) + '</td></tr>');
                  });
                  i++;
                });

                $(r1['additional_costs']).each(function(index, r2) {
                  let cost_type;
                  if (r2['additional_cost_type'] == 'discount') {
                    total = total - parseInt(r2['additional_cost_price']);
                    cost_type = "<?= $this->lang->line('text-discount') ?>";
                  } else {
                    total = total + parseInt(r2['additional_cost_price']);
                    cost_type = r2['additional_cost_type'] == 'request' ? "<?= $this->lang->line('text-request') ?>" : "<?= $this->lang->line('text-loss_or_damage') ?>";;
                  }
                  $('#check-extend-days table tbody').append('<tr><td class="align-middle text-center" style="width: 40px;">' + cost_type + '</td><td colspan="4" class="align-middle">' + r2['additional_cost_description'] + '</td><td class="align-middle text-center">' + money_format(r2['additional_cost_price']) + '</td></tr>');
                });
              });
              $('#check-extend-days table tbody').append('<tr><th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total) + '</td></tr>');


              $('#check-extend-days #extend_days').val(extend_days);
              $('#check-extend-days #reservation_id').val(reservation_id);
              $('#check-extend-days .overlay').addClass('invisible');
              $('#check-extend-days').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    $('#check-extend-days #save').click(function() {
      let extend_days = $('#check-extend-days #extend_days').val();
      let reservation_id = $('#check-extend-days #reservation_id').val();
      if (extend_days == "" || reservation_id == "") {
        toastr.warning("<?= $this->lang->line('text-extend_days_empty') ?>");
      } else {
        $('#check-extend-days .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-check_extend_days',
            'extend_days': extend_days,
            'reservation_id': reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-add_payment') ?>');
              $('#check-extend-days .overlay').addClass('invisible');
              $('#check-extend-days').modal('hide');
              $('#extend-days').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Add Payments
    $('#checkin-detail button[data-target="#add-payment"]').click(function() {
      $('#add-payment .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-add_payment',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#add-payment #payment_id').empty().select2({
              data: data['payments'],
              width: "100%",
              theme: 'bootstrap4',
            });

            // $('#add-payment #payment_date').val('');
            $('#add-payment #payment_date').datepicker("setDate", moment().format('YYYY-MM-DD'));
            $('#add-payment #payment_description').val('');
            $('#add-payment #total_payment').val('');
            $('#add-payment .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#add-payment #save').click(function() {
      let payment_description = $('#add-payment #payment_description').val();
      let payment_id = $('#add-payment #payment_id').val();
      let payment_date = $('#add-payment #payment_date').val();
      let total_payment = $('#add-payment #total_payment').val().replaceAll('.', '');
      let reservation_id = $('#add-payment #reservation_id').val();
      if (payment_description == "" || total_payment == "" || reservation_id == "" || reservation_id == "" || reservation_id == "") {
        toastr.warning("<?= $this->lang->line('text-add_payment_empty') ?>");
      } else {
        $('#add-payment .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-add_payment',
            'reservation_id': reservation_id,
            'payment_id': payment_id,
            'payment_date': payment_date,
            'payment_description': payment_description,
            'total_payment': total_payment,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              window.open('<?= base_url('export/receipt/') ?>' + data['payment_number'], '_blank');
              toastr.success('<?= $this->lang->line('toast-add_payment') ?>');
              $('#add-payment .overlay').addClass('invisible');
              $('#add-payment').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Delete Payments
    $('#checkin-detail').on('click', '.delete-payment', function() {
      $('#delete-payment').modal('show');
      $('#delete-payment #payment_history_id').val($(this).attr('payment_history_id'));
      $('#delete-payment #reservation_id').val($(this).attr('reservation_id'));
    });

    $('#delete-payment #save').click(function() {
      let payment_history_id = $('#delete-payment #payment_history_id').val();
      let reservation_id = $('#delete-payment #reservation_id').val();
      if (payment_history_id == "" || reservation_id == "" || reservation_id == "undefined") {
        toastr.warning("<?= $this->lang->line('text-delete_payment_empty') ?>");
      } else {
        $('#delete-payment .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-delete_payment',
            'payment_history_id': payment_history_id,
            'reservation_id': reservation_id,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-delete_payment') ?>');
              $('#delete-payment .overlay').addClass('invisible');
              $('#delete-payment').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Change Price
    $('#checkin-detail').on('click', '.change_price', function() {
      $('#change-price').modal('show');
      $('#change-price .overlay').removeClass('invisible');
      let room_reservation_id = $(this).attr('room_reservation_id');
      let in_house = parseInt($(this).attr('in_house'));
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-change_price',
          'room_reservation_id': room_reservation_id,
        },
        success: function(d) {
          if (d['status'] == 'done') {
            let sessions = d['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + d['session_name'] + '</span>' : '';
            $('#change-price #room_name').html('Room reserved type : ' + d['room_type_name'] + ' No. ' + d['room_number'] + sessions);
            $('#change-price #in_house').html(in_house + ' <?= $this->lang->line('text-night') ?>');
            $('#change-price #room_price').html(money_format(d['room_price']));
            $('#change-price #total_price').html(money_format(parseInt(d['room_price']) * in_house));
            $('#change-price .room_price').attr('in_house', in_house).attr('room_price', d['room_price']).val(d['room_price']).trigger('input').trigger('keyup');


            $('#change-price #room_reservation_id').val(room_reservation_id);
            $('#change-price .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#change-price .room_price').keyup(function() {
      let in_house = parseInt($(this).attr('in_house'));
      let room_price = parseInt($(this).attr('room_price'));
      let price_change = $(this).val() == '' ? 0 : parseInt($(this).val().replaceAll('.', ''));
      $('#change-price #total_change').html(money_format(price_change * in_house));
      $('#change-price #price_difference').html(money_format(Math.abs(price_change - room_price)));
      $('#change-price #total_difference').html(money_format(Math.abs((price_change - room_price) * in_house)));
    });

    $('#change-price #save').click(function() {
      let room_reservation_id = $('#change-price #room_reservation_id').val();
      let price_before = $('#change-price .room_price').attr('room_price');
      let room_price = $('#change-price .room_price').val().replaceAll(".", "");
      if (room_reservation_id == "" || room_price == '') {
        toastr.warning("<?= $this->lang->line('text-change_price_empty') ?>");
      } else if (price_before == room_price) {
        toastr.warning("<?= $this->lang->line('text-change_price_same') ?>");
      } else {
        $('#change-price .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-change_price',
            'room_reservation_id': room_reservation_id,
            'room_price': room_price,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-change_price') ?>');
              $('#change-price .overlay').addClass('invisible');
              $('#change-price').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Add Additional Costs
    $('#checkin-detail button[data-target="#add-additional_costs_checkin"]').click(function() {
      $('#add-additional_costs_checkin .overlay').removeClass('invisible');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-add_additional_costs',
        },
        success: function(data) {
          if (data['status'] == 'done') {
            $('#add-additional_costs_checkin #request_id').empty().select2({
              data: data['requests'],
              width: "100%",
              theme: 'bootstrap4',
            });

            $('#add-additional_costs_checkin #room_id').prop('selectedIndex', 0).trigger('change');
            $('#add-additional_costs_checkin #additional_cost_description').val('');
            $('#add-additional_costs_checkin #additional_cost_price').val('');
            $('#add-additional_costs_checkin #additional_cost_type').val('request').trigger('change');
            $('#add-additional_costs_checkin .overlay').addClass('invisible');
          } else {
            toastr.error('<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

    $('#add-additional_costs_checkin #additional_cost_type').change(function() {
      if ($(this).val() == 'request') {
        $('#add-additional_costs_checkin .price').addClass('d-none');
        $('#add-additional_costs_checkin .description').addClass('d-none');
        $('#add-additional_costs_checkin .requests').removeClass('d-none');
      } else {
        $('#add-additional_costs_checkin .price').removeClass('d-none');
        $('#add-additional_costs_checkin .description').removeClass('d-none');
        $('#add-additional_costs_checkin .requests').addClass('d-none');
      }
    });

    $('#add-additional_costs_checkin #save').click(function() {
      let room_id = $('#add-additional_costs_checkin #room_id').val();
      let reservation_id = $('#add-additional_costs_checkin #reservation_id').val();
      let additional_cost_type = $('#add-additional_costs_checkin #additional_cost_type').val();
      let additional_cost_description = $('#add-additional_costs_checkin #additional_cost_description').val();
      let additional_cost_price = $('#add-additional_costs_checkin #additional_cost_price').val().replaceAll(".", "");
      let request_id = $('#add-additional_costs_checkin #request_id').val();
      if (reservation_id == "" || room_id == "" || request_id == "" || (additional_cost_type != 'request' && (additional_cost_price == '' || additional_cost_description == ''))) {
        toastr.warning("<?= $this->lang->line('text-additional_costs_empty') ?>");
      } else {
        $('#add-additional_costs_checkin .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-add_additional_costs',
            'room_id': room_id,
            'reservation_id': reservation_id,
            'additional_cost_type': additional_cost_type,
            'additional_cost_description': additional_cost_description,
            'additional_cost_price': additional_cost_price,
            'request_id': request_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-add_additional_costs') ?>');
              $('#add-additional_costs_checkin .overlay').addClass('invisible');
              $('#add-additional_costs_checkin').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    // Delete Additional Costs
    $('#checkin-detail').on('click', '.delete-additional_costs', function() {
      $('#delete-additional_costs_checkin').modal('show');
      $('#delete-additional_costs_checkin #additional_cost_id').val($(this).attr('additional_cost_id'));
      $('#delete-additional_costs_checkin #reservation_id').val($(this).attr('reservation_id'));
    });

    $('#delete-additional_costs_checkin #save').click(function() {
      let additional_cost_id = $('#delete-additional_costs_checkin #additional_cost_id').val();
      let reservation_id = $('#delete-additional_costs_checkin #reservation_id').val();
      if (additional_cost_id == "" || reservation_id == "" || reservation_id == "undefined") {
        toastr.warning("<?= $this->lang->line('text-delete_additional_costs_empty') ?>");
      } else {
        $('#delete-additional_costs_checkin .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'save-delete_additional_costs',
            'additional_cost_id': additional_cost_id,
            'reservation_id': reservation_id,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              setTableCheckinDetail(data['rooms']);
              toastr.success('<?= $this->lang->line('toast-cancel_reservation') ?>');
              $('#delete-additional_costs_checkin .overlay').addClass('invisible');
              $('#delete-additional_costs_checkin').modal('hide');
            } else {
              toastr.error('<?= $this->lang->line('text-toast-error') ?>');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });


    //////////////////////////////////////////////////////////////////////////////////////////////////


    var table_guest_data = $('#table_guest_data').DataTable({
      'paging': false,
      'info': false,
      'searching': true,
      'ordering': true,
      'autoWidth': false,
      "columns": [{
        'className': "align-middle text-center",
        "width": "130px",
      }, {
        'className': "align-middle",
      }, {
        'className': "align-middle text-center",
        "width": "150px",
      }, {
        'className': "align-middle text-center",
        "width": "30px",
      }, ],
      "order": [
        [2, "asc"]
      ],
    });
    $('#table_guest_data_filter').hide();
    $('#field_guest_data').keyup(function() {
      table_guest_data.columns($('#column_guest_data').val()).search(this.value).draw();
    });

    $('#guest_history #check_guest').click(function() {
      let date_report = $('#guest_history #date_report').val();
      if (date_report == '') {
        toastr.warning("<?= $this->lang->line('text-guest_data_empty') ?>");
      } else {
        $('#guest-data').modal('show').find('.overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: "<?= base_url('frontoffice/get_data') ?>",
          dataType: "JSON",
          data: {
            'set': 'start-guest_data',
            'date': date_report,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              table_guest_data.clear().draw();
              $(data['reservations']).each(function(index, hasil) {
                let hasil_button = '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" reservation_id="' + hasil['reservation_id'] + '" data-toggle="modal" data-target="#guest-detail"><i class="fa fa-eye"></i></button></div>';
                // table_guest_data.row.add([hasil['nm_tamu'], hasil['lama_inap'], format_rupiah(hasil['harga_tkamar'], 'Rp.'), hasil_button]).draw().node();
                table_guest_data.row.add([hasil['reservation_number'], hasil['guest_name'], hasil['in_house'], hasil_button]).draw().node();
              });
              $('#guest-data .overlay').addClass('invisible');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    });

    table_guest_data.on('click', 'button[data-target="#guest-detail"]', function() {
      $('#guest-detail .overlay').removeClass('invisible');
      let reservation_id = $(this).attr('reservation_id');
      $.ajax({
        type: "POST",
        url: "<?= base_url('frontoffice/get_data') ?>",
        dataType: "JSON",
        data: {
          'set': 'start-guest_detail',
          'reservation_id': reservation_id,
        },
        success: function(data) {
          if (data['status'] == 'done') {
            let reservations = data['reservations'];
            let in_house = parseInt(moment(reservations['checkout_schedule'], "YYYY-MM-DD").diff(moment(reservations['checkin_schedule'], "YYYY-MM-DD"), 'days'));
            $('#guest-detail #guest_name span').html(reservations['guest_name']);
            $('#guest-detail #reservation_number span').html(reservations['reservation_number']);
            $('#guest-detail #reservation_status span').html(reservations['reservation_status']);
            $('#guest-detail #segment span').html(reservations['segment_name'] + ' - ' + reservations['segment_type']);
            $('#guest-detail #in_house span').html(setFullDate(reservations['checkin_schedule']) + ' - ' + setFullDate(reservations['checkout_schedule']) + ' || ' + in_house + ' <?= $this->lang->line('text-night') ?>');

            $('#guest-detail #total_guest span').html(reservations['adult_guest'] + ' <?= $this->lang->line('text-adult') ?>' + (reservations['child_guest'] != null ? ' & ' + reservations['child_guest'] + ' <?= $this->lang->line('text-child') ?>' : ''));

            $('#guest-detail #checkin_time span').html(reservations['checkin_time']);
            $('#guest-detail #checkout_time span').html(reservations['checkout_time']);
            $('#guest-detail #room_plan_name span').html(reservations['room_plan_name']);
            $('#guest-detail #reservation_time span').html(reservations['reservation_time']);

            $('#guest-detail #print_folio').attr('href', '<?= base_url('export/reservation/') ?>' + reservations['reservation_number']);

            if (reservations['reservation_status'] == 'Finished') {
              let set_linkbv = reservations['receipt_type'] == 'bill' ? '<?= base_url('export/bill/') ?>' : '<?= base_url('export/invoice/') ?>';
              $('#guest-detail #print_billinvoice').removeClass('disabled').attr('href', set_linkbv + reservations['bill_number']);
              $('#guest-detail #checkin_time').removeClass('d-none');
              $('#guest-detail #checkout_time').removeClass('d-none');
            } else if (reservations['reservation_status'] == 'Stay') {
              $('#guest-detail #checkin_time').removeClass('d-none');
              $('#guest-detail #checkout_time').addClass('d-none');
              $('#guest-detail #print_billinvoice').addClass('disabled');
            } else {
              $('#guest-detail #checkin_time').addClass('d-none');
              $('#guest-detail #checkout_time').addClass('d-none');
              $('#guest-detail #print_billinvoice').addClass('disabled');
            }


            let total = 0;
            let get_room = data['rooms'];
            $('#guest-detail table tbody').html('');
            $(get_room['rooms']).each(function(index, r1) {
              let i = 0;
              let last_columm = '';
              $(r1['room_data']).each(function(index, r2) {
                let in_house = parseInt(moment(r2['checkout'], "YYYY-MM-DD").diff(moment(r2['checkin'], "YYYY-MM-DD"), 'days'));
                let sessions = r2['session_name'] != null ? '<br><span class="font-italic">Sessions : ' + r2['session_name'] + '</span>' : '';
                total += parseInt(r2['room_price']) * in_house;
                if (i == 0) {
                  $('#guest-detail table tbody').append('<tr><td colspan="3" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td><td rowspan="' + r1['row_number'] + '" class="align-middle text-center"><a class="btn btn-sm btn-info disabled" target="_blank" href="<?= base_url('export/room_payment/') ?>' + r1['room_reservation_id'] + '"><i class="fa fa-print"></i></a></td></tr>');
                } else {
                  $('#guest-detail table tbody').append('<tr><td class="align-middle text-center" rowspan="' + r2['row_number'] + '" style="width: 40px;"><?= $this->lang->line('text-change_rooms') ?></td><td colspan="2" rowspan="' + r2['row_number'] + '" class="align-middle">Room reserved type : ' + r2['room_type_name'] + ' No. ' + r2['room_number'] + sessions + '</td><td class="align-middle text-center">' + money_format(r2['room_price']) + '</td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + in_house + ' <?= $this->lang->line('text-night') ?></td><td rowspan="' + r2['row_number'] + '" class="align-middle text-center">' + money_format(parseInt(r2['room_price']) * in_house) + '</td></tr>');
                }
                $(r2['price_change']).each(function(index, r3) {
                  $('#guest-detail table tbody').append('<tr><td class="align-middle text-center">' + money_format(r3) + '</td></tr>');
                });
                i++;
              });

              $(r1['additional_costs']).each(function(index, r2) {
                let cost_type;
                if (r2['additional_cost_type'] == 'discount') {
                  total = total - parseInt(r2['additional_cost_price']);
                  cost_type = "<?= $this->lang->line('text-discount') ?>";
                } else {
                  total = total + parseInt(r2['additional_cost_price']);
                  cost_type = r2['additional_cost_type'] == 'request' ? "<?= $this->lang->line('text-request') ?>" : "<?= $this->lang->line('text-loss_or_damage') ?>";;
                }
                $('#guest-detail table tbody').append('<tr><td class="align-middle text-center" style="width: 40px;">' + cost_type + '</td><td colspan="4" class="align-middle">' + r2['additional_cost_description'] + '</td><td class="align-middle text-center">' + money_format(r2['additional_cost_price']) + '</td><td class="align-middle text-center"><button type="button" class="btn btn-sm btn-danger delete-additional_costs" additional_cost_id="' + r2['additional_cost_id'] + '" reservation_id="' + r1['reservation_id'] + '"><i class="fa fa-trash-alt"></i></td></tr>');
              });
            });
            $('#guest-detail table tbody').append('<tr><th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-total_price') ?></th><td class="text-center">' + money_format(total) + '</td><td></td></tr>');
            if (get_room['payment_histories'].length > 0) {
              $(get_room['payment_histories']).each(function(index, r1) {
                total = total - parseInt(r1['total_payment']);
                $('#guest-detail table tbody').append('<tr><td colspan="2" class="align-middle text-center" style="width: 100px;">' + setFullDate(r1['payment_date']) + '</td><td colspan="3" class="align-middle">' + r1['payment_desciption'] + '</td><td class="align-middle text-center">' + money_format(r1['total_payment']) + '</td><td class="align-middle text-center"><a class="btn btn-sm btn-primary" target="_blank" href="<?= base_url('export/receipt/') ?>' + r1['payment_number'] + '"><i class="fa fa-print"></i></a></td></tr>');
              });

              let line = total > 0 ? '<?= $this->lang->line('text-remaining_payment') ?>' : '<?= $this->lang->line('text-remaining_return') ?>';
              $('#guest-detail table tbody').append('<tr><th class="text-center align-middle" colspan="5">' + line + '</th><td class="text-center">' + money_format(Math.abs(total)) + '</td><td></td></tr>');
            }

            $('#guest-detail .overlay').addClass('invisible');
          } else {
            toastr.error(data['status'] != 'none' ? data['status'] : '<?= $this->lang->line('text-toast-error') ?>');
          }
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });


  });
</script>