<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Nightaudit_model extends CI_Model
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
    } else if ($this->input->post('set') == 'start-nightaudit') {
      $date = $this->input->post('date');
      $query = "SELECT night_audit_id FROM night_audits WHERE night_audit_date='$date'";
      if ($this->db->query($query)->num_rows() > 0) {
        // $hasil = ['date' => $date, 'room_status' => 'done', 'status' => 'done'];
        $hasil = ['room_status' => 'done', 'status' => 'done'];
      } else {
        // $hasil = ['date' => $date, 'room_status' => 'none', 'status' => 'done'];
        $hasil = ['room_status' => 'none', 'status' => 'done'];
      }
    } else if ($this->input->post('set') == 'save-submit_nightaudit') {
      $date = $this->input->post('date') != '' ? $this->input->post('date') : null;
      $query = $this->db->insert('night_audits', ['night_audit_date' => $date, 'night_audit_time' => date('Y-m-d H:i:s'), 'user_id' => $this->session->userdata('user_id')]);

      $hasil['status'] = $query ? 'done' : 'none';
    } else {
      $hasil['status'] = 'none';
    }
    echo json_encode($hasil);
  }
}
