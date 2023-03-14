<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('password', 'Password', 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/auth', [
        'content' => $this->load->view('auth/login', null, true),
        'set'     => ['title' => $this->lang->line('text-login')],
      ]);
    } else {
      $user = $this->db->get_where('users', [
        'username' => $this->input->post('username'),
        'password' => $this->input->post('password'),
      ]);
      if ($user->num_rows() > 0) {
        $user = $user->row_array();
        $data = [
          'username'      => $user['username'],
          'password'      => $user['password'],
          'user_id'       => $user['user_id'],
          'user_fullname' => $user['user_fullname'],
          'user_photo'    => $user['user_photo'],
          'user_access'   => $user['user_access'],
        ];
        $this->session->set_userdata($data);
        if ($data['user_access'] == 'admin') {
          redirect('admin');
        } else if ($data['user_access'] == 'housekeeping') {
          redirect('housekeeping');
        } else if ($data['user_access'] == 'frontoffice') {
          redirect('frontoffice');
        } else if ($data['user_access'] == 'nightaudit') {
          redirect('nightaudit');
        } else {
          redirect('auth/error');
        }
      } else {
        $this->session->set_flashdata('message', $this->lang->line('alert-login_failed'));
        redirect();
      }
    }
  }


  public function logout()
  {
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('password');
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('user_fullname');
    $this->session->unset_userdata('user_photo');
    $this->session->unset_userdata('user_access');
    redirect();
  }


  public function error()
  {
    $this->load->view('templates/auth', [
      'content' => $this->load->view('auth/error', null, true),
      'set'     => ['title' => $this->lang->line('text-error')],
    ]);
  }
}
