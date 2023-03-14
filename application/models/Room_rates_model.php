<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_rates_model extends CI_Model
{
  public function insertRoom_rates($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_type_id' => $this->input->post('room_type_id'),
      'room_price'   => str_replace('.', '', $this->input->post('room_price')),
      'room_plan_id' => $this->input->post('room_plan_id'),
      'session_id'   => $this->input->post('session_id') == 0 ? null : $this->input->post('session_id'),
    ];

    if ($this->db->insert('room_rates', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-room_rates')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateRoom_rates($room_rate_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_type_id' => $this->input->post('room_type_id'),
      'room_price'   => str_replace('.', '', $this->input->post('room_price')),
      'room_plan_id' => $this->input->post('room_plan_id'),
      'session_id'   => $this->input->post('session_id') == 0 ? null : $this->input->post('session_id'),
    ];

    if ($this->db->update('room_rates', $data, ['room_rate_id' => $room_rate_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-room_rates')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteRoom_rates($room_rate_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $room_rate = $this->db->get_where('room_rates', ['room_rate_id' => $room_rate_id]);
    if ($room_rate->num_rows() > 0) {
      if ($this->db->delete('room_rates', ['room_rate_id' => $room_rate_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-room_rates')));
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
    if ($this->input->post('set') == 'get_room_rates') {
      $room_rate_id = $this->input->post('room_rate_id');
      $this->db->join('room_types', 'room_type_id');
      $this->db->join('room_plans', 'room_plan_id');
      $this->db->join('sessions', 'session_id', 'left');
      $sql = $this->db->get_where("room_rates", ['room_rate_id' => $room_rate_id]);
      if ($sql->num_rows() > 0) {
        $hasil = $sql->row_array();
        $hasil['rooms'] = $this->db->get_where("rooms", ['room_type_id' => $hasil['room_type_id']])->result_array();
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
