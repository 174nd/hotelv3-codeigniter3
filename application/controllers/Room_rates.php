<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_rates extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Room_rates_model', 'room_rate');
    kicked('admin');
  }

  public function index()
  {
    $room_plan_id = $this->input->post('room_plan_id');
    // $room_type_id = $this->input->post('room_type_id');
    $session_id = $this->input->post('session_id') != null ? "session_id='" . $this->input->post('session_id') . "'" : 'session_id IS NULL';
    $this->form_validation->set_rules('room_type_id', $this->lang->line('field-room_type_id'), "required|trim|is_unique[room_rates.room_plan_id='$room_plan_id' AND $session_id AND room_type_id=]");
    $this->form_validation->set_rules('room_price', $this->lang->line('field-room_price'), 'required|trim');
    $this->form_validation->set_rules('room_plan_id', $this->lang->line('field-room_plan_id'), 'required|trim');
    // $this->form_validation->set_rules('session_id', $this->lang->line('field-session_id'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->db->order_by('room_type_name', 'asc');
      $room_types = $this->db->get_where('room_types')->result_array();
      $this->db->order_by('session_name', 'asc');
      $sessions = $this->db->get_where('sessions')->result_array();
      $this->db->order_by('room_plan_name', 'asc');
      $room_plans = $this->db->get_where('room_plans')->result_array();
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('room_rates/main', ['room_rate' => null, 'room_types' => $room_types, 'sessions' => $sessions, 'room_plans' => $room_plans], true),
        'add_css'    => $this->load->view('room_rates/add_css', null, true),
        'add_script' => $this->load->view('room_rates/add_script', null, true),
        'own_script' => $this->load->view('room_rates/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-room_rates'),
          'content'        => $this->lang->line('table-room_rates'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_rates')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-room_rates')    => 'active',
          ],
        ],
      ]);
    } else {
      // echo "SELECT * FROM room_rates WHERE room_plan_id='$room_plan_id' AND $session_id AND room_type_id='$room_type_id'";
      $this->room_rate->insertRoom_rates();
    }
  }


  public function update()
  {
    $room_rate_id = $this->uri->segment(3);
    if ($room_rate_id != '') {
      $room_plan_id = $this->input->post('room_plan_id');

      $session_id = $this->input->post('session_id') != null ? "session_id='" . $this->input->post('session_id') . "'" : 'session_id IS NULL';
      $this->form_validation->set_rules('room_type_id', $this->lang->line('field-room_type_id'), "required|trim|is_unique[room_rates.room_rate_id!='$room_rate_id' AND room_plan_id='$room_plan_id' AND $session_id AND room_type_id=]");
      $this->form_validation->set_rules('room_price', $this->lang->line('field-room_price'), 'required|trim');
      $this->form_validation->set_rules('room_plan_id', $this->lang->line('field-room_plan_id'), 'required|trim');
      // $this->form_validation->set_rules('session_id', $this->lang->line('field-session_id'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $room_rate = $this->db->get_where('room_rates', ['room_rate_id' => $room_rate_id])->row_array();
        $this->db->order_by('room_type_name', 'asc');
        $room_types = $this->db->get_where('room_types')->result_array();
        $this->db->order_by('session_name', 'asc');
        $sessions = $this->db->get_where('sessions')->result_array();
        $this->db->order_by('room_plan_name', 'asc');
        $room_plans = $this->db->get_where('room_plans')->result_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('room_rates/main', ['room_rate' => $room_rate, 'room_types' => $room_types, 'sessions' => $sessions, 'room_plans' => $room_plans], true),
          'add_css'    => $this->load->view('room_rates/add_css', null, true),
          'add_script' => $this->load->view('room_rates/add_script', null, true),
          'own_script' => $this->load->view('room_rates/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-room_rates'),
            'content'        => $this->lang->line('table-update-room_rates'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_rates')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-room_rates')   => base_url('room_rates'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->room_rate->updateRoom_rates($room_rate_id, 'room_rates', 'room_rates/update/' . $room_rate_id);
      }
    } else {
      redirect('room_rates');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-room_rates')) {
      $this->room_rate->deleteRoom_rates($this->input->post('room_rate_id'), 'room_rates');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('room_rates');
    }
  }


  public function get_data()
  {
    $this->room_rate->getData();
  }
}
