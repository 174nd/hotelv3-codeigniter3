<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Housekeeping_model extends CI_Model
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
    } else if ($this->input->post('set') == 'start-check_rooms') {
      $this->db->join('room_types', 'room_type_id');
      $hasil = [
        'rooms' => $this->db->get_where("rooms")->result_array(),
        'status' => 'done',
      ];
    } else if ($this->input->post('set') == 'start-room_status') {
      $room_id  = $this->input->post('room_id');

      $this->db->join('room_types', 'room_type_id');
      $this->db->select("*,COALESCE((select cleaning_description from cleaning_histories where cleaning_histories.room_id=rooms.room_id order by created_at DESC limit 1),'-') AS cleaning_description");
      $rooms = $this->db->get_where('rooms', ['room_id' => $room_id]);
      if ($rooms->num_rows() > 0) {
        $rooms = $rooms->row_array();
        $change_status = [];
        switch ($rooms['room_status']) {
          case 'VR':
            $change_status = [
              ['id' => 'VC', 'text' => $this->lang->line('text-VC')],
              ['id' => 'VD', 'text' => $this->lang->line('text-VD')],
              ['id' => 'OO', 'text' => $this->lang->line('text-OO')],
            ];
            break;
          case 'VC':
            $change_status = [
              ['id' => 'VR', 'text' => $this->lang->line('text-VR')],
              ['id' => 'VD', 'text' => $this->lang->line('text-VD')],
              ['id' => 'OO', 'text' => $this->lang->line('text-OO')],
            ];
            break;
          case 'VD':
            $change_status = [
              ['id' => 'VR', 'text' => $this->lang->line('text-VR')],
              ['id' => 'VC', 'text' => $this->lang->line('text-VC')],
              ['id' => 'OO', 'text' => $this->lang->line('text-OO')],
            ];
            break;
          case 'OD':
            $change_status = [
              ['id' => 'OC', 'text' => $this->lang->line('text-OC')],
            ];
            break;
          case 'OC':
            $change_status = [
              ['id' => 'OD', 'text' => $this->lang->line('text-OD')],
            ];
            break;
          default:
            $change_status = [
              ['id' => 'VR', 'text' => $this->lang->line('text-VR')],
              ['id' => 'VC', 'text' => $this->lang->line('text-VC')],
              ['id' => 'VD', 'text' => $this->lang->line('text-VD')],
            ];
        }

        $hasil = [
          'change_status'        => $change_status,
          'room_status'          => $this->lang->line('text-' . $rooms['room_status']),
          'cleaning_description' => $rooms['cleaning_description'],
          'room_type_name'       => $rooms['room_type_name'],
          'room_number'          => $rooms['room_number'],
          'status'               => 'done',
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else if ($this->input->post('set') == 'save-room_status') {
      $room_id       = $this->input->post('room_id');
      $change_status = $this->input->post('change_status');
      $cleaning_description = $this->input->post('cleaning_description');

      $rooms = $this->db->get_where('rooms', ['room_id' => $room_id]);
      if ($rooms->num_rows() > 0) {
        $query = $this->db->update('rooms', ['room_status' => $change_status], ['room_id' => $room_id]) && $this->db->insert('cleaning_histories', [
          'room_id'              => $room_id,
          'cleaning_status'      => $change_status,
          'cleaning_description' => $cleaning_description,
          'created_at'           => date('Y-m-d H:i:s'),
          'created_by'           => $this->session->userdata('user_id'),
        ]);
        $hasil['status'] = $query ? 'done' : 'none';
      } else {
        $hasil['status'] = 'none';
      }
    } else {
      $hasil['status'] = 'none';
    }
    echo json_encode($hasil);
  }
}
