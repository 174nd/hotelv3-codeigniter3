<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_plans extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Room_plans_model', 'room_plan');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('room_plan_name', $this->lang->line('field-room_plan_name'), 'required|trim|is_unique[room_plans.room_plan_name]');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('room_plans/main', ['room_plan' => null], true),
        'add_css'    => $this->load->view('room_plans/add_css', null, true),
        'add_script' => $this->load->view('room_plans/add_script', null, true),
        'own_script' => $this->load->view('room_plans/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-room_plans'),
          'content'        => $this->lang->line('table-room_plans'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_plans')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-room_plans')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->room_plan->insertRoom_plans();
    }
  }


  public function update()
  {
    $room_plan_id = $this->uri->segment(3);
    if ($room_plan_id != '') {
      $this->form_validation->set_rules('room_plan_name', $this->lang->line('field-room_plan_name'), "required|trim|is_unique[room_plans.room_plan_id!='$room_plan_id' AND room_plan_name=]");
      if ($this->form_validation->run() == FALSE) {
        $room_plan = $this->db->get_where('room_plans', ['room_plan_id' => $room_plan_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('room_plans/main', ['room_plan' => $room_plan], true),
          'add_css'    => $this->load->view('room_plans/add_css', null, true),
          'add_script' => $this->load->view('room_plans/add_script', null, true),
          'own_script' => $this->load->view('room_plans/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-room_plans'),
            'content'        => $this->lang->line('table-update-room_plans'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_plans')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-room_plans')   => base_url('room_plans'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->room_plan->updateRoom_plans($room_plan_id, 'room_plans', 'room_plans/update/' . $room_plan_id);
      }
    } else {
      redirect('room_plans');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-room_plans')) {
      $this->room_plan->deleteRoom_plans($this->input->post('room_plan_id'), 'room_plans');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('room_plans');
    }
  }
}
