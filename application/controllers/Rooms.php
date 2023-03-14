<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rooms extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Rooms_model', 'room');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('room_number', $this->lang->line('field-room_number'), 'required|trim|is_unique[rooms.room_number]');
    $this->form_validation->set_rules('room_type_id', $this->lang->line('text-room_type'), 'required|trim');
    $this->form_validation->set_rules('floor_id', $this->lang->line('text-floor'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->db->order_by('room_type_name', 'asc');
      $room_types = $this->db->get_where('room_types')->result_array();
      $this->db->order_by('floor_name', 'asc');
      $floors = $this->db->get_where('floors')->result_array();
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('rooms/main', ['room' => null, 'room_types' => $room_types, 'floors' => $floors], true),
        'add_css'    => $this->load->view('rooms/add_css', null, true),
        'add_script' => $this->load->view('rooms/add_script', null, true),
        'own_script' => $this->load->view('rooms/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-rooms'),
          'content'        => $this->lang->line('table-rooms'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-rooms')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-rooms')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->room->insertRooms();
    }
  }


  public function update()
  {
    $room_id = $this->uri->segment(3);
    if ($room_id != '') {
      $this->form_validation->set_rules('room_number', $this->lang->line('field-room_number'), "required|trim|is_unique[rooms.room_id!='$room_id' AND room_number=]");
      $this->form_validation->set_rules('room_type_id', $this->lang->line('text-room_type'), 'required|trim');
      $this->form_validation->set_rules('floor_id', $this->lang->line('text-floor'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $room = $this->db->get_where('rooms', ['room_id' => $room_id])->row_array();
        $this->db->order_by('room_type_name', 'asc');
        $room_types = $this->db->get_where('room_types')->result_array();
        $this->db->order_by('floor_name', 'asc');
        $floors = $this->db->get_where('floors')->result_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('rooms/main', ['room' => $room, 'room_types' => $room_types, 'floors' => $floors], true),
          'add_css'    => $this->load->view('rooms/add_css', null, true),
          'add_script' => $this->load->view('rooms/add_script', null, true),
          'own_script' => $this->load->view('rooms/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-rooms'),
            'content'        => $this->lang->line('table-update-rooms'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-rooms')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-rooms')   => base_url('rooms'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->room->updateRooms($room_id, 'rooms', 'rooms/update/' . $room_id);
      }
    } else {
      redirect('rooms');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-rooms')) {
      $this->room->deleteRooms($this->input->post('room_id'), 'rooms');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('rooms');
    }
  }
}
