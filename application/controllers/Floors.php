<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Floors extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Floors_model', 'floor');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('floor_name', $this->lang->line('field-floor_name'), 'required|trim|is_unique[floors.floor_name]');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('floors/main', ['floor' => null], true),
        'add_css'    => $this->load->view('floors/add_css', null, true),
        'add_script' => $this->load->view('floors/add_script', null, true),
        'own_script' => $this->load->view('floors/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-floors'),
          'content'        => $this->lang->line('table-floors'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-floors')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-floors')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->floor->insertFloors();
    }
  }


  public function update()
  {
    $floor_id = $this->uri->segment(3);
    if ($floor_id != '') {
      $this->form_validation->set_rules('floor_name', $this->lang->line('field-floor_name'), "required|trim|is_unique[floors.floor_id!='$floor_id' AND floor_name=]");
      if ($this->form_validation->run() == FALSE) {
        $floor = $this->db->get_where('floors', ['floor_id' => $floor_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('floors/main', ['floor' => $floor], true),
          'add_css'    => $this->load->view('floors/add_css', null, true),
          'add_script' => $this->load->view('floors/add_script', null, true),
          'own_script' => $this->load->view('floors/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-floors'),
            'content'        => $this->lang->line('table-update-floors'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-floors')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-floors')   => base_url('floors'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->floor->updateFloors($floor_id, 'floors', 'floors/update/' . $floor_id);
      }
    } else {
      redirect('floors');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-floors')) {
      $this->floor->deleteFloors($this->input->post('floor_id'), 'floors');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('floors');
    }
  }
}
