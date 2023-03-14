<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
  public function insertUsers($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'user_fullname' => $this->input->post('user_fullname'),
      'username'      => $this->input->post('username'),
      'password'      => $this->input->post('password'),
      'user_access'   => $this->input->post('user_access'),
    ];

    if ($_FILES['user_photo']['name']) {
      $config['allowed_types'] = 'gif|jpg|png';
      $config['upload_path'] = './uploads/users/';
      $config['max_size']     = '2048';
      $this->load->library('upload', $config);
      if ($this->upload->do_upload('user_photo')) {
        $data['user_photo'] = $this->upload->data('file_name');
      } else {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-body-error'), $this->upload->display_errors()));
        redirect($redirect);
      }
    }
    if ($this->db->insert('users', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-users')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateUsers($user_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;
    $user = $this->db->get_where('users', ['user_id' => $user_id])->row_array();

    $data = [
      'user_fullname' => $this->input->post('user_fullname'),
      'username'      => $this->input->post('username'),
      'password'      => $this->input->post('password'),
      'user_access'   => $this->input->post('user_access'),
    ];

    if ($_FILES['user_photo']['name']) {
      $config['allowed_types'] = 'gif|jpg|png';
      $config['upload_path'] = './uploads/users/';
      $config['max_size']     = '2048';
      $this->load->library('upload', $config);
      if ($this->upload->do_upload('user_photo')) {
        $data['user_photo'] = $this->upload->data('file_name');
      } else {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-body-error'), $this->upload->display_errors()));
        redirect($redirect);
      }
    }
    if ($this->db->update('users', $data, ['user_id' => $user_id])) {
      if ($user['user_photo'] != '') unlink(FCPATH . 'uploads/users/' . $user['user_photo']);
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-users')));
      redirect($return);
    } else {
      if ($data['user_photo'] != '') unlink(FCPATH . 'uploads/users/' . $data['user_photo']);
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteUsers($user_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $user = $this->db->get_where('users', ['user_id' => $user_id]);
    if ($user->num_rows() > 0) {
      $data = $user->row_array();
      if ($this->db->delete('users', ['user_id' => $user_id])) {
        if ($data['user_photo'] != '') unlink(FCPATH . 'uploads/users/' . $data['user_photo']);
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-users')));
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
