<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_types_model extends CI_Model
{
  public function insertRoom_types($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_type_name' => $this->input->post('room_type_name'),
    ];

    if ($this->db->insert('room_types', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-room_types')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateRoom_types($room_type_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_type_name' => $this->input->post('room_type_name'),
    ];

    if ($this->db->update('room_types', $data, ['room_type_id' => $room_type_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-room_types')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteRoom_types($room_type_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $room_type = $this->db->get_where('room_types', ['room_type_id' => $room_type_id]);
    if ($room_type->num_rows() > 0) {
      if ($this->db->delete('room_types', ['room_type_id' => $room_type_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-room_types')));
        redirect($return);
      } else {
        $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
        redirect($return);
      }
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($return);
    }
  }

  public function getData()
  {
    if ($this->input->post('set') == 'get_room_types') {
      $room_type_id = $this->input->post('room_type_id');
      $this->db->select("*,(SELECT COUNT(room_id) FROM rooms WHERE room_type_id='$room_type_id') AS total_rooms");
      $sql = $this->db->get_where("room_types", ['room_type_id' => $room_type_id]);
      if ($sql->num_rows() > 0) {
        $hasil = $sql->row_array();
        $hasil['rooms'] = $this->db->get_where("rooms", ['room_type_id' => $room_type_id])->result_array();
        $hasil['status'] = 'done';
      } else {
        $hasil['status'] = 'none';
      }
    } else {
      $hasil['status'] = 'none';
    }
    echo json_encode($hasil);
  }
}
