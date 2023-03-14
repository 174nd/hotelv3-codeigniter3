<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fo_guests extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Guests_model', 'guest');
    kicked('frontoffice');
  }

  public function index()
  {
    $this->form_validation->set_rules('guest_name', $this->lang->line('field-guest_name'), 'required|trim');
    $this->form_validation->set_rules('identity_type', $this->lang->line('field-identity_type'), 'required|trim');
    $this->form_validation->set_rules('identity_number', $this->lang->line('field-identity_number'), 'required|trim|is_unique[guests.identity_number]');
    $this->form_validation->set_rules('national', $this->lang->line('field-national'), 'required|trim');
    $this->form_validation->set_rules('birth_date', $this->lang->line('field-birth_date'), 'required|trim');
    $this->form_validation->set_rules('guest_address', $this->lang->line('field-guest_address'), 'required|trim');
    $this->form_validation->set_rules('phone_number', $this->lang->line('field-phone_number'), 'required|trim');
    $this->form_validation->set_rules('email', $this->lang->line('field-email'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-frontoffice', [
        'content'    => $this->load->view('fo_guests/main', ['guest' => null], true),
        'add_css'    => $this->load->view('fo_guests/add_css', null, true),
        'add_script' => $this->load->view('fo_guests/add_script', null, true),
        'own_script' => $this->load->view('fo_guests/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-guests'),
          'content'        => $this->lang->line('table-guests'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-guests')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('frontoffice'),
            $this->lang->line('table-guests')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->guest->insertGuests();
    }
  }


  public function update()
  {
    $guest_id = $this->uri->segment(3);
    if ($guest_id != '') {
      $this->form_validation->set_rules('guest_name', $this->lang->line('field-guest_name'), 'required|trim');
      $this->form_validation->set_rules('identity_type', $this->lang->line('field-identity_type'), 'required|trim');
      $this->form_validation->set_rules('identity_number', $this->lang->line('field-identity_number'), "required|trim|is_unique[guests.guest_id!='$guest_id' AND identity_number=]");
      $this->form_validation->set_rules('national', $this->lang->line('field-national'), 'required|trim');
      $this->form_validation->set_rules('birth_date', $this->lang->line('field-birth_date'), 'required|trim');
      $this->form_validation->set_rules('guest_address', $this->lang->line('field-guest_address'), 'required|trim');
      $this->form_validation->set_rules('phone_number', $this->lang->line('field-phone_number'), 'required|trim');
      $this->form_validation->set_rules('email', $this->lang->line('field-email'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $guest = $this->db->get_where('guests', ['guest_id' => $guest_id])->row_array();
        $this->load->view('templates/dashboard-frontoffice', [
          'content'    => $this->load->view('fo_guests/main', ['guest' => $guest], true),
          'add_css'    => $this->load->view('fo_guests/add_css', null, true),
          'add_script' => $this->load->view('fo_guests/add_script', null, true),
          'own_script' => $this->load->view('fo_guests/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-guests'),
            'content'        => $this->lang->line('table-update-guests'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-guests')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('frontoffice'),
              $this->lang->line('table-guests')   => base_url('guests'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->guest->updateGuests($guest_id, 'guests', 'fo_guests/update/' . $guest_id);
      }
    } else {
      redirect('guests');
    }
  }


  public function get_data()
  {
    $this->guest->getData();
  }
}
