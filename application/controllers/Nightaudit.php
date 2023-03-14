<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nightaudit extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Nightaudit_model', 'nightaudit');
    kicked('nightaudit');
  }

  public function index()
  {
    if ($this->input->post('u-password')) {
      $this->session->set_flashdata('message', $this->nightaudit->changePasswordUser());
    }

    if ($this->input->post('u-foto')) {
      $this->session->set_flashdata('message', $this->nightaudit->uploadPhotoUser());
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
    $this->load->view('templates/dashboard-nightaudit', [

      'content'    => $this->load->view('nightaudit/dashboard', ['floors' => $floors], true),
      'add_css'    => $this->load->view('nightaudit/add_css', null, true),
      'add_script' => $this->load->view('nightaudit/add_script', null, true),
      'own_script' => $this->load->view('nightaudit/dashboard_script', null, true),
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
    $this->nightaudit->getData();
  }
}
