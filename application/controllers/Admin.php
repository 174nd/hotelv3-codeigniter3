<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Admin_model', 'admin');
    kicked('admin');
  }

  public function index()
  {
    if ($this->input->post('u-password')) {
      $this->session->set_flashdata('message', $this->admin->changePasswordUser());
    }

    if ($this->input->post('u-foto')) {
      $this->session->set_flashdata('message', $this->admin->uploadPhotoUser());
    }

    $floors = [];
    $sql = $this->db->get_where("floors");
    foreach ($sql->result_array() as $data) {
      $this->db->join("room_types", 'room_type_id');
      $this->db->order_by("room_number", 'ASC');
      $floors[] = [
        'floor_name' => $data['floor_name'],
        'rooms'     => $this->db->get_where("rooms", ['floor_id' => $data['floor_id']])->result_array(),
      ];
    }
    // $this->db->order_by('shift_name', 'ASC');
    $shift = $this->db->get_where("shifts")->result_array();
    $this->load->view('templates/dashboard-admin', [

      'content'    => $this->load->view('admin/dashboard', ['floors' => $floors, 'shift' => $shift], true),
      'add_css'    => $this->load->view('admin/add_css', null, true),
      'add_script' => $this->load->view('admin/add_script', null, true),
      'own_script' => $this->load->view('admin/dashboard_script', null, true),
      'set'        => [
        'title'          => 'Dashboard',
        'content'        => 'Dashboard',
        'sidebar'        => $this->sidebar,
        'active-sidebar' => ['Dashboard', 'bg-dark'],
        'breadcrumb'     => [
          'Dashboard' => 'active',
        ],
      ],
    ]);
  }


  public function get_data()
  {
    $this->admin->getData();
  }
}
