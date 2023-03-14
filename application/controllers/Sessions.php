<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sessions extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Sessions_model', 'sessions');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('session_name', $this->lang->line('field-session_name'), 'required|trim|is_unique[sessions.session_name]');
    $this->form_validation->set_rules('start_session', $this->lang->line('field-start_session'), 'required|trim');
    $this->form_validation->set_rules('end_session', $this->lang->line('field-end_session'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('sessions/main', ['session' => null], true),
        'add_css'    => $this->load->view('sessions/add_css', null, true),
        'add_script' => $this->load->view('sessions/add_script', null, true),
        'own_script' => $this->load->view('sessions/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-sessions'),
          'content'        => $this->lang->line('table-sessions'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-sessions')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-sessions')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->sessions->insertSessions();
    }
  }


  public function update()
  {
    $session_id = $this->uri->segment(3);
    if ($session_id != '') {
      $this->form_validation->set_rules('session_name', $this->lang->line('field-session_name'), "required|trim|is_unique[sessions.session_id!='$session_id' AND session_name=]");
      $this->form_validation->set_rules('start_session', $this->lang->line('field-start_session'), 'required|trim');
      $this->form_validation->set_rules('end_session', $this->lang->line('field-end_session'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $session = $this->db->get_where('sessions', ['session_id' => $session_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('sessions/main', ['session' => $session], true),
          'add_css'    => $this->load->view('sessions/add_css', null, true),
          'add_script' => $this->load->view('sessions/add_script', null, true),
          'own_script' => $this->load->view('sessions/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-sessions'),
            'content'        => $this->lang->line('table-update-sessions'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-sessions')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-sessions')   => base_url('sessions'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->sessions->updateSessions($session_id, 'sessions', 'sessions/update/' . $session_id);
      }
    } else {
      redirect('sessions');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-sessions')) {
      $this->sessions->deleteSessions($this->input->post('session_id'), 'sessions');
    } else {
      $this->sessions->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('sessions');
    }
  }


  public function get_data()
  {
    $this->sessions->getData();
  }
}
