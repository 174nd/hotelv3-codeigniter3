<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_plans_model extends CI_Model
{
  public function insertRoom_plans($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_plan_name' => $this->input->post('room_plan_name'),
    ];

    if ($this->db->insert('room_plans', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-room_plans')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateRoom_plans($room_plan_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'room_plan_name' => $this->input->post('room_plan_name'),
    ];

    if ($this->db->update('room_plans', $data, ['room_plan_id' => $room_plan_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-room_plans')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteRoom_plans($room_plan_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $room_plan = $this->db->get_where('room_plans', ['room_plan_id' => $room_plan_id]);
    if ($room_plan->num_rows() > 0) {
      if ($this->db->delete('room_plans', ['room_plan_id' => $room_plan_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-room_plans')));
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
