<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Latihan extends CI_Controller
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
    $reservation_id = '10';

    $rooms = [];
    $this->db->join('rooms', 'room_id');
    $this->db->join('room_types', 'room_type_id');
    $this->db->join('room_rates', 'room_rate_id');
    $this->db->join('sessions', 'session_id', 'left');
    $this->db->join('reservations', 'reservation_id');
    $this->db->select('room_reservation_id, room_type_name, room_number, session_name, checkin_schedule, checkout_schedule, room_reservations.room_price AS room_price');
    $room_reservations  = $this->db->get_where("room_reservations", ['reservation_id' => $reservation_id])->result_array();
    foreach ($room_reservations as $result) {

      $i = 1;
      $room_data = [[
        'row_number'     => 1,
        'room_type_name' => $result['room_type_name'],
        'room_number'    => $result['room_number'],
        'sessions'       => $result['session_name'],
        'room_price'     => $result['room_price'],
        'checkin'        => $result['checkin_schedule'],
        'checkout'       => $result['checkout_schedule'],
        'price_change'   => [],
      ]];

      $this->db->order_by('created_at', 'desc');

      $this->db->join('rooms', 'room_id', 'left');
      $this->db->join('room_types', 'room_type_id', 'left');
      $this->db->join('room_rates', 'room_rate_id', 'left');
      $this->db->join('sessions', 'session_id', 'left');
      $this->db->select('room_change_type, room_type_name, room_number, session_name, room_change_date, room_change_histories.room_price AS room_price');
      $room_change_histories = $this->db->get_where("room_change_histories", ['room_reservation_id' => $result['room_reservation_id']]);
      foreach ($room_change_histories->result_array() as $r2) {

        if ($r2['room_change_type'] == 'switch room') {
          $room_data[] = [
            'row_number'     => 1,
            'room_type_name' => $r2['room_type_name'],
            'room_number'    => $r2['room_number'],
            'sessions'       => $r2['session_name'],
            'checkin'        => $r2['room_change_date'],
            'checkout'        => $room_data[$i - 1]['checkin'],
            'room_price'     => $r2['room_price'],
            'price_change'   => [],
          ];
          $room_data[$i - 1]['checkin'] = $r2['room_change_date'];
          $i++;
        } else {
          $room_data[$i - 1]['row_number'] = $room_data[$i - 1]['row_number'] + 1;
          $xprice = $room_data[$i - 1]['price_change'];
          $xprice[] = $r2['room_price'];
          $room_data[$i - 1]['price_change'] = $xprice;
        }



        // if ($r2['room_change_type'] == 'switch room') {
        //   $room_data[] = [
        //     'row_number'       => 1,
        //     'room_type_name'   => $r2['room_type_name'],
        //     'room_number'      => $r2['room_number'],
        //     'sessions'         => $r2['session_name'],
        //     'room_price'       => [$r2['room_price']],
        //   ];
        //   $i++;
        // } else {
        //   $xrow_number = $room_data[$i - 1]['row_number'];
        //   $xprice = $room_data[$i - 1]['room_price'];

        //   $xprice[] = $r2['room_price'];
        //   $room_data[$i - 1]['room_price'] = $xprice;
        //   $room_data[$i - 1]['row_number'] = $xrow_number + 1;
        // }
      }



      $this->db->order_by('created_at', 'asc');
      $rooms[] = [
        'room_reservation_id' => $result['room_reservation_id'],
        'row_number'          => $room_change_histories->num_rows() + 1,
        'room_data'          => $room_data,
        'additional_costs'    => $this->db->get_where("additional_costs", 'room_reservation_id="' . $result['room_reservation_id'] . '" AND deleted_at IS NULL')->result_array(),
      ];

      echo '<pre>';
      print_r($rooms);
      echo '</pre>';
    }
  }
}
