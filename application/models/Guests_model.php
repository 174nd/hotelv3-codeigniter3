<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guests_model extends CI_Model
{
  public function insertGuests($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'guest_name'      => $this->input->post('guest_name'),
      'identity_type'   => $this->input->post('identity_type'),
      'identity_number' => $this->input->post('identity_number'),
      'national'        => $this->input->post('national'),
      'birth_date'      => $this->input->post('birth_date'),
      'guest_address'   => $this->input->post('guest_address'),
      'phone_number'    => $this->input->post('phone_number'),
      'email'           => $this->input->post('email'),
    ];

    if ($this->db->insert('guests', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-guests')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updateGuests($guest_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'guest_name'      => $this->input->post('guest_name'),
      'identity_type'   => $this->input->post('identity_type'),
      'identity_number' => $this->input->post('identity_number'),
      'national'        => $this->input->post('national'),
      'birth_date'      => $this->input->post('birth_date'),
      'guest_address'   => $this->input->post('guest_address'),
      'phone_number'    => $this->input->post('phone_number'),
      'email'           => $this->input->post('email'),
    ];

    if ($this->db->update('guests', $data, ['guest_id' => $guest_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-guests')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deleteGuests($guest_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $guest = $this->db->get_where('guests', ['guest_id' => $guest_id]);
    if ($guest->num_rows() > 0) {
      if ($this->db->delete('guests', ['guest_id' => $guest_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-guests')));
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
    if ($this->input->post('set') == 'get_guests') {
      $guest_id = $this->input->post('guest_id');
      $sql = $this->db->get_where("guests", ['guest_id' => $guest_id]);
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
