<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_types extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Room_types_model', 'room_type');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('room_type_name', $this->lang->line('field-room_type_name'), 'required|trim|is_unique[room_types.room_type_name]');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('room_types/main', ['room_type' => null], true),
        'add_css'    => $this->load->view('room_types/add_css', null, true),
        'add_script' => $this->load->view('room_types/add_script', null, true),
        'own_script' => $this->load->view('room_types/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-room_types'),
          'content'        => $this->lang->line('table-room_types'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_types')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-room_types')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->room_type->insertRoom_types();
    }
  }


  public function update()
  {
    $room_type_id = $this->uri->segment(3);
    if ($room_type_id != '') {
      $this->form_validation->set_rules('room_type_name', $this->lang->line('field-room_type_name'), "required|trim|is_unique[room_types.room_type_id!='$room_type_id' AND room_type_name=]");
      if ($this->form_validation->run() == FALSE) {
        $room_type = $this->db->get_where('room_types', ['room_type_id' => $room_type_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('room_types/main', ['room_type' => $room_type], true),
          'add_css'    => $this->load->view('room_types/add_css', null, true),
          'add_script' => $this->load->view('room_types/add_script', null, true),
          'own_script' => $this->load->view('room_types/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-room_types'),
            'content'        => $this->lang->line('table-update-room_types'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-room_types')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-room_types')   => base_url('room_types'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->room_type->updateRoom_types($room_type_id, 'room_types', 'room_types/update/' . $room_type_id);
      }
    } else {
      redirect('room_types');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-room_types')) {
      $this->room_type->deleteRoom_types($this->input->post('room_type_id'), 'room_types');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('room_types');
    }
  }


  public function get_data()
  {
    $this->room_type->getData();
  }
}
