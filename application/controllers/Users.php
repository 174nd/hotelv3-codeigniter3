<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Users_model', 'user');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('user_fullname', $this->lang->line('field-user_fullname'), 'required|trim');
    $this->form_validation->set_rules('username', $this->lang->line('field-username'), 'required|trim|is_unique[users.username]');
    $this->form_validation->set_rules('password', $this->lang->line('field-password'), 'required|trim');
    $this->form_validation->set_rules('user_access', $this->lang->line('field-user_access'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('users/main', ['user' => null], true),
        'add_css'    => $this->load->view('users/add_css', null, true),
        'add_script' => $this->load->view('users/add_script', null, true),
        'own_script' => $this->load->view('users/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-users'),
          'content'        => $this->lang->line('table-users'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-users')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-users')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->user->insertUsers();
    }
  }


  public function update()
  {
    $user_id = $this->uri->segment(3);
    if ($user_id != '') {
      $this->form_validation->set_rules('user_fullname', $this->lang->line('field-user_fullname'), 'required|trim');
      $this->form_validation->set_rules('username', $this->lang->line('field-username'), "required|trim|is_unique[users.user_id!='$user_id' AND username=]");
      $this->form_validation->set_rules('password', $this->lang->line('field-password'), 'required|trim');
      $this->form_validation->set_rules('user_access', $this->lang->line('field-user_access'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $user = $this->db->get_where('users', ['user_id' => $user_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('users/main', ['user' => $user], true),
          'add_css'    => $this->load->view('users/add_css', null, true),
          'add_script' => $this->load->view('users/add_script', null, true),
          'own_script' => $this->load->view('users/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-users'),
            'content'        => $this->lang->line('table-update-users'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-users')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-users')    => base_url('users'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->user->updateUsers($user_id, 'users', 'users/update/' . $user_id);
      }
    } else {
      redirect('users');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-users')) {
      $this->user->deleteUsers($this->input->post('user_id'), 'users');
    } else {
      $this->session->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('users');
    }
  }
}
