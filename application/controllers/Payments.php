<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Payments_model', 'payment');
    // kicked('admin');
    kicked('xxx');
  }

  public function index()
  {
    $this->form_validation->set_rules('payment_name', $this->lang->line('field-payment_name'), 'required|trim|is_unique[payments.payment_name]');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('payments/main', ['payment' => null], true),
        'add_css'    => $this->load->view('payments/add_css', null, true),
        'add_script' => $this->load->view('payments/add_script', null, true),
        'own_script' => $this->load->view('payments/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-payments'),
          'content'        => $this->lang->line('table-payments'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-payments')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-payments')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->payment->insertPayments();
    }
  }


  public function update()
  {
    $payment_id = $this->uri->segment(3);
    if ($payment_id != '') {
      $this->form_validation->set_rules('payment_name', $this->lang->line('field-payment_name'), "required|trim|is_unique[payments.payment_id!='$payment_id' AND payment_name=]");
      if ($this->form_validation->run() == FALSE) {
        $payment = $this->db->get_where('payments', ['payment_id' => $payment_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('payments/main', ['payment' => $payment], true),
          'add_css'    => $this->load->view('payments/add_css', null, true),
          'add_script' => $this->load->view('payments/add_script', null, true),
          'own_script' => $this->load->view('payments/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-payments'),
            'content'        => $this->lang->line('table-update-payments'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-payments')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-payments')   => base_url('payments'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->payment->updatePayments($payment_id, 'payments', 'payments/update/' . $payment_id);
      }
    } else {
      redirect('payments');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-payments')) {
      $this->payment->deletePayments($this->input->post('payment_id'), 'payments');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('payments');
    }
  }
}
