<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Segments_model extends CI_Model
{
  public function insertSegments($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'segment_name'  => $this->input->post('segment_name'),
      'segment_type'   => $this->input->post('segment_type'),
    ];

    if ($this->db->insert('segments', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-segments')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateSegments($segment_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'segment_name' => $this->input->post('segment_name'),
      'segment_type' => $this->input->post('segment_type'),
    ];

    if ($this->db->update('segments', $data, ['segment_id' => $segment_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-segments')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteSegments($segment_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $segment = $this->db->get_where('segments', ['segment_id' => $segment_id]);
    if ($segment->num_rows() > 0) {
      if ($this->db->delete('segments', ['segment_id' => $segment_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-segments')));
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
