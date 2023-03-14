<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Housekeeping extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('housekeeping');
    $this->load->model('Housekeeping_model', 'housekeeping');
    kicked('housekeeping');
  }

  public function index()
  {
    if ($this->input->post('u-password')) {
      $this->session->set_flashdata('message', $this->housekeeping->changePasswordUser());
    }

    if ($this->input->post('u-foto')) {
      $this->session->set_flashdata('message', $this->housekeeping->uploadPhotoUser());
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
    $this->load->view('templates/dashboard-housekeeping', [

      'content'    => $this->load->view('housekeeping/dashboard', ['floors' => $floors], true),
      'add_css'    => $this->load->view('housekeeping/add_css', null, true),
      'add_script' => $this->load->view('housekeeping/add_script', null, true),
      'own_script' => $this->load->view('housekeeping/dashboard_script', null, true),
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
    $this->housekeeping->getData();
  }
}
