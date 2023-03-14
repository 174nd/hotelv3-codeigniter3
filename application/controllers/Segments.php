<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Segments extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
    $this->sidebar = set_sidebar('admin');
    $this->load->model('Segments_model', 'segment');
    kicked('admin');
  }

  public function index()
  {
    $this->form_validation->set_rules('segment_name', $this->lang->line('field-segment_name'), 'required|trim|is_unique[segments.segment_name]');
    $this->form_validation->set_rules('segment_type', $this->lang->line('field-segment_type'), 'required|trim');
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/dashboard-admin', [
        'content'    => $this->load->view('segments/main', ['segment' => null], true),
        'add_css'    => $this->load->view('segments/add_css', null, true),
        'add_script' => $this->load->view('segments/add_script', null, true),
        'own_script' => $this->load->view('segments/main_script', null, true),
        'set'        => [
          'title'          => $this->lang->line('table-segments'),
          'content'        => $this->lang->line('table-segments'),
          'sidebar'        => $this->sidebar,
          'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-segments')],
          'breadcrumb'     => [
            $this->lang->line('text-dashboard') => base_url('admin'),
            $this->lang->line('table-segments')    => 'active',
          ],
        ],
      ]);
    } else {
      $this->segment->insertSegments();
    }
  }


  public function update()
  {
    $segment_id = $this->uri->segment(3);
    if ($segment_id != '') {
      $this->form_validation->set_rules('segment_name', $this->lang->line('field-segment_name'), "required|trim|is_unique[segments.segment_id!='$segment_id' AND segment_name=]");
      $this->form_validation->set_rules('segment_type', $this->lang->line('field-segment_type'), 'required|trim');
      if ($this->form_validation->run() == FALSE) {
        $segment = $this->db->get_where('segments', ['segment_id' => $segment_id])->row_array();
        $this->load->view('templates/dashboard-admin', [
          'content'    => $this->load->view('segments/main', ['segment' => $segment], true),
          'add_css'    => $this->load->view('segments/add_css', null, true),
          'add_script' => $this->load->view('segments/add_script', null, true),
          'own_script' => $this->load->view('segments/main_script', null, true),
          'set'        => [
            'title'          => $this->lang->line('table-update-segments'),
            'content'        => $this->lang->line('table-update-segments'),
            'sidebar'        => $this->sidebar,
            'active-sidebar' => [$this->lang->line('text-dashboard'), 'bg-dark', $this->lang->line('table-segments')],
            'breadcrumb'     => [
              $this->lang->line('text-dashboard') => base_url('admin'),
              $this->lang->line('table-segments')   => base_url('segments'),
              $this->lang->line('text-update')    => 'active',
            ],
          ],
        ]);
      } else {
        $this->segment->updateSegments($segment_id, 'segments', 'segments/update/' . $segment_id);
      }
    } else {
      redirect('segments');
    }
  }


  public function delete()
  {
    if ($this->input->post('delete-segments')) {
      $this->segment->deleteSegments($this->input->post('segment_id'), 'segments');
    } else {
      $this->segment->set_flashdata('message', $this->lang->line('alert-failed'));
      redirect('segments');
    }
  }


  public function get_data()
  {
    $this->segment->getData();
  }
}
