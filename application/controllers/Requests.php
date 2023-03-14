<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Requests extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Requests_model', 'request');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('request_name', $this->lang->line('field-request_name'), 'required|trim|is_unique[requests.request_name]');
    $this->form_validation->set_rules('request_price', $this->lang->line('field-request_price'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('requests/main', ['request' => null], true),
        'add_css'    => $this->load->view('requests/add_css', null, true),
        'add_script' => $this->load->view('requests/add_script', null, true),
        'own_script' => $this->load->view('requests/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-requests'),
          'content'        => $this->lang->line('table-requests'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-requests')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-requests')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->request->insertRequests();
    }
  }


  public function update()
  {
    $request_id = $this->uri->segment(3);
    if ($request_id != '') {
      $this->form_validation->set_rules('request_name', $this->lang->line('field-request_name'), "required|trim|is_unique[requests.request_id!='$request_id' AND request_name=]");
      $this->form_validation->set_rules('request_price', $this->lang->line('field-request_price'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $request = $this->db->get_where('requests', ['request_id' => $request_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('requests/main', ['request' => $request], true),
          'add_css'    => $this->load->view('requests/add_css', null, true),
          'add_script' => $this->load->view('requests/add_script', null, true),
          'own_script' => $this->load->view('requests/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-requests'),
            'content'        => $this->lang->line('table-update-requests'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-requests')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-requests')   => base_url('requests'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->request->updateRequests($request_id, 'requests', 'requests/update/' . $request_id);
      }
    } else {
      redirect('requests');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-requests')) {
      $this->request->deleteRequests($this->input->post('request_id'), 'requests');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('requests');
    }
  }
}
