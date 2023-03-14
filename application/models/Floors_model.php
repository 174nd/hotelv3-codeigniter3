<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Floors_model extends CI_Model
{
  public function insertFloors($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'floor_name' => $this->input->post('floor_name'),
    ];

    if ($this->db->insert('floors', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-floors')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateFloors($floor_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'floor_name' => $this->input->post('floor_name'),
    ];

    if ($this->db->update('floors', $data, ['floor_id' => $floor_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-floors')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteFloors($floor_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $floor = $this->db->get_where('floors', ['floor_id' => $floor_id]);
    if ($floor->num_rows() > 0) {
      if ($this->db->delete('floors', ['floor_id' => $floor_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-floors')));
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
