<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rooms_model extends CI_Model
{
  public function insertRooms($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_number'  => $this->input->post('room_number'),
      'room_type_id' => $this->input->post('room_type_id'),
      'floor_id'     => $this->input->post('floor_id'),
    ];

    if ($this->db->insert('rooms', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-rooms')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateRooms($room_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_number'  => $this->input->post('room_number'),
      'room_type_id' => $this->input->post('room_type_id'),
      'floor_id'     => $this->input->post('floor_id'),
    ];

    if ($this->db->update('rooms', $data, ['room_id' => $room_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-rooms')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteRooms($room_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $room = $this->db->get_where('rooms', ['room_id' => $room_id]);
    if ($room->num_rows() > 0) {
      if ($this->db->delete('rooms', ['room_id' => $room_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-rooms')));
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
}
