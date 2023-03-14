<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Frontoffice_model extends CI_Model
{
  public function uploadPhotoUser()
  {
    $upload_image = $_FILES['user_photo']['name'];
    if ($upload_image) {
      $config['allowed_types'] = 'gif|jpg|png';
      $config['upload_path'] = './uploads/users/';
      $config['max_size']     = '2048';
      $this->load->library('upload', $config);
      if ($this->upload->do_upload('user_photo')) {
        $hasil_foto = $this->upload->data('file_name');
        if ($this->db->update('users', ['user_photo' => $hasil_foto], ['user_id' => $this->session->userdata('user_id')])) {
          if ($this->session->userdata('user_photo') != '') unlink(FCPATH . 'uploads/users/' . $this->session->userdata('user_photo'));
          $this->session->set_userdata(['user_photo'  => $hasil_foto]);
          return sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-users'));
        } else {
          return $this->lang->line('alert-failed');
        }
      } else {
        return sprintf($this->lang->line('alert-body-error'), $this->upload->display_errors());
      }
    }
  }

  public function changePasswordUser()
  {

    if ($this->db->get_where('users', [
      'user_id'  => $this->session->userdata('user_id'),
      'username' => $this->session->userdata('username'),
      'password' => $this->input->post('old_pass'),
    ])->num_rows() > 0) {
      if ($this->input->post('new_pass1') == $this->input->post('new_pass2')) {
        if ($this->db->update('users', ['password' => $this->input->post('new_pass1')], ['user_id' => $this->session->userdata('user_id')])) {
          $this->session->set_userdata(['password'  => $this->input->post('new_pass1')]);
          return sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-users'));
        } else {
          return $this->lang->line('alert-failed');
        }
      } else {
        return sprintf($this->lang->line('alert-body-error'), 'New Password Different / Not Same!');
      }
    } else {
      return sprintf($this->lang->line('alert-body-error'), 'Wrong password entered!');
    }
  }

  public function getReservationRooms($reservation_id, $extend_days = null)
  {
    $rooms = [];
    $room_plan_id = '';
    $this->db->join('rooms', 'room_id');
    $this->db->join('room_types', 'room_type_id');
    $this->db->join('room_rates', 'room_rate_id');
    $this->db->join('sessions', 'session_id', 'left');
    $this->db->join('reservations', 'reservation_id');
    $this->db->select('reservation_id, room_reservation_id, room_id, room_plan_id, room_type_name, room_number, session_name, checkin_schedule, checkout_schedule, room_reservations.room_price AS room_price');
    $room_reservations  = $this->db->get_where("room_reservations", ['reservation_id' => $reservation_id])->result_array();
    foreach ($room_reservations as $result) {
      $room_plan_id = $result['room_plan_id'];
      $i = 1;
      $room_data = [[
        'row_number'     => 1,
        'room_id'        => $result['room_id'],
        'room_type_name' => $result['room_type_name'],
        'room_number'    => $result['room_number'],
        'sessions'       => $result['session_name'],
        'room_price'     => $result['room_price'],
        'checkin'        => $result['checkin_schedule'],
        'checkout'       => $extend_days != null ? $extend_days : $result['checkout_schedule'],
        'price_change'   => [],
      ]];

      $this->db->order_by('created_at', 'desc');

      $this->db->join('rooms', 'room_id', 'left');
      $this->db->join('room_types', 'room_type_id', 'left');
      $this->db->join('room_rates', 'room_rate_id', 'left');
      $this->db->join('sessions', 'session_id', 'left');
      $this->db->select('room_change_type, room_type_name, room_number, session_name, room_change_date, room_change_histories.room_price AS room_price');
      $room_change_histories = $this->db->get_where("room_change_histories", ['room_reservation_id' => $result['room_reservation_id']]);
      foreach ($room_change_histories->result_array() as $r2) {
        if ($r2['room_change_type'] == 'switch room') {
          $room_data[] = [
            'row_number'     => 1,
            'room_type_name' => $r2['room_type_name'],
            'room_number'    => $r2['room_number'],
            'sessions'       => $r2['session_name'],
            'checkin'        => $room_data[$i - 1]['checkin'],
            'checkout'       => $r2['room_change_date'],
            'room_price'     => $r2['room_price'],
            'price_change'   => [],
          ];
          $room_data[$i - 1]['checkin'] = $r2['room_change_date'];
          $i++;
        } else {
          $room_data[$i - 1]['row_number'] = $room_data[$i - 1]['row_number'] + 1;
          $xprice = $room_data[$i - 1]['price_change'];
          $xprice[] = $r2['room_price'];
          $room_data[$i - 1]['price_change'] = $xprice;
        }
      }

      $this->db->order_by('created_at', 'asc');
      $rooms[] = [
        'room_id'             => $result['room_id'],
        'reservation_id'      => $result['reservation_id'],
        'room_reservation_id' => $result['room_reservation_id'],
        'room_type_name'      => $result['room_type_name'],
        'room_number'         => $result['room_number'],
        'row_number'          => $room_change_histories->num_rows() + 1,
        'room_data'           => $room_data,
        'additional_costs'    => $this->db->get_where("additional_costs", 'room_reservation_id="' . $result['room_reservation_id'] . '" AND deleted_at IS NULL')->result_array(),
      ];
    }

    return [
      'rooms'             => $rooms,
      'room_plan_id'      => $room_plan_id,
      'payment_histories' => $this->db->get_where("payment_histories", 'payment_desciption!="Deposit" AND reservation_id="' . $result['reservation_id'] . '" AND deleted_at IS NULL')->result_array(),
    ];
  }

  public function getData()
  {
    if ($this->input->post('set') == 'refresh_dashboard') {
      $vacant_ready   = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='VR'")->row_array();
      $vacant_clean   = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='VC'")->row_array();
      $occupied       = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='OD' OR room_status='OC'")->row_array();
      $vacant_room    = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='VR' OR room_status='VC' OR room_status='VD'")->row_array();
      $vacant_dirty   = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='VD'")->row_array();
      $occupied_dirty = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='OD'")->row_array();
      $occupied_clean = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='OC'")->row_array();
      $out_of_service = $this->db->query("SELECT COUNT(room_id) AS hasil FROM rooms WHERE room_status='OO'")->row_array();


      $expected_departure = $this->db->query("SELECT COUNT(reservation_id) AS hasil FROM reservations WHERE `reservation_status`='Stay' AND reservations.checkout_schedule=DATE(NOW())")->row_array();
      $expected_arrival   = $this->db->query("SELECT COUNT(reservation_id) AS hasil FROM reservations WHERE `reservation_status`='Reservation' AND reservations.checkin_schedule=DATE(NOW())")->row_array();


      $hasil = [
        'vacant_ready'       => $vacant_ready['hasil'],
        'vacant_clean'       => $vacant_clean['hasil'],
        'occupied'           => $occupied['hasil'],
        'vacant_room'        => $vacant_room['hasil'],
        'vacant_dirty'       => $vacant_dirty['hasil'],
        'occupied_dirty'     => $occupied_dirty['hasil'],
        'occupied_clean'     => $occupied_clean['hasil'],
        'out_of_service'     => $out_of_service['hasil'],
        'expected_departure' => $expected_departure['hasil'],
        'expected_arrival'   => $expected_arrival['hasil'],
        'status'             => 'done',
      ];
    } else if ($this->input->post('set') == 'start-room_status_data') {
      $status = $this->input->post('status');

      $this->db->join('floors', 'floor_id');
      $this->db->join('room_types', 'room_type_id');
      $hasil = [
        'rooms'  => $this->db->get_where("rooms", ['room_status' => $status])->result_array(),
        'types'  => $this->lang->line('text-' . $status),
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'start-add_reservation') {
      $this->db->order_by('room_plan_name', 'asc');
      $this->db->select("room_plan_id AS `id`, room_plan_name AS `text`");
      $hasil = [
        'room_plans' => $this->db->get_where("room_plans")->result_array(),
        'status'     => 'done',
      ];
    } else if ($this->input->post('set') == 'start-find_rooms') {
      $room_plans = $this->input->post('room_plans');
      $checkin = $this->input->post('checkin');
      $checkin_year = date('Y', strtotime($checkin));
      $checkout = $this->input->post('checkout');
      $hasil = [
        'rooms' => $this->db->query("SELECT rooms.room_id, rooms.room_number, t1.room_rate_id, COALESCE((SELECT session_name FROM sessions WHERE sessions.session_id=t1.session_id),NULL) AS session_name, IF(EXISTS(SELECT 1 FROM room_rates AS a WHERE a.room_type_id=rooms.room_type_id AND a.room_plan_id='$room_plans' AND IF(t1.session_id IS NULL,1,a.session_id=t1.session_id)), COALESCE((SELECT 'occupied' FROM rooms AS c JOIN room_reservations USING(room_id) JOIN reservations USING(reservation_id) WHERE c.room_id=rooms.room_id AND (('$checkin' BETWEEN `checkin_schedule` AND `checkout_schedule`) OR ('$checkout' BETWEEN `checkin_schedule` AND `checkout_schedule`) OR (`checkin_schedule` BETWEEN '$checkin' AND '$checkout') OR (`checkout_schedule` BETWEEN '$checkin' AND '$checkout')) AND `reservation_status` IN ('Reservation','Stay') LIMIT 1), IF(rooms.room_status='OO','out_of_service', IF(rooms.room_status='VD' OR rooms.room_status='OD','dirty',IF(rooms.room_status='VC' OR rooms.room_status='OC','clean','ready')))),'no_room_plans') AS room_status FROM rooms LEFT JOIN (SELECT room_rates.room_type_id,room_rates.room_rate_id,room_rates.session_id FROM room_rates JOIN (SELECT room_type_id, CASE WHEN COUNT(CASE WHEN session_id=(SELECT session_id FROM sessions WHERE '$checkin' BETWEEN DATE_FORMAT(start_session,'$checkin_year-%m-%d') AND DATE_FORMAT(end_session,CONCAT('$checkin_year'+(YEAR(end_session)- YEAR(start_session)),'-%m-%d'))) THEN 1 END) > 0 THEN (SELECT session_id FROM sessions WHERE '$checkin' BETWEEN DATE_FORMAT(start_session,'$checkin_year-%m-%d') AND DATE_FORMAT(end_session,CONCAT('$checkin_year'+(YEAR(end_session)- YEAR(start_session)),'-%m-%d'))) END AS session_id FROM room_rates GROUP BY room_type_id) AS a ON room_rates.room_type_id=a.room_type_id AND ((a.session_id IS NULL AND room_rates.session_id IS NULL) OR (a.session_id = room_rates.session_id)) WHERE room_plan_id='$room_plans') AS t1 USING(room_type_id)")->result_array(),
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'start-add_additional_costs') {
      $this->db->order_by('request_name', 'asc');
      $this->db->group_by('request_id', 'asc');
      $this->db->select("CONCAT(request_name,'||', request_price) AS `id`, CONCAT(CAST(request_name AS CHAR CHARACTER SET utf8),' - Rp. ', FORMAT(SUM(request_price), 0)) AS `text`");
      $hasil = [
        'requests' => $this->db->get_where("requests")->result_array(),
        'status'     => 'done',
      ];
    } else if ($this->input->post('set') == 'start-choose_rooms') {
      $rooms_get = $this->input->post('rooms');
      $rooms = [];
      $room = [];
      foreach ($rooms_get as $result) {
        $this->db->join('room_types', 'room_type_id');
        $this->db->join('room_rates', 'room_type_id');
        $this->db->join('sessions', 'session_id', 'left');
        $xx  = $this->db->get_where("rooms", ['room_id' => $result['room_id'], 'room_rate_id' => $result['room_rate_id']])->row_array();
        $rooms[] = [
          'room_id'        => $xx['room_id'],
          'room_rate_id'   => $xx['room_rate_id'],
          'room_type_name' => $xx['room_type_name'],
          'room_number'    => $xx['room_number'],
          'room_price'     => $xx['room_price'],
          'session_name'   => $xx['session_name'],
        ];
        $room[] = [
          'id'        => $xx['room_id'],
          'text' => $xx['room_type_name'] . ' No. ' . $xx['room_number'],
        ];
      }

      $this->db->order_by('guest_name');
      $this->db->select("guest_id AS `id`, CONCAT(guest_name,' (', identity_type,' - ', identity_number, ') ') AS `text`");
      $guests  = $this->db->get_where("guests")->result_array();

      $this->db->order_by('segment_name');
      $this->db->select("segment_id AS `id`, segment_name AS `text`");
      $segments  = $this->db->get_where("segments")->result_array();

      $this->db->order_by('payment_name');
      $this->db->select("payment_id AS `id`, payment_name AS `text`");
      $payments  = $this->db->get_where("payments")->result_array();
      $hasil = [
        'room'     => $room,
        'rooms'    => $rooms,
        'guests'   => $guests,
        'segments' => $segments,
        'payments' => $payments,
        'status'   => 'done',
      ];
    } else if ($this->input->post('set') == 'save-guests') {
      $guest_name      = $this->input->post('guest_name') != '' ? $this->input->post('guest_name') : null;
      $national        = $this->input->post('national') != '' ? $this->input->post('national') : null;
      $identity_type   = $this->input->post('identity_type') != '' ? $this->input->post('identity_type') : null;
      $identity_number = $this->input->post('identity_number') != '' ? $this->input->post('identity_number') : null;
      $phone_number    = $this->input->post('phone_number') != '' ? $this->input->post('phone_number') : null;
      $birth_date      = $this->input->post('birth_date') != '' ? $this->input->post('birth_date') : null;
      $email           = $this->input->post('email') != '' ? $this->input->post('email') : null;
      $guest_address   = $this->input->post('guest_address') != '' ? $this->input->post('guest_address') : null;

      if ($this->db->get_where('guests', ['identity_type' => $identity_type, 'identity_number' => $identity_number])->num_rows() == 0) {
        if ($this->db->insert('guests', [
          'guest_name'      => $guest_name,
          'national'        => $national,
          'identity_type'   => $identity_type,
          'identity_number' => $identity_number,
          'phone_number'    => $phone_number,
          'birth_date'      => $birth_date,
          'email'           => $email,
          'guest_address'   => $guest_address,
        ])) {
          $this->db->order_by('guest_name');
          $this->db->select("guest_id AS `id`, CONCAT(guest_name,' (', identity_type,' - ', identity_number, ') ') AS `text`");
          $guests  = $this->db->get_where("guests")->result_array();
          $hasil = [
            'guests' => $guests,
            'status' => 'done',
          ];
        } else {
          $hasil['status'] = 'none';
        };
      } else {
        $hasil['status'] = $this->lang->line('text-guest_same');
      };
    } else if ($this->input->post('set') == 'save-reservations') {
      $reservation_id     = getAutoIncrement('reservations');

      $reservation_code = 'NPHR' . date('ym');
      $reservation_number = setCode($reservation_code, 12, 'reservation_number', 'reservations', "reservation_number LIKE'%" . $reservation_code . "%'");
      $payment_code = 'NPHP' . date('ym');
      $payment_number = setCode($payment_code, 12, 'payment_number', 'payment_histories', "payment_number LIKE'%" . $payment_code . "%'");

      $rooms            = $this->input->post('rooms');
      $additional_costs = $this->input->post('additional_costs');
      $checkin_date     = $this->input->post('checkin_date');
      $checkout_date    = $this->input->post('checkout_date');
      $guest_id         = $this->input->post('guest_id');
      $status           = $this->input->post('status');
      $segment_id       = $this->input->post('segment_id');
      $adult_guest      = $this->input->post('adult_guest');
      $child_guest      = $this->input->post('child_guest');
      $payment_id       = $this->input->post('payment_id');
      $deposit          = $this->input->post('deposit');
      $checkin_time     = $this->input->post('checkin_time');

      $set_data =  [

        'reservation_number' => $reservation_number,
        'segment_id'         => $segment_id,
        'guest_id'           => $guest_id,
        'adult_guest'        => $adult_guest,
        'child_guest'        => $child_guest,
        'checkin_schedule'   => $checkin_date,
        'checkout_schedule'  => $checkout_date,
        'deposit'            => $status == 'Check-In' ? $deposit : null,
        'checkin_time'       => $status == 'Check-In' ? $checkin_date . ' ' . $checkin_time . ':00' : null,
        'reservation_status' => $status == 'Check-In' ? 'Stay' : 'Reservation',


      ];

      if ($status == 'Check-In') $set_data['deposit'] = $deposit;
      $query = $this->db->insert('reservations', $set_data) && $this->db->insert('reservation_histories', [
        'reservation_id'     => $reservation_id,
        'reservation_status' => 'Reservation',
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]);

      if ($status == 'Check-In') $query .= $this->db->insert('payment_histories', [
        'reservation_id'     => $reservation_id,
        'payment_id'         => $payment_id,
        'payment_date'       => $checkin_date,
        'payment_number'     => $payment_number,
        'payment_desciption' => 'Deposit',
        'total_payment'      => $deposit,
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]) && $this->db->insert('reservation_histories', [
        'reservation_id'     => $reservation_id,
        'reservation_status' => 'Stay',
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]);


      if (!$query) {
        $this->db->delete('reservations', ['reservation_id' => $reservation_id]);
        $this->db->delete('reservation_histories', ['reservation_id' => $reservation_id]);
        $this->db->delete('payment_histories', ['reservation_id' => $reservation_id]);
        return json_encode(['status' => 'none']);
      }
      $get_room_reservation_id = [];
      $remove_room_reservation_id = [];
      $remove_cleaning_history_id = [];
      foreach ($rooms as $a) {
        $room_reservation_id = getAutoIncrement('room_reservations');
        $get_room_reservation_id[$a['room_id']] = $room_reservation_id;
        $cleaning_history_id = getAutoIncrement('cleaning_histories');


        $remove_room_reservation_id[] = $room_reservation_id;
        $remove_cleaning_history_id[] = $cleaning_history_id;
        $set_data = [
          'reservation_id' => $reservation_id,
          'room_id'        => $a['room_id'],
          'room_price'     => $a['room_price'],
          'room_rate_id'   => $a['room_rate_id'],
        ];

        $query = $this->db->insert('room_reservations', $set_data);
        if ($status == 'Check-In') $query .= $this->db->update('rooms', ['room_status' => 'OC'], ['room_id' => $a['room_id']]) && $this->db->insert('room_reservation_histories', [
          'room_reservation_id' => $room_reservation_id,
          'reservation_status'  => 'Check-In',
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ]) && $this->db->insert('cleaning_histories', [
          'room_id'         => $a['room_id'],
          'cleaning_status' => 'OC',
          'created_at'      => date('Y-m-d H:i:s'),
          'created_by'      => $this->session->userdata('user_id'),
        ]);
        if (!$query) {
          $this->db->delete('room_reservations', ['reservation_id' => $reservation_id]);
          $this->db->where_in('room_reservation_id', $remove_room_reservation_id);
          $this->db->delete('room_reservation_histories');
          $this->db->where_in('cleaning_history_id', $remove_cleaning_history_id);
          $this->db->delete('cleaning_histories');

          $this->db->delete('reservations', ['reservation_id' => $reservation_id]);
          $this->db->delete('reservation_histories', ['reservation_id' => $reservation_id]);
          $this->db->delete('payment_histories', ['reservation_id' => $reservation_id]);
          return json_encode(['status' => 'none']);
        }
      }
      if (!empty($additional_costs)) {
        foreach ($additional_costs as $a) {
          $query = $this->db->insert('additional_costs', [
            'room_reservation_id'         => $get_room_reservation_id[$a['room_id']],
            'additional_cost_type'        => $a['additional_cost_type'],
            'additional_cost_description' => $a['additional_cost_description'],
            'additional_cost_price'       => $a['additional_cost_price'],
            'created_at'                  => date('Y-m-d H:i:s'),
            'created_by'                  => $this->session->userdata('user_id'),
          ]);
          if (!$query) {
            $this->db->where_in('room_reservation_id', $remove_room_reservation_id);
            $this->db->delete('additional_costs');

            $this->db->delete('room_reservations', ['reservation_id' => $reservation_id]);
            $this->db->where_in('room_reservation_id', $remove_room_reservation_id);
            $this->db->delete('room_reservation_histories');
            $this->db->where_in('cleaning_history_id', $remove_cleaning_history_id);
            $this->db->delete('cleaning_histories');

            $this->db->delete('reservations', ['reservation_id' => $reservation_id]);
            $this->db->delete('reservation_histories', ['reservation_id' => $reservation_id]);
            $this->db->delete('payment_histories', ['reservation_id' => $reservation_id]);
            return json_encode(['status' => 'none']);
          }
        }
      }
      $hasil = [
        'reservation_number' => $reservation_number,
        'status'             => 'done',
      ];
      $hasil['payment_number'] = $payment_number;
    } else if ($this->input->post('set') == 'start-reservation_data') {
      $this->db->join('guests', 'guest_id');
      $reservations  = $this->db->get_where("reservations", ['reservation_status' => 'Reservation'])->result_array();
      $reservation = [];
      foreach ($reservations as $value) {
        $reservation[] = [
          'reservation_id'     => $value['reservation_id'],
          'reservation_number' => $value['reservation_number'],
          'guest_name'         => $value['guest_name'],
          'checkin'            => $value['checkin_schedule'],
          'in_house'          => $value['checkin_schedule'] . ' - ' . $value['checkout_schedule'],
        ];
      }
      $hasil = [
        'reservations' => $reservation,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'start-reservation_detail') {
      $reservation_id = $this->input->post('reservation_id');

      $this->db->join('guests', 'guest_id');
      $this->db->join('segments', 'segment_id');
      $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
      $reservations  = $this->db->get_where("reservations", ['reservation_id' => $reservation_id])->row_array();
      $hasil = [
        'reservations' => $reservations,
        'rooms'        => $this->getReservationRooms($reservation_id),
        'status'       => 'done',
      ];
    } else if ($this->input->post('set') == 'start-checkin_reservation') {
      $this->db->order_by('payment_name');
      $this->db->select("payment_id AS `id`, payment_name AS `text`");
      $payments  = $this->db->get_where("payments")->result_array();
      $hasil = [
        'payments' => $payments,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'save-checkin_reservation') {

      $payment_code = 'NPHP' . date('ym');
      $payment_number = setCode($payment_code, 12, 'payment_number', 'payment_histories', "payment_number LIKE'%" . $payment_code . "%'");

      $reservation_id = $this->input->post('reservation_id');
      $deposit        = $this->input->post('deposit');
      $checkin_date   = $this->input->post('checkin_date');
      $checkin_time   = $this->input->post('checkin_time');
      $payment_id     = $this->input->post('payment_id');

      $rooms = [];
      $cleaning_histories = [];
      $room_reservation_histories = [];
      $room_reservations  = $this->db->get_where("room_reservations", ['reservation_id' => $reservation_id])->result_array();
      foreach ($room_reservations as $result) {
        $rooms[] = [
          'room_id'     => $result['room_id'],
          'room_status' => 'OC',
        ];
        $cleaning_histories[] = [
          'room_id'         => $result['room_id'],
          'cleaning_status' => 'OC',
          'created_at'      => date('Y-m-d H:i:s'),
          'created_by'      => $this->session->userdata('user_id'),
        ];
        $room_reservation_histories[] = [
          'room_reservation_id' => $result['room_reservation_id'],
          'reservation_status'  => 'Check-In',
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ];
      }
      $query = $this->db->update('reservations', [
        'deposit'            => $deposit,
        'reservation_status' => 'Stay',
        'checkin_time'       => $checkin_date . ' ' . $checkin_time . ':00',
      ], ['reservation_id' => $reservation_id]) && $this->db->insert('payment_histories', [
        'reservation_id'     => $reservation_id,
        'payment_id'         => $payment_id,
        'payment_date'       => $checkin_date,
        'payment_number'     => $payment_number,
        'payment_desciption' => 'Deposit',
        'total_payment'      => $deposit,
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]) && $this->db->insert('reservation_histories', [
        'reservation_id'     => $reservation_id,
        'reservation_status' => 'Stay',
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]) && $this->db->update_batch('rooms', $rooms, 'room_id') && $this->db->insert_batch('cleaning_histories', $cleaning_histories) && $this->db->insert_batch('room_reservation_histories', $room_reservation_histories);

      if ($query) {
        $hasil =  [
          'payment_number' => $payment_number,
          'status'         => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'save-cancel_reservation') {
      $reservation_id = $this->input->post('reservation_id');

      $query = $this->db->update('reservations', [
        'reservation_status' => 'Cancelled',
      ], ['reservation_id' => $reservation_id]) && $this->db->insert('reservation_histories', [
        'reservation_id'     => $reservation_id,
        'reservation_status' => 'Cancelled',
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]);

      $hasil['status'] = $query ? 'done' : 'none';
    } else if ($this->input->post('set') == 'save-add_additional_costs') {
      $reservation_id = $this->input->post('reservation_id');
      $room_id = $this->input->post('room_id');
      $additional_cost_type = $this->input->post('additional_cost_type');
      $additional_cost_description = $this->input->post('additional_cost_description');
      $additional_cost_price = $this->input->post('additional_cost_price');
      $request_id = $this->input->post('request_id');

      $additional_cost_added = $additional_cost_type == 'request' ? explode('||', $request_id) : [$additional_cost_description, $additional_cost_price];

      if ($this->db->insert('additional_costs', [
        'room_reservation_id'         => $room_id,
        'additional_cost_type'        => $additional_cost_type,
        'additional_cost_description' => $additional_cost_added[0],
        'additional_cost_price'       => $additional_cost_added[1],
        'created_at'                  => date('Y-m-d H:i:s'),
        'created_by'                  => $this->session->userdata('user_id'),
      ])) {
        $hasil = [
          'rooms'  => $this->getReservationRooms($reservation_id),
          'status' => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'save-delete_additional_costs') {
      $additional_cost_id = $this->input->post('additional_cost_id');
      $reservation_id = $this->input->post('reservation_id');

      if ($this->db->update('additional_costs', [
        'deleted_at'         => date('Y-m-d H:i:s'),
        'deleted_by'         => $this->session->userdata('user_id'),
      ], ['additional_cost_id' => $additional_cost_id])) {

        $hasil = [
          'rooms'          => $this->getReservationRooms($reservation_id),
          'reservation_id' => $reservation_id,
          'status'         => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-checkin_data') {
      $this->db->join('guests', 'guest_id');
      $reservations  = $this->db->get_where("reservations", ['reservation_status' => 'Stay'])->result_array();
      $reservation = [];
      foreach ($reservations as $value) {
        $reservation[] = [
          'reservation_id'     => $value['reservation_id'],
          'reservation_number' => $value['reservation_number'],
          'guest_name'         => $value['guest_name'],
          'checkout'           => $value['checkout_schedule'],
          'in_house'          => $value['checkin_schedule'] . ' - ' . $value['checkout_schedule'],
        ];
      }
      $hasil = [
        'reservations' => $reservation,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'start-checkin_detail') {
      $reservation_id = $this->input->post('reservation_id');

      $this->db->join('guests', 'guest_id');
      $this->db->join('segments', 'segment_id');
      $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
      $reservations  = $this->db->get_where("reservations", ['reservation_id' => $reservation_id])->row_array();
      $hasil = [
        'reservations' => $reservations,
        'rooms'        => $this->getReservationRooms($reservation_id),
        'status'       => 'done',
      ];
    } else if ($this->input->post('set') == 'start-checkout_reservation') {
      $this->db->order_by('payment_name');
      $this->db->select("payment_id AS `id`, payment_name AS `text`");
      $payments  = $this->db->get_where("payments")->result_array();
      $hasil = [
        'payments' => $payments,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'save-checkout_reservation') {

      $bill_code = 'NPHB' . date('ym');
      $bill_number = setCode($bill_code, 12, 'bill_number', 'reservations', "bill_number LIKE'%" . $bill_code . "%'");
      $payment_code = 'NPHP' . date('ym');
      $payment_number1 = setCode($payment_code, 12, 'payment_number', 'payment_histories', "payment_number LIKE'%" . $payment_code . "%'");
      $payment_number2 = setCode($payment_code, 12, 'payment_number', 'payment_histories', "payment_number LIKE'%" . $payment_code . "%'", 1);

      $checkout_time     = $this->input->post('checkout_time');
      $checkout_date     = $this->input->post('checkout_date');
      $payment_id        = $this->input->post('payment_id');
      $receipt_type      = $this->input->post('receipt_type');
      $reservation_id    = $this->input->post('reservation_id');
      $remaining_payment = $this->input->post('remaining_payment');

      $rooms = [];
      $cleaning_histories = [];
      $room_reservation_histories = [];
      $room_reservations  = $this->db->get_where("room_reservations", ['reservation_id' => $reservation_id])->result_array();
      $reservations  = $this->db->get_where("reservations", ['reservation_id' => $reservation_id])->row_array();
      foreach ($room_reservations as $result) {
        $rooms[] = [
          'room_id'     => $result['room_id'],
          'room_status' => 'VD',
        ];
        $cleaning_histories[] = [
          'room_id'         => $result['room_id'],
          'cleaning_status' => 'VD',
          'created_at'      => date('Y-m-d H:i:s'),
          'created_by'      => $this->session->userdata('user_id'),
        ];
        $room_reservation_histories[] = [
          'room_reservation_id' => $result['room_reservation_id'],
          'reservation_status'  => 'Check-Out',
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ];
      }
      $query = $this->db->update('reservations', [
        'reservation_status' => 'Finished',
        'bill_number'        => $bill_number,
        'receipt_type'       => $receipt_type,
        'checkout_time'      => $checkout_date . ' ' . $checkout_time . ':00',
      ], ['reservation_id' => $reservation_id]) && $this->db->insert('payment_histories', [
        'reservation_id'     => $reservation_id,
        'payment_id'         => $payment_id,
        'payment_date'       => $checkout_date,
        'payment_number'     => $payment_number1,
        'payment_desciption' => $remaining_payment > 0 ? 'Remaining Payment' : 'Remaining Return',
        'total_payment'      => $remaining_payment,
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]) && $this->db->insert('reservation_histories', [
        'reservation_id'     => $reservation_id,
        'reservation_status' => 'Finished',
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ]) && $this->db->update_batch('rooms', $rooms, 'room_id') && $this->db->insert_batch('cleaning_histories', $cleaning_histories) && $this->db->insert_batch('room_reservation_histories', $room_reservation_histories);

      if ($query) {
        $hasil =  [
          'bill_number'    => $bill_number,
          'payment_number' => $payment_number1,
          'status'         => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-change_price') {
      $room_reservation_id = $this->input->post('room_reservation_id');
      $this->db->join('rooms', 'room_id');
      $this->db->join('room_types', 'room_type_id');
      $this->db->join('room_rates', 'room_rate_id');
      $this->db->join('sessions', 'session_id', 'left');


      $this->db->select('room_types.room_type_name, room_number, session_name, room_reservations.room_price AS room_price');
      $hasil = $this->db->get_where("room_reservations", ['room_reservation_id' => $room_reservation_id])->row_array();
      $hasil['status'] = 'done';
    } else if ($this->input->post('set') == 'save-change_price') {
      $room_reservation_id = $this->input->post('room_reservation_id');
      $room_price = $this->input->post('room_price');

      $room_reservations = $this->db->get_where('room_reservations', ['room_reservation_id' => $room_reservation_id]);
      if ($room_reservations->num_rows() > 0) {
        $room_reservations = $room_reservations->row_array();
        if ($this->db->update('room_reservations', ['room_price' => $room_price], ['room_reservation_id' => $room_reservation_id]) && $this->db->insert('room_change_histories', [
          'room_reservation_id' => $room_reservation_id,
          'room_change_type'    => 'change price',
          'room_price'          => $room_reservations['room_price'],
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ])) {
          $hasil = [
            'rooms'  => $this->getReservationRooms($room_reservations['reservation_id']),
            'status' => 'done',
          ];
        } else {
          $hasil['status'] = 'none';
        }
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-add_payment') {
      $this->db->order_by('payment_name');
      $this->db->select("payment_id AS `id`, payment_name AS `text`");
      $payments  = $this->db->get_where("payments")->result_array();
      $hasil = [
        'payments' => $payments,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'save-add_payment') {
      $payment_code        = 'NPHP' . date('ym');
      $payment_number      = setCode($payment_code, 12, 'reservation_number', 'reservations', "reservation_number LIKE'%" . $payment_code . "%'");
      $payment_id          = $this->input->post('payment_id');
      $payment_date        = $this->input->post('payment_date');
      $reservation_id      = $this->input->post('reservation_id');
      $payment_description = $this->input->post('payment_description');
      $total_payment       = $this->input->post('total_payment');

      if ($this->db->insert('payment_histories', [
        'reservation_id'     => $reservation_id,
        'payment_id'         => $payment_id,
        'payment_date'       => $payment_date,
        'payment_number'     => $payment_number,
        'payment_desciption' => $payment_description,
        'total_payment'      => $total_payment,
        'created_at'         => date('Y-m-d H:i:s'),
        'created_by'         => $this->session->userdata('user_id'),
      ])) {
        $hasil = [
          'rooms'          => $this->getReservationRooms($reservation_id),
          'payment_number' => $payment_number,
          'status'         => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'save-delete_payment') {
      $payment_history_id = $this->input->post('payment_history_id');
      $reservation_id = $this->input->post('reservation_id');

      if ($this->db->update('payment_histories', [
        'deleted_at'          => date('Y-m-d H:i:s'),
        'deleted_by'          => $this->session->userdata('user_id'),
      ], ['payment_history_id' => $payment_history_id])) {
        $hasil = [
          'rooms'  => $this->getReservationRooms($reservation_id),
          'status' => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-check_extend_days') {
      $extend_days    = $this->input->post('extend_days');
      $reservation_id = $this->input->post('reservation_id');
      $data = $this->getReservationRooms($reservation_id, $extend_days);
      $hasil = [
        'rooms'  => $data['rooms'],
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'save-check_extend_days') {
      $extend_days    = $this->input->post('extend_days');
      $reservation_id = $this->input->post('reservation_id');

      if ($this->db->update('reservations', ['checkout_schedule' => $extend_days], ['reservation_id' => $reservation_id]) && $this->db->insert('extend_histories', [
        'reservation_id' => $reservation_id,
        'extend_before'  => $extend_days,
        'created_at'     => date('Y-m-d H:i:s'),
        'created_by'     => $this->session->userdata('user_id'),
      ])) {
        $hasil = [
          'rooms'  => $this->getReservationRooms($reservation_id),
          'extend_days' => $extend_days,
          'status' => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-change_rooms') {
      $room_plans = $this->input->post('room_plans');
      $checkin = date('Y-m-d');
      $checkin_year = date('Y');
      $checkout = $this->input->post('checkout');
      $hasil = [
        'rooms' => $this->db->query("SELECT rooms.room_id, rooms.room_number, t1.room_rate_id, COALESCE((SELECT session_name FROM sessions WHERE sessions.session_id=t1.session_id),NULL) AS session_name, IF(EXISTS(SELECT 1 FROM room_rates AS a WHERE a.room_type_id=rooms.room_type_id AND a.room_plan_id='$room_plans'), COALESCE((SELECT 'occupied' FROM rooms AS c JOIN room_reservations USING(room_id) JOIN reservations USING(reservation_id) WHERE c.room_id=rooms.room_id AND (('$checkin' BETWEEN `checkin_schedule` AND `checkout_schedule`) OR ('$checkout' BETWEEN `checkin_schedule` AND `checkout_schedule`) OR (`checkin_schedule` BETWEEN '$checkin' AND '$checkout') OR (`checkout_schedule` BETWEEN '$checkin' AND '$checkout')) AND `reservation_status` IN ('Reservation','Stay') LIMIT 1), IF(rooms.room_status='OO','out_of_service', IF(rooms.room_status='VD' OR rooms.room_status='OD','dirty',IF(rooms.room_status='VC' OR rooms.room_status='OC','clean','ready')))),'no_room_plans') AS room_status FROM rooms LEFT JOIN (SELECT room_rates.room_type_id,room_rates.room_rate_id,room_rates.session_id FROM room_rates JOIN (SELECT room_type_id, CASE WHEN COUNT(CASE WHEN session_id=(SELECT session_id FROM sessions WHERE '$checkin' BETWEEN DATE_FORMAT(start_session,'$checkin_year-%m-%d') AND DATE_FORMAT(end_session,CONCAT('$checkin_year'+(YEAR(end_session)- YEAR(start_session)),'-%m-%d'))) THEN 1 END) > 0 THEN (SELECT session_id FROM sessions WHERE '$checkin' BETWEEN DATE_FORMAT(start_session,'$checkin_year-%m-%d') AND DATE_FORMAT(end_session,CONCAT('$checkin_year'+(YEAR(end_session)- YEAR(start_session)),'-%m-%d'))) END AS session_id FROM room_rates GROUP BY room_type_id) AS a ON room_rates.room_type_id=a.room_type_id AND ((a.session_id IS NULL AND room_rates.session_id IS NULL) OR (a.session_id = room_rates.session_id)) WHERE room_plan_id='$room_plans') AS t1 USING(room_type_id)")->result_array(),
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'start-check_change_rooms') {
      $room_id = $this->input->post('room_id');
      $room_rate_id = $this->input->post('room_rate_id');
      $room_reservation_id = $this->input->post('room_reservation_id');



      $this->db->join('rooms', 'room_id');
      $this->db->join('room_types', 'room_type_id');
      $this->db->join('room_rates', 'room_rate_id');
      $this->db->join('sessions', 'session_id', 'left');
      $this->db->select('room_id, room_rate_id, room_type_name, room_number, session_name, room_reservations.room_price AS room_price');
      $r1  = $this->db->get_where("room_reservations", ['room_reservation_id' => $room_reservation_id])->row_array();


      $this->db->join('room_types', 'room_type_id');
      $this->db->select('room_id, room_type_name, room_number');
      $h1  = $this->db->get_where("rooms", ['room_id' => $room_id])->row_array();

      $this->db->join('sessions', 'session_id', 'left');
      $this->db->select('room_rate_id, session_name, room_price');
      $h2  = $this->db->get_where("room_rates", ['room_rate_id' => $room_rate_id])->row_array();
      $hasil = [
        'room_before' => [
          'room_id'        => $r1['room_id'],
          'room_rate_id'   => $r1['room_rate_id'],
          'room_type_name' => $r1['room_type_name'],
          'room_number'    => $r1['room_number'],
          'sessions'       => $r1['session_name'],
          'room_price'     => $r1['room_price'],
        ],
        'room_change' => [
          'room_id'        => $h1['room_id'],
          'room_rate_id'   => $h2['room_rate_id'],
          'room_type_name' => $h1['room_type_name'],
          'room_number'    => $h1['room_number'],
          'sessions'       => $h2['session_name'],
          'room_price'     => $h2['room_price'],
        ],
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'save-check_rooms_change') {
      $room_id  = $this->input->post('room_id');
      $room_price  = $this->input->post('room_price');
      $room_rate_id  = $this->input->post('room_rate_id');
      $room_reservation_id  = $this->input->post('room_reservation_id');
      $room_reservations = $this->db->get_where('room_reservations', ['room_reservation_id' => $room_reservation_id]);
      if ($room_reservations->num_rows() > 0) {
        $room_reservations = $room_reservations->row_array();
        $query = $this->db->update('room_reservations', [
          'room_id'      => $room_id,
          'room_price'   => $room_price,
          'room_rate_id' => $room_rate_id,
        ], ['room_reservation_id' => $room_reservation_id]) && $this->db->insert('room_change_histories', [
          'room_reservation_id' => $room_reservation_id,
          'room_change_type'    => 'switch room',
          'room_change_date'    => date('Y-m-d'),
          'room_rate_id'        => $room_reservations['room_rate_id'],
          'room_id'             => $room_reservations['room_id'],
          'room_price'          => $room_reservations['room_price'],
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ]) && $this->db->update('rooms', ['room_status' => 'VD'], ['room_id' => $room_reservations['room_id']]) && $this->db->insert('cleaning_histories', [
          'room_id'             => $room_reservations['room_id'],
          'cleaning_status'    => 'VD',
          'created_at'          => date('Y-m-d H:i:s'),
          'created_by'          => $this->session->userdata('user_id'),
        ]);
        if ($query) {
          $hasil = [
            'rooms'  => $this->getReservationRooms($room_reservations['reservation_id']),
            'status' => 'done',
          ];
        } else {
          $hasil['status'] = 'none';
        }
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'start-guest_data') {
      $date = $this->input->post('date');
      $this->db->join('guests', 'guest_id');
      $reservations  = $this->db->get_where("reservations", "'$date' BETWEEN checkin_schedule AND checkout_schedule")->result_array();
      $reservation = [];
      foreach ($reservations as $value) {
        $reservation[] = [
          'reservation_id'     => $value['reservation_id'],
          'reservation_number' => $value['reservation_number'],
          'guest_name'         => $value['guest_name'],
          'checkout'           => $value['checkout_schedule'],
          'in_house'          => $value['checkin_schedule'] . ' - ' . $value['checkout_schedule'],
        ];
      }
      $hasil = [
        'reservations' => $reservation,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'start-guest_detail') {
      $reservation_id = $this->input->post('reservation_id');

      $this->db->join('guests', 'guest_id');
      $this->db->join('segments', 'segment_id');
      $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
      $reservations  = $this->db->get_where("reservations", ['reservation_id' => $reservation_id])->row_array();
      $hasil = [
        'reservations' => $reservations,
        'rooms'        => $this->getReservationRooms($reservation_id),
        'status'       => 'done',
      ];
    }
    /////////////////////////////////
    else if ($this->input->post('set') == 'get_kamar_reservasi') {
      $id_reservasi = $this->input->post('id_reservasi');
      $this->db->join('kamar', 'id_kamar');
      $this->db->join('tkamar', 'id_tkamar');
      $kamar  = $this->db->get_where("kreservasi", ['id_reservasi' => $id_reservasi])->result_array();
      $hasil = [
        'kamar'     => $kamar,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'get_detail_kamar_reservasi') {
      $id_roomplans = $this->input->post('roomplans');
      $check_in = $this->input->post('check_in');
      $ycheck_in = date('Y', strtotime($check_in));
      $this->db->join('kamar', 'id_kamar');
      $this->db->join('tkamar', 'id_tkamar');
      $this->db->join('roomrates', 'id_roomrates');
      $this->db->join('roomplans', 'id_roomplans');
      $this->db->join('sessions', 'id_sessions', 'left');
      $kamar_reservasi = $this->db->get_where("kreservasi", ['id_kamar' => $this->input->post('id_kamar_pindah')])->row_array();



      $this->db->join('tkamar', 'id_tkamar');
      $this->db->join("(SELECT roomrates.*,COALESCE((SELECT nm_sessions FROM sessions WHERE sessions.id_sessions=t1.id_sessions),NULL) AS nm_sessions FROM roomrates JOIN (SELECT id_tkamar, CASE WHEN COUNT(CASE WHEN id_sessions=(SELECT id_sessions FROM `sessions` WHERE '$check_in' BETWEEN DATE_FORMAT(mulai_sessions,'$ycheck_in-%m-%d') AND DATE_FORMAT(akhir_sessions,CONCAT('$ycheck_in'+(YEAR(akhir_sessions)- YEAR(mulai_sessions)),'-%m-%d'))) THEN 1 END) > 0 THEN (SELECT id_sessions FROM `sessions` WHERE '$check_in' BETWEEN DATE_FORMAT(mulai_sessions,'$ycheck_in-%m-%d') AND DATE_FORMAT(akhir_sessions,CONCAT('$ycheck_in'+(YEAR(akhir_sessions)- YEAR(mulai_sessions)),'-%m-%d'))) END AS id_sessions FROM roomrates GROUP BY id_tkamar) AS t1 ON roomrates.id_tkamar=t1.id_tkamar AND ((t1.id_sessions IS NULL AND roomrates.id_sessions IS NULL) OR (t1.id_sessions = roomrates.id_sessions))) AS roomrates", 'id_tkamar');
      $kamar = $this->db->get_where("kamar", ['id_kamar' => $this->input->post('id_kamar')])->row_array();
      $hasil = [
        'kamar_reservasi' => $kamar_reservasi,
        'kamar'           => $kamar,
        'status'          => 'done',
      ];
    } else if ($this->input->post('set') == 'set_pindah_kamar') {
      $tgl_pindah    = $this->input->post('tgl_pindah');
      $id_kreservasi = $this->input->post('id_kreservasi');
      $id_roomrates  = $this->input->post('id_roomrates');
      $id_kamar      = $this->input->post('id_kamar');
      $harga_kamar   = $this->input->post('harga_kamar');
      $kreservasi    = $this->db->get_where("kreservasi", ['id_kreservasi' => $id_kreservasi]);
      if ($kreservasi->num_rows() > 0) {
        $kreservasi = $kreservasi->row_array();
        $query = $this->db->update('kreservasi', [
          'id_kamar'     => $id_kamar,
          'id_roomrates' => $id_roomrates,
          'harga_kamar'  => $harga_kamar,
        ], ['id_kreservasi' => $id_kreservasi]) && $this->db->insert('h_pkamar', [
          'id_user'       => $this->session->userdata('id_user'),
          'id_kreservasi' => $id_kreservasi,
          'id_roomrates' => $kreservasi['id_roomrates'],
          'tgl_pkamar'    => $tgl_pindah,
          'id_skamar'     => $kreservasi['id_kamar'],
          'hrg_skamar'    => $kreservasi['harga_kamar'],
          'wkt_hpkamar'   => date('Y-m-d H:i:s'),
        ]);
        if (!$query) {
          echo json_encode(['status' => 'none']);
          return;
        }
      } else {
        echo json_encode(['status' => 'none']);
        return;
      }
      $hasil['status'] = 'done';
    } else if ($this->input->post('set') == 'set_batal_reservasi') {
      $id_reservasi = $this->input->post('id_reservasi');
      $query = $this->db->update('reservasi', ['status' => 'Cancel'], ['id_reservasi' => $id_reservasi]) && $this->db->insert('h_psreservasi', [
        'id_user'          => $this->session->userdata('id_user'),
        'id_reservasi'     => $id_reservasi,
        'status_reservasi' => 'Cancel',
        'wkt_hpsreservasi' => date('Y-m-d H:i:s'),
      ]);

      $hasil['status'] = $query ? 'done' : 'none';
    } else if ($this->input->post('set') == 'set_checkin_reservasi') {
      $tgl_checkin = $this->input->post('tgl_checkin');
      $id_reservasi = $this->input->post('id_reservasi');
      $wkt_checkin = $this->input->post('wkt_checkin');
      $deposit = $this->input->post('deposit');

      $query = $this->db->get_where('kreservasi', ['id_reservasi' => $id_reservasi])->result_array();
      foreach ($query as $data_kamar) {
        $data_pkamar[] = [
          'id_kamar'  => $data_kamar['id_kamar'],
          'stt_kamar' => 'OC',
        ];
        $data_hpembersihan[] = [
          'id_user'          => $this->session->userdata('id_user'),
          'id_kamar'         => $data_kamar['id_kamar'],
          'stt_kamar'        => 'OC',
          'wkt_hpembersihan' => date('Y-m-d H:i:s'),
        ];
      }


      $query = $this->db->update('reservasi', ['deposit' => $deposit, 'status' => 'Menginap'], ['id_reservasi' => $id_reservasi]) && $this->db->update('kreservasi', ['tcheck_in' => $tgl_checkin . ' ' . $wkt_checkin . ':00'], ['id_reservasi' => $id_reservasi]) && $this->db->insert('h_psreservasi', [
        'id_user'          => $this->session->userdata('id_user'),
        'id_reservasi'     => $id_reservasi,
        'status_reservasi' => 'Menginap',
        'wkt_hpsreservasi' => date('Y-m-d H:i:s'),
      ]) && $this->db->update_batch('kamar', $data_pkamar, 'id_kamar') && $this->db->insert_batch('h_pembersihan', $data_hpembersihan);

      $hasil['status'] = $query ? 'done' : 'none';
    } else if ($this->input->post('set') == 'set_checkout_reservasi') {
      $tgl_checkout = $this->input->post('tgl_checkout');
      $id_reservasi = $this->input->post('id_reservasi');
      $wkt_checkout = $this->input->post('wkt_checkout');
      $sisa_pembayaran = $this->input->post('sisa_pembayaran');
      $this->db->join('kamar', 'id_kamar');
      $this->db->join('tkamar', 'id_tkamar');
      $query = $this->db->get_where('kreservasi', ['id_reservasi' => $id_reservasi])->result_array();

      $data_pkamar = [];
      $data_hpembersihan = [];
      foreach ($query as $data_kamar) {
        $data_pkamar[] = [
          'id_kamar'  => $data_kamar['id_kamar'],
          'stt_kamar' => 'VD',
        ];
        $data_hpembersihan[] = [
          'id_user'          => $this->session->userdata('id_user'),
          'id_kamar'         => $data_kamar['id_kamar'],
          'stt_kamar'        => 'VD',
          'wkt_hpembersihan' => date('Y-m-d H:i:s'),
        ];
      }

      $query = $this->db->update('reservasi', ['sisa_pembayaran' => $sisa_pembayaran, 'status' => 'Selesai'], ['id_reservasi' => $id_reservasi]) && $this->db->update('kreservasi', ['tcheck_out' => $tgl_checkout . ' ' . $wkt_checkout . ':00'], ['id_reservasi' => $id_reservasi]) && $this->db->insert('h_psreservasi', [
        'id_user'          => $this->session->userdata('id_user'),
        'id_reservasi'     => $id_reservasi,
        'status_reservasi' => 'Selesai',
        'wkt_hpsreservasi' => date('Y-m-d H:i:s'),
      ]) && $this->db->insert('h_psreservasi', [
        'id_user'          => $this->session->userdata('id_user'),
        'id_reservasi'     => $id_reservasi,
        'status_reservasi' => 'Selesai',
        'wkt_hpsreservasi' => date('Y-m-d H:i:s'),
      ]) && $this->db->update_batch('kamar', $data_pkamar, 'id_kamar') && $this->db->insert_batch('h_pembersihan', $data_hpembersihan);

      $hasil['status'] = $query ? 'done' : 'none';
    } else if ($this->input->post('set') == 'get_dcheckin') {
      $this->db->join('tamu', 'id_tamu');
      $reservasi  = $this->db->get_where("reservasi", ['status' => 'Menginap'])->result_array();
      $hasil = [
        'checkin' => $reservasi,
        'status'    => 'done',
      ];
    } else if ($this->input->post('set') == 'set_tamu') {
      $nm_tamu        = $this->input->post('nm_tamu');
      $warga_negara   = $this->input->post('warga_negara');
      $tipe_identitas = $this->input->post('tipe_identitas');
      $no_identitas   = $this->input->post('no_identitas');
      $no_telp        = $this->input->post('no_telp');
      $tgl_lahir      = $this->input->post('tgl_lahir');
      $email          = $this->input->post('email');
      $almt_tamu      = $this->input->post('almt_tamu');


      if ($this->db->insert('tamu', [
        'nm_tamu'        => $nm_tamu,
        'warga_negara'   => $warga_negara,
        'tipe_identitas' => $tipe_identitas,
        'no_identitas'   => $no_identitas,
        'no_telp'        => $no_telp,
        'tgl_lahir'      => $tgl_lahir,
        'email'          => $email,
        'almt_tamu'      => $almt_tamu,
      ])) {

        $this->db->select("id_tamu AS `id`, CONCAT(nm_tamu,' (', tipe_identitas,' - ', no_identitas, ') ') AS `text`");
        $hasil['tamu'] = $this->db->get_where("tamu")->result_array();
        $this->db->order_by('nm_tamu');
        $this->db->select("id_tamu AS `id`, CONCAT(nm_tamu,' (', tipe_identitas,' - ', no_identitas, ') ') AS `text`");
        $hasil['tamu'] = $this->db->get_where("tamu")->result_array();
        $hasil['status'] = 'done';
      } else {
        $hasil['status'] = 'none';
      };
    } else if ($this->input->post('set') == 'get_roomplans') {
      $this->db->select("id_roomplans AS `id`, nm_roomplans AS `text`");
      $hasil = [
        'roomplans' => $this->db->get_where("roomplans")->result_array(),
        'status' => 'done',
      ];
    } else {
      $hasil['status'] = 'none';
    }
    echo json_encode($hasil);
  }
}
