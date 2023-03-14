<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments_model extends CI_Model
{
  public function insertPayments($get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'payment_name' => $this->input->post('payment_name'),
    ];

    if ($this->db->insert('payments', $data)) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-insert_success'), $this->lang->line('table-payments')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function updatePayments($payment_id, $get_return = null, $get_redirect = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;
    $redirect = $get_redirect == null ? uri_string() : $get_redirect;

    $data = [
      'payment_name' => $this->input->post('payment_name'),
    ];

    if ($this->db->update('payments', $data, ['payment_id' => $payment_id])) {
      $this->session->set_flashdata('message', sprintf($this->lang->line('alert-updated_success'), $this->lang->line('table-payments')));
      redirect($return);
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect($redirect);
    }
  }

  public function deletePayments($payment_id, $get_return = null)
  {
    $return = $get_return == null ? uri_string() : $get_return;

    $payment = $this->db->get_where('payments', ['payment_id' => $payment_id]);
    if ($payment->num_rows() > 0) {
      if ($this->db->delete('payments', ['payment_id' => $payment_id])) {
        $this->session->set_flashdata('message', sprintf($this->lang->line('alert-delete_success'), $this->lang->line('table-payments')));
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
