<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Requests_model extends CI_Model
{
  public function insertRequests($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'request_name'  => $this->input->post('request_name'),
      'request_price' => str_replace('.', '', $this->input->post('request_price')),
    ];

    if ($this->db->insert('requests', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-requests')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateRequests($request_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'request_name'  => $this->input->post('request_name'),
      'request_price' => str_replace('.', '', $this->input->post('request_price')),
    ];

    if ($this->db->update('requests', $data, ['request_id' => $request_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-requests')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteRequests($request_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $request = $this->db->get_where('requests', ['request_id' => $request_id]);
    if ($request->num_rows() > 0) {
      if ($this->db->delete('requests', ['request_id' => $request_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-requests')));
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
