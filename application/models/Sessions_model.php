<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sessions_model extends CI_Model
{
  public function insertSessions($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'session_name'  => $this->input->post('session_name'),
      'start_session' => $this->input->post('start_session'),
      'end_session'   => $this->input->post('end_session'),
    ];

    if ($this->db->insert('sessions', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-sessions')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateSessions($session_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'session_name'  => $this->input->post('session_name'),
      'start_session' => $this->input->post('start_session'),
      'end_session'   => $this->input->post('end_session'),
    ];

    if ($this->db->update('sessions', $data, ['session_id' => $session_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-sessions')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteSessions($session_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $session = $this->db->get_where('sessions', ['session_id' => $session_id]);
    if ($session->num_rows() > 0) {
      if ($this->db->delete('sessions', ['session_id' => $session_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-sessions')));
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
    if ($this->input->post('set') == 'get_sessions') {
      $session_id = $this->input->post('session_id');
      $sql = $this->db->get_where("sessions", ['session_id' => $session_id]);
      if ($sql->num_rows() > 0) {
        $hasil = $sql->row_array();
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
