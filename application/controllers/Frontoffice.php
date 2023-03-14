<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontoffice extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model', 'admin');
    kicked('frontoffice');
  }

  public function index()
  {
    if ($this->input->post('u-password')) {
      $this->session->set_flashdata('message', $this->frontoffice->changePasswordUser());
    }

    if ($this->input->post('u-foto')) {
      $this->session->set_flashdata('message', $this->frontoffice->uploadPhotoUser());
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
    $this->load->view('templates/dashboard-frontoffice', [

      'content'    => $this->load->view('frontoffice/dashboard', ['floors' => $floors, 'shift' => $shift], true),
      'add_css'    => $this->load->view('frontoffice/add_css', null, true),
      'add_script' => $this->load->view('frontoffice/add_script', null, true),
      'own_script' => $this->load->view('frontoffice/dashboard_script', null, true),
      'set'        => [
        'title'          => 'Dashboard',
        'content'        => 'Dashboard',
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
