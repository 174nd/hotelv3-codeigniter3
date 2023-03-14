<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    kicked();
  }

  public function index()
  {
    redirect('auth/logout');
  }

  public function getTables()
  {
    if (count($this->input->post()) > 0) {
      $get_query = $this->input->post('query');
      $get_search = $this->input->post('search');
      $get_set_tables = $this->input->post('set_tables');
      $set_tables = (isset($get_query) && $get_query ==  true) ? "($get_set_tables)" : $get_set_tables;
      $search = $get_search['value'];
      $limit = $this->input->post('length');
      $start = $this->input->post('start');
      $sql_count = $this->db->query("SELECT * FROM $set_tables AS x")->num_rows();


      $query = "SELECT * FROM $set_tables  AS x WHERE 1";
      $columns = $this->input->post('columns');
      $order = $this->input->post('order');
      for ($i = 0; $i < count($columns); $i++) {
        $query .= ' AND x.' . $columns[$i]['data'] . " LIKE '%" . $columns[$i]['search']['value'] . "%'";
      }

      $order_index = $order[0]['column'];
      $order_field = $columns[$order_index]['data'];
      $order_ascdesc = $order[0]['dir'];
      $order = " ORDER BY " . $order_field . " " . $order_ascdesc;

      $sql_data = $this->db->query($query . $order . " LIMIT " . $limit . " OFFSET " . $start);
      $sql_filter_count = $this->db->query($query)->num_rows();

      $data = $sql_data->result_array();

      $callback = array(
        'draw' => $this->input->post('draw'),
        'recordsTotal' => $sql_count,
        'recordsFiltered' => $sql_filter_count,
        'data' => $data
      );
      header('Content-Type: application/json');
      echo json_encode($callback);
    } else {
      redirect('auth/logout');
    }
  }
}
