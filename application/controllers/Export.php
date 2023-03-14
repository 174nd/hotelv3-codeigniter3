<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Export extends CI_Controller
{
  protected $sidebar;
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    show_404();
  }

  public function reservation_report()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/templates-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);

      $spreadsheet->getSheetByName('All Reservation')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('All Reservation')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Void Reservation')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Void Reservation')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('In House Guest')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('In House Guest')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Expected Arrival Guest')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Expected Arrival Guest')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Expected Departure Guest')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Expected Departure Guest')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Daily Room Counter Sheet')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Daily Room Counter Sheet')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A3', $fordate);
      // $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A2', $printed);
      // $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A3', $fordate);

      $this->db->query("set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
      ///// All Reservation
      $query = "SELECT reservations.reservation_number, (SELECT guests.guest_name FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS guest_name, reservations.adult_guest, reservations.child_guest, (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`, COUNT(room_number) AS total_rooms, GROUP_CONCAT(room_number SEPARATOR '\n') AS rooms, GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\n') AS room_type, reservations.checkin_schedule, reservations.checkout_schedule, room_plans.room_plan_name, COALESCE(sessions.session_name,'-') AS session_name, SUM(room_reservations.room_price) AS price, (SELECT user_fullname FROM reservation_histories AS a JOIN users ON users.user_id=a.created_by WHERE reservation_status='Reservation' AND a.reservation_id=reservations.reservation_id LIMIT 1) AS admin FROM reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) LEFT JOIN sessions USING(session_id) JOIN rooms USING(room_id) JOIN room_types ON rooms.room_type_id=room_types.room_type_id WHERE reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Reservation') GROUP BY reservation_id";
      $no = 1;
      $numrow = 5;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'All Reservation';
      foreach ($this->db->query($query)->result_array() as $result) {
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['session_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }


      ///// In House Guest
      $query = "SELECT reservations.reservation_number, (SELECT guests.guest_name from guests where guests.guest_id=reservations.guest_id limit 1) AS guest_name, reservations.adult_guest, reservations.child_guest, (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`,COUNT(room_number) AS total_rooms, group_concat(room_number SEPARATOR '\\n') AS rooms, GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\\n') AS room_type, reservations.checkin_schedule, reservations.checkout_schedule, room_plans.room_plan_name, sum(room_reservations.room_price) AS price, (select user_fullname from reservation_histories AS a join users ON users.user_id=a.created_by WHERE reservation_status='Stay' AND a.reservation_id=reservations.reservation_id LIMIT 1) as admin from reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) JOIN rooms USING(room_id) JOIN room_types on rooms.room_type_id=room_types.room_type_id where reservation_id IN ( SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay') GROUP BY reservation_id";
      $no = 1;
      $numrow = 5;
      $child = 0;
      $adult = 0;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'In House Guest';
      foreach ($this->db->query($query)->result_array() as $result) {
        $guest = $result['adult_guest'] . ' Adult' . ($result['child_guest'] != 0 ? ' / ' . $result['child_guest'] . ' Child' : '');
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $guest);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $adult += $result['adult_guest'];
        $child += $result['child_guest'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $adult . ' Adult / ' . $child . ' Child');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }



      ///// Void Reservation
      $query = "SELECT reservations.reservation_number,
        (SELECT guests.guest_name from guests where guests.guest_id=reservations.guest_id limit 1) AS guest_name,
        reservations.adult_guest,
        reservations.child_guest,
        (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`,
        COUNT(room_number) AS total_rooms,
        group_concat(room_number SEPARATOR '\n') AS rooms,
        GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\n') AS room_type,
        reservations.checkin_schedule,
        reservations.checkout_schedule,
        room_plans.room_plan_name,
        segments.segment_name,
        sum(room_reservations.room_price) AS price,
        (select DATE(created_at) from reservation_histories AS a WHERE reservation_status='Cancelled' AND a.reservation_id=reservations.reservation_id LIMIT 1) as cancel_date,
        (select user_fullname from reservation_histories AS a join users ON users.user_id=a.created_by WHERE reservation_status='Cancelled' AND a.reservation_id=reservations.reservation_id LIMIT 1) as admin
         from reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) JOIN segments USING(segment_id) JOIN rooms USING(room_id) JOIN room_types on rooms.room_type_id=room_types.room_type_id where reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id,
        (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status` IN ('Cancelled')) GROUP by reservation_id";
      $no = 1;
      $numrow = 5;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'Void Reservation';
      foreach ($this->db->query($query)->result_array() as $result) {
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $result['segment_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['cancel_date']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('N' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }








      ///// Expected Arrival Guest
      $query = "SELECT reservations.reservation_number, (SELECT guests.guest_name FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS guest_name, reservations.adult_guest, reservations.child_guest, (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`, COUNT(room_number) AS total_rooms, GROUP_CONCAT(room_number SEPARATOR '\n') AS rooms, GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\n') AS room_type, reservations.checkin_schedule, reservations.checkout_schedule, room_plans.room_plan_name, COALESCE(sessions.session_name,'-') AS session_name, SUM(room_reservations.room_price) AS price, (SELECT user_fullname FROM reservation_histories AS a JOIN users ON users.user_id=a.created_by WHERE reservation_status='Reservation' AND a.reservation_id=reservations.reservation_id LIMIT 1) AS admin FROM reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) LEFT JOIN sessions USING(session_id) JOIN rooms USING(room_id) JOIN room_types ON rooms.room_type_id=room_types.room_type_id WHERE reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Reservation') AND checkin_schedule='$date' GROUP BY reservation_id";
      $no = 1;
      $numrow = 5;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'Expected Arrival Guest';
      foreach ($this->db->query($query)->result_array() as $result) {
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['session_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }



      ///// Expected Departure Guest
      $query = "SELECT reservations.reservation_number, (SELECT guests.guest_name from guests where guests.guest_id=reservations.guest_id limit 1) AS guest_name, reservations.adult_guest, reservations.child_guest, (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`,COUNT(room_number) AS total_rooms, group_concat(room_number SEPARATOR '\\n') AS rooms, GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\\n') AS room_type, reservations.checkin_schedule, reservations.checkout_schedule, room_plans.room_plan_name, sum(room_reservations.room_price) AS price, (select user_fullname from reservation_histories AS a join users ON users.user_id=a.created_by WHERE reservation_status='Stay' AND a.reservation_id=reservations.reservation_id LIMIT 1) as admin from reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) JOIN rooms USING(room_id) JOIN room_types on rooms.room_type_id=room_types.room_type_id where reservation_id IN ( SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay') AND checkout_schedule='$date' GROUP BY reservation_id";
      $no = 1;
      $numrow = 5;
      $child = 0;
      $adult = 0;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'Expected Departure Guest';
      foreach ($this->db->query($query)->result_array() as $result) {
        $guest = $result['adult_guest'] . ' Adult' . ($result['child_guest'] != 0 ? ' / ' . $result['child_guest'] . ' Child' : '');
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $guest);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $adult += $result['adult_guest'];
        $child += $result['child_guest'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $adult . ' Adult / ' . $child . ' Child');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }



      ///// Daily Room Counter Sheet
      $query = "SELECT reservations.reservation_number,
        (SELECT guests.guest_name from guests where guests.guest_id=reservations.guest_id limit 1) AS guest_name,
        reservations.adult_guest,
        reservations.child_guest,
        (SELECT guests.national FROM guests WHERE guests.guest_id=reservations.guest_id LIMIT 1) AS `national`,
        COUNT(room_number) AS total_rooms,
        group_concat(room_number SEPARATOR '\n') AS rooms,
        GROUP_CONCAT(DISTINCT room_type_name SEPARATOR '\n') AS room_type,
        reservations.checkin_schedule,
        reservations.checkout_schedule,
        room_plans.room_plan_name,
        segments.segment_name,
        sum(room_reservations.room_price) AS price,
        (select user_fullname from reservation_histories AS a join users ON users.user_id=a.created_by WHERE reservation_status='Reservation' AND a.reservation_id=reservations.reservation_id LIMIT 1) as admin from reservations JOIN room_reservations USING(reservation_id) JOIN room_rates USING(room_rate_id) JOIN room_plans USING(room_plan_id) JOIN segments USING(segment_id) JOIN rooms USING(room_id) JOIN room_types on rooms.room_type_id=room_types.room_type_id where reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id,
        (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status` IN ('Finished','Stay')) GROUP by reservation_id";
      $no = 1;
      $numrow = 5;
      $night = 0;
      $rooms = 0;
      $First_row = $numrow;
      $sheets = 'Daily Room Counter Sheet';
      foreach ($this->db->query($query)->result_array() as $result) {
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['reservation_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['national']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['rooms']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['room_type']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, $result['checkout_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $in_house . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('J' . $numrow, $result['segment_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('K' . $numrow, $result['room_plan_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, $result['price']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('M' . $numrow, $result['admin']);


        $no++;
        $numrow++;
        $night += $in_house;
        $rooms += $result['total_rooms'];
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $rooms . ' Rooms');
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $night . ' Night');
        $spreadsheet->getSheetByName($sheets)->setCellValue('L' . $numrow, '=SUM(L' . $First_row . ':L' . ($numrow - 1) . ')');
      }



      ///// FO Cashier Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE DATE(created_at)='$date' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Cashier Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . ($numrow - 1) . ')');
      }



      ///// Daily Operation Report
      // $query = "SELECT (SELECT sum(total_payment) from payment_histories where date(payment_date)=DATE('2021-11-06')) AS this_day, (SELECT SUM(total_payment) FROM payment_histories WHERE MONTH(payment_date)=MONTH('2021-11-06')) AS this_month, (SELECT SUM(total_payment) FROM payment_histories WHERE YEAR(payment_date)=YEAR('2021-11-06')) AS this_year";
      // $sheets = 'Daily Operation Report';
      // $result = $this->db->query($query)->row_array();
      // $spreadsheet->getSheetByName($sheets)->setCellValue('C7', $result['this_day']);
      // $spreadsheet->getSheetByName($sheets)->setCellValue('D7', $result['this_month']);
      // $spreadsheet->getSheetByName($sheets)->setCellValue('E7', $result['this_year']);




      $spreadsheet->setActiveSheetIndexByName('All Reservation');
      $spreadsheet->getSheetByName('All Reservation')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('In House Guest')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Expected Arrival Guest')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Expected Departure Guest')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Daily Room Counter Sheet')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      // $spreadsheet->getSheetByName('Daily Operation Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle('Daily Report')->setSubject("Daily Report")->setDescription("Import Daily Report")->setKeywords("Daily Report");
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Daily Report  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function receipt()
  {
    $payment_number = $this->uri->segment(3);
    if ($payment_number != '') {
      $this->db->join('payments', 'payment_id');
      $payment = $this->db->get_where('payment_histories', ['payment_number' => $payment_number]);
      if ($payment->num_rows() > 0) {
        $payment = $payment->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A5', 'landscape');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['reservation_id' => $payment['reservation_id']])->row_array();


        $this->pdf->filename = "receipt - $payment_number.pdf";
        $this->pdf->load_view('export/receipt', [
          'reservations' => $reservations,
          'payment'      => $payment,
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  private function getReservationReport($reservation_id, $checkin_schedule, $checkout_schedule, $receipt_type, $not_rooms = true)
  {
    $reservation_data = [];
    $checkin_schedule = date('Y-m-d', strtotime($checkin_schedule));
    $checkout_schedule = date('Y-m-d', strtotime($checkout_schedule));
    $payment_number = 0;
    for ($i = 0; $checkin_schedule <= $checkout_schedule; $i++) {
      $result = [];
      $this->db->order_by('created_at', 'ASC');
      $this->db->join('room_reservations', 'room_reservation_id');
      $this->db->join('rooms', 'room_id');
      $payment = $this->db->get_where("additional_costs", ['reservation_id' => $reservation_id, 'DATE(created_at)' => $checkin_schedule])->result_array();
      foreach ($payment as $a) {
        $result[] = [
          'type'                        => 'additional_cost',
          'additional_cost_type'        => $a['additional_cost_type'],
          'additional_cost_description' => $a['additional_cost_description'] . ' At Room No. ' . $a['room_number'],
          'additional_cost_price'       => $a['additional_cost_price'],
        ];
      }
      if ($not_rooms === true) {
        $this->db->order_by('created_at', 'ASC');
        $get = $this->db->get_where("payment_histories", ['reservation_id' => $reservation_id, 'DATE(created_at)' => $checkin_schedule]);
        $last = $this->db->get_where("payment_histories", ['reservation_id' => $reservation_id])->num_rows() - 1;
        $payment = $get->result_array();
        $i = 0;
        foreach ($payment as $a) {
          $show = $receipt_type == 'bill' && $payment_number == $last ? false : ($receipt_type == 'invoice' && ($payment_number == 0 || $payment_number == $last || $payment_number == $last - 1) ? false : true);
          $result[] = [
            'type'               => 'payment',
            'payment_desciption' => $a['payment_desciption'],
            'total_payment'      => $a['total_payment'],
            // 'show'               => true,
            'show'               => $show,
          ];
          $i++;
          $payment_number++;
        }
      }

      if ($checkin_schedule != $checkout_schedule) {
        $this->db->order_by('room_number', 'ASC');
        $this->db->join('rooms', 'room_id');
        $payment = $this->db->get_where("room_reservations", ['reservation_id' => $reservation_id])->result_array();
        foreach ($payment as $a) {
          if ($not_rooms === true || ($not_rooms == $a['room_reservation_id'])) {
            $result[] = [
              'type'        => 'rooms',
              'room_number' => 'Room reserved type : ' . $a['room_number'],
              'room_price'  => $a['room_price'],
            ];
          }
        }
      }

      $reservation_data[$checkin_schedule] = $result;
      $checkin_schedule = date('Y-m-d', strtotime($checkin_schedule . ' +1 day'));
    }
    return $reservation_data;
  }

  public function reservation()
  {
    $reservation_number = $this->uri->segment(3);
    if ($reservation_number != '') {
      $reservation = $this->db->get_where('reservations', ['reservation_number' => $reservation_number]);
      if ($reservation->num_rows() > 0) {
        $reservations = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['reservation_number' => $reservation_number])->row_array();

        $this->pdf->filename = "Folio - $reservation_number.pdf";
        $this->pdf->load_view('export/reservation', [
          'reservations' => $reservations,
          'room_data'    => $this->getReservationReport($reservations['reservation_id'], $reservations['checkin_schedule'], $reservations['checkout_schedule'], $reservations['receipt_type']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function room_reservation()
  {
    $room_reservation_id = $this->uri->segment(3);
    if ($room_reservation_id != '') {
      $reservations = $this->db->get_where('room_reservations', ['room_reservation_id' => $room_reservation_id]);
      if ($reservations->num_rows() > 0) {
        $reservations = $reservations->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['reservation_id' => $reservations['reservation_id']])->row_array();

        $this->pdf->filename = "ROOM BILL - $reservations[reservation_number].pdf";
        $this->pdf->load_view('export/room-reservation.php', [
          'reservations' => $reservations,
          'room_data'    => $this->getReservationReport($reservations['reservation_id'], $reservations['checkin_schedule'], $reservations['checkout_schedule'], $reservations['receipt_type'], $room_reservation_id),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function invoice()
  {
    $bill_number = $this->uri->segment(3);
    if ($bill_number != '') {
      $reservation = $this->db->get_where('reservations', ['bill_number' => $bill_number, 'receipt_type' => 'invoice']);
      if ($reservation->num_rows() > 0) {
        $reservation = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['bill_number' => $bill_number])->row_array();

        $this->pdf->filename = "Invoice - $bill_number.pdf";
        $this->pdf->load_view('export/invoice', [
          'reservations' => $reservations,
          'room_data'    => $this->getReservationReport($reservations['reservation_id'], $reservations['checkin_schedule'], $reservations['checkout_schedule'], $reservations['receipt_type']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function bill()
  {
    $bill_number = $this->uri->segment(3);
    if ($bill_number != '') {
      $reservation = $this->db->get_where('reservations', ['bill_number' => $bill_number, 'receipt_type' => 'bill']);
      if ($reservation->num_rows() > 0) {
        $reservation = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['bill_number' => $bill_number])->row_array();
        $this->pdf->filename = "Bill - $bill_number.pdf";
        $this->pdf->load_view('export/bill', [
          'reservations' => $reservations,
          'room_data'    => $this->getReservationReport($reservations['reservation_id'], $reservations['checkin_schedule'], $reservations['checkout_schedule'], $reservations['receipt_type']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function housekeeping_day_report()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/housekeeping-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);

      $spreadsheet->getSheetByName('Housekeeping Day Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Housekeeping Day Report')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Housekeeping Due Out Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Housekeeping Due Out Report')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Housekeeping Occupied Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Housekeeping Occupied Report')->setCellValue('A3', $fordate);


      ///// Housekeeping Day Report
      $query = "SELECT guests.guest_name, rooms.room_number, room_types.room_type_name, reservations.checkin_schedule, reservations.checkout_schedule FROM room_reservations JOIN rooms USING(room_id) JOIN room_types USING(room_type_id) JOIN reservations USING(reservation_id) JOIN guests USING(guest_id) WHERE reservation_status!='Cancelled' AND DATE(checkin_schedule)=DATE('$date')";
      $no = 1;
      $rooms = 0;
      $numrow = 5;
      $sheets = 'Housekeeping Day Report';
      foreach ($this->db->query($query)->result_array() as $result) {
        $in_house = round((strtotime($result['checkout_schedule']) - strtotime($result['checkin_schedule'])) / (60 * 60 * 24));
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_type_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['room_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $in_house . ' Night');

        $no++;
        $rooms++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $rooms . ' Rooms');
      }


      ///// Housekeeping Due Out Report
      $query = "SELECT guest_name, room_type_name, room_number, checkin_schedule, checkout_schedule FROM room_reservations JOIN reservations USING(reservation_id) JOIN guests USING(guest_id) JOIN rooms USING(room_id) JOIN room_types USING(room_type_id) WHERE checkout_schedule='$date' GROUP BY room_reservation_id";
      $no = 1;
      $rooms = 0;
      $numrow = 5;
      $sheets = 'Housekeeping Due Out Report';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_type_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['room_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['checkout_schedule']);

        $no++;
        $rooms++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $rooms . ' Rooms');
      }


      ///// Housekeeping Occupied Report
      $query = "SELECT guest_name, room_type_name, room_number, checkin_schedule, checkout_schedule FROM room_reservations JOIN reservations USING(reservation_id) JOIN guests USING(guest_id) JOIN rooms USING(room_id) JOIN room_types USING(room_type_id) WHERE '$date' BETWEEN checkin_schedule AND checkout_schedule - INTERVAL 1 DAY GROUP BY room_reservation_id";
      $no = 1;
      $rooms = 0;
      $numrow = 5;
      $sheets = 'Housekeeping Occupied Report';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_type_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['room_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['checkin_schedule']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $result['checkout_schedule']);

        $no++;
        $rooms++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $rooms . ' Rooms');
      }


      $spreadsheet->setActiveSheetIndexByName('Housekeeping Day Report');
      $spreadsheet->getSheetByName('Housekeeping Day Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Housekeeping Due Out Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Housekeeping Occupied Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle('Housekeeping Report')->setSubject("Housekeeping Report")->setDescription("Import Housekeeping Report")->setKeywords("Housekeeping Report");
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Housekeeping Report  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function housekeeping_room_report()
  {
    $spreadsheet = new Spreadsheet();
    $inputFileType = 'Xlsx';
    $inputFileName = FCPATH . 'files/housekeeping-room-report.xlsx';
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');

    $spreadsheet->getSheetByName('Housekeeping Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Vacant Ready Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Vacant Clean Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Vacant Dirty Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Occupied Clean Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Occupied Dirty Room Report')->setCellValue('A2', $printed);
    $spreadsheet->getSheetByName('Out of Service Room Report')->setCellValue('A2', $printed);



    ///// Housekeeping Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('VC','VD','OD','OO') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Housekeeping Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      switch ($result['room_status']) {
        case 'VC':
          $x = $this->lang->line('text-VC');
          break;
        case 'VD':
          $x = $this->lang->line('text-VD');
          break;
        case 'OD':
          $x = $this->lang->line('text-OD');
          break;
        default:
          $x = $this->lang->line('text-OO');
      }
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $x);


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Vacant Ready Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('VR') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Vacant Ready Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-VR'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Vacant Clean Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('VC') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Vacant Clean Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-VC'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Vacant Dirty Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('VD') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Vacant Dirty Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-VD'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Occupied Clean Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('OC') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Occupied Clean Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-OC'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Occupied Dirty Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('OD') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Occupied Dirty Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-OD'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    ///// Out of Service Room Report
    $query = "SELECT * FROM rooms JOIN room_types USING(room_type_id) WHERE room_status IN('OO') ORDER BY room_number";
    $no = 1;
    $numrow = 4;
    $rooms = 0;
    $sheets = 'Out of Service Room Report';
    foreach ($this->db->query($query)->result_array() as $result) {
      $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
      $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['room_type_name']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['room_number']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $this->lang->line('text-OO'));


      $no++;
      $rooms++;
      $numrow++;
      $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
    }
    if ($no != 1) {
      $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $rooms . ' Rooms');
    }


    $spreadsheet->setActiveSheetIndexByName('Housekeeping Room Report');
    $spreadsheet->getSheetByName('Housekeeping Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Vacant Ready Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Vacant Clean Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Vacant Dirty Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Occupied Clean Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Occupied Dirty Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getSheetByName('Out of Service Room Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
    $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle('Housekeeping Room Report')->setSubject("Housekeeping Room Report")->setDescription("Import Housekeeping Room Report")->setKeywords("Housekeeping Room Report");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Housekeeping Room Report  - ' . dateFormat(date('Y-m-d')) . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
  }

  public function nightaudit_report1()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/nightaudit-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);
      $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Leadger Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Leadger Report')->setCellValue('A3', $fordate);


      /// Daily Operation Report
      $query = "SELECT 
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND DATE(created_at)=DATE('$date')),0) AS this_day,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND DATE(created_at)=DATE('$date')),0) AS ngthis_day,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND MONTH(created_at)=MONTH('$date')),0) AS this_month,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND MONTH(created_at)=MONTH('$date')),0) AS ngthis_month,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND YEAR(created_at)=YEAR('$date')),0) AS this_year,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND YEAR(created_at)=YEAR('$date')),0) AS ngthis_year,
      
      (SELECT COUNT(room_reservations.reservation_id) FROM room_reservations WHERE
      room_reservations.reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id,
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')) AS room_sold,
      (SELECT COUNT(room_id) FROM rooms) AS total_room";
      $sheets = 'Daily Operation Report';
      $result = $this->db->query($query)->row_array();
      $spreadsheet->getSheetByName($sheets)->setCellValue('C7', $result['this_day']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D7', $result['this_month']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('E7', $result['this_year']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C11', $result['ngthis_day']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D11', $result['ngthis_month']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('E11', $result['ngthis_year']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C13', ($result['total_room'] - $result['room_sold']) . ' Rooms');
      $spreadsheet->getSheetByName($sheets)->setCellValue('C14', $result['room_sold'] . ' Rooms');


      /// Leadger Report
      $query = "SELECT
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      WHERE payment_type='deposit' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) as dl_1,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      WHERE payment_type='deposit' AND DATE(created_at)='$date' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS dl_2,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      WHERE payment_type='refund' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status`='Finished')),0) AS dl_3,
      
      
      
      COALESCE((SELECT SUM((DATE('$date' - INTERVAL 1 DAY) - checkin_schedule + 1)  * 
      (SELECT SUM(room_price) FROM room_reservations WHERE room_reservations.reservation_id=reservations.reservation_id)) FROM reservations WHERE
      reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' - INTERVAL 1 DAY GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS gl_1,
      COALESCE((SELECT SUM((DATE('$date') - checkin_schedule + 1)  * 
      (SELECT SUM(room_price) FROM room_reservations WHERE room_reservations.reservation_id=reservations.reservation_id)) FROM reservations WHERE
      reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS gl_2,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories WHERE payment_type='onstay' AND DATE(created_at)='$date'),0) as gl_3,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) WHERE
      reservation_id IN 
      (SELECT reservation_id FROM 
      (SELECT reservation_id, 
        (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status`='Finished')
      ),0) AS gl_4,
      
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND MONTH(created_at)=MONTH('$date')),0) cl_1,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND DATE(created_at)=DATE('$date')),0) cl_2";
      $sheets = 'Leadger Report';
      $result = $this->db->query($query)->row_array();
      $spreadsheet->getSheetByName($sheets)->setCellValue('C5', $result['dl_1']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C6', $result['dl_2']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C7', $result['dl_3']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C10', $result['gl_1']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C11', $result['gl_2']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C12', $result['gl_3']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C13', $result['gl_4']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C16', $result['cl_1']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C17', $result['cl_2']);


      $filename = 'Night Audit Report';
      $spreadsheet->setActiveSheetIndexByName('Daily Operation Report');
      $spreadsheet->getSheetByName('Daily Operation Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Leadger Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($filename)->setSubject($filename)->setDescription("Import " . $filename)->setKeywords($filename);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function nightaudit_report()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/nightaudit-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);
      $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Daily Operation Report')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('Leadger Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Leadger Report')->setCellValue('A3', $fordate);


      /// Daily Operation Report
      $query = "SELECT 
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND DATE(created_at)=DATE('$date')),0) AS this_day,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND DATE(created_at)=DATE('$date')),0) AS ngthis_day,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND MONTH(created_at)=MONTH('$date') AND DATE(created_at)<=DATE('$date')),0) AS this_month,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND MONTH(created_at)=MONTH('$date') AND DATE(created_at)<=DATE('$date')),0) AS ngthis_month,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type!='non-guaranted' AND YEAR(created_at)=YEAR('$date') AND DATE(created_at)<=DATE('$date')),0) AS this_year,
      COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) JOIN segments USING(segment_id) 
      WHERE segment_type='non-guaranted' AND YEAR(created_at)=YEAR('$date') AND DATE(created_at)<=DATE('$date')),0) AS ngthis_year,
      
      (SELECT COUNT(room_reservations.reservation_id) FROM room_reservations WHERE
      room_reservations.reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id,
      (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')) AS room_sold,
      (SELECT COUNT(room_id) FROM rooms) AS total_room";
      $sheets = 'Daily Operation Report';
      $result = $this->db->query($query)->row_array();
      $spreadsheet->getSheetByName($sheets)->setCellValue('C7', $result['this_day']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D7', $result['this_month']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('E7', $result['this_year']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C11', $result['ngthis_day']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('D11', $result['ngthis_month']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('E11', $result['ngthis_year']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C13', ($result['total_room'] - $result['room_sold']) . ' Rooms');
      $spreadsheet->getSheetByName($sheets)->setCellValue('C14', $result['room_sold'] . ' Rooms');


      /// Leadger Report
      // $query = "SELECT
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      // WHERE payment_type='deposit' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      // (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) as dl_1,
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      // WHERE payment_type='deposit' AND DATE(created_at)='$date' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      // (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS dl_2,
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      // WHERE payment_type='refund' AND reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      // (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status`='Finished')),0) AS dl_3,



      // COALESCE((SELECT SUM((DATE('$date' - INTERVAL 1 DAY) - checkin_schedule + 1)  * 
      // (SELECT SUM(room_price) FROM room_reservations WHERE room_reservations.reservation_id=reservations.reservation_id)) FROM reservations WHERE
      // reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      // (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' - INTERVAL 1 DAY GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS gl_1,
      // COALESCE((SELECT SUM((DATE('$date') - checkin_schedule + 1)  * 
      // (SELECT SUM(room_price) FROM room_reservations WHERE room_reservations.reservation_id=reservations.reservation_id)) FROM reservations WHERE
      // reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, 
      // (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')),0) AS gl_2,
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories WHERE payment_type='onstay' AND DATE(created_at)='$date'),0) as gl_3,
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) WHERE
      // reservation_id IN 
      // (SELECT reservation_id FROM 
      // (SELECT reservation_id, 
      //   (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status` 
      // FROM reservation_histories AS a WHERE DATE(a.created_at)='$date' GROUP BY reservation_id) AS a WHERE `status`='Finished')
      // ),0) AS gl_4,

      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      // WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND MONTH(created_at)=MONTH('$date')),0) cl_1,
      // COALESCE((SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id) 
      // WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND DATE(created_at)=DATE('$date')),0) cl_2";

      $query = "SELECT
      COALESCE((
      SELECT SUM(deposit) FROM reservations WHERE
      checkin_schedule='$date' AND reservation_status!='Cancelled'
      ),0) AS deposit,
      COALESCE((
      SELECT SUM(room_price) FROM reservations JOIN room_reservations USING(reservation_id) WHERE
      reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status`
      FROM reservation_histories AS a WHERE DATE(a.created_at)<='$date' GROUP BY reservation_id) AS a WHERE `status`='Stay')
      ),0) AS gl_dr,
      COALESCE((
      SELECT SUM((room_price * DATEDIFF('$date', checkin_schedule))) - (SUM(DISTINCT(deposit + reservation_id)) - SUM(DISTINCT(reservation_id))) FROM reservations JOIN room_reservations USING(reservation_id) WHERE
      reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status`
      FROM reservation_histories AS a WHERE DATE(a.created_at)<=DATE('$date') - INTERVAL 1 DAY GROUP BY reservation_id) AS a WHERE `status`='Stay')
      ),0) AS previous_balance,
      -- COALESCE((
      -- SELECT (SUM(DISTINCT(deposit + reservation_id)) - SUM(DISTINCT(reservation_id))) - SUM((room_price * DATEDIFF('$date', checkin_schedule))) FROM reservations JOIN room_reservations USING(reservation_id) WHERE
      -- reservation_id IN (SELECT reservation_id FROM (SELECT reservation_id, (SELECT reservation_status FROM reservation_histories AS `x` WHERE x.reservation_history_id=MAX(a.reservation_history_id) LIMIT 1) AS `status`
      -- FROM reservation_histories AS a WHERE DATE(a.created_at)=DATE('$date') GROUP BY reservation_id) AS a WHERE `status`='Finished')
      -- ),0) AS guest_co,
      COALESCE((
      SELECT SUM(IF(receipt_type='invoice' AND payment_type='remaining',0,total_payment)) * - 1 FROM payment_histories JOIN reservations USING(reservation_id)
      WHERE payment_type IN ('remaining','refund') AND DAY(created_at)=DAY('$date')
      ),0) guest_co,
      COALESCE((
      SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id)
      WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND DAY(created_at)=DAY('$date')
      ),0) invoice_day,
      COALESCE((
      SELECT SUM(total_payment) FROM payment_histories JOIN reservations USING(reservation_id)
      WHERE receipt_type='invoice' AND payment_type IN ('remaining') AND DAY(created_at)<=DAY('$date') AND MONTH(created_at)=MONTH('$date')
      ),0) invoice_month";
      $sheets = 'Leadger Report';
      $result = $this->db->query($query)->row_array();
      $spreadsheet->getSheetByName($sheets)->setCellValue('C5', 0);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C6', $result['deposit']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C7', ($result['deposit'] * -1));
      $spreadsheet->getSheetByName($sheets)->setCellValue('C10', $result['previous_balance']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C11', $result['gl_dr']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C12', ($result['deposit'] * -1));
      $spreadsheet->getSheetByName($sheets)->setCellValue('C13', $result['guest_co']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C16', $result['invoice_day']);
      $spreadsheet->getSheetByName($sheets)->setCellValue('C17', $result['invoice_month']);

      ob_get_clean();
      $filename = 'Night Audit Report';
      $spreadsheet->setActiveSheetIndexByName('Daily Operation Report');
      $spreadsheet->getSheetByName('Daily Operation Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('Leadger Report')->setSelectedCell('A1')->getProtection()->setSheet(true);

      // $spreadsheet->getActiveSheet('Daily Operation Report')->getProtection()->setSheet(true);
      // $spreadsheet->getActiveSheet('Leadger Report')->getProtection()->setSheet(true);
      // $spreadsheet->getSecurity()->setLockWindows(true);
      // $spreadsheet->getSecurity()->setLockStructure(true);
      // $spreadsheet->getSecurity()->setWorkbookPassword("PhpSpreadsheet");


      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($filename)->setSubject($filename)->setDescription("Import " . $filename)->setKeywords($filename);

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function daily_shift_report()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/shift-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);
      $shift = $this->db->get_where('shifts', ['shift_id' => $this->input->post('shift_id')])->row_array();
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A1', 'FO Cashier Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A1', 'FO Deposit Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A1', 'FO Refund Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A1', 'FO Remaining Payment Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A3', $fordate);

      $date1 = date('Y-m-d H:i:s', strtotime($date . ' ' . $shift['start_shift']));
      $date2 = date('Y-m-d H:i:s', strtotime($date . ' ' . $shift['end_shift'] . ($shift['start_shift'] > $shift['end_shift'] ? ' +1 day' : '')));

      ///// FO Cashier Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Cashier Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Deposit Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND payment_type='deposit' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Deposit Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Refund Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND payment_type='refund' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Refund Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Remaining Payment Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND payment_type='remaining' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Remaining Payment Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      $filename = 'Daily Shift Report';
      $spreadsheet->setActiveSheetIndexByName('FO Cashier Summary');
      $spreadsheet->getSheetByName('FO Cashier Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Refund Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($filename)->setSubject($filename)->setDescription("Import " . $filename)->setKeywords($filename);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function daily_shift_frontoffice()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/shift-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);
      $shift = $this->db->get_where('shifts', ['shift_id' => $this->input->post('shift_id')])->row_array();
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A1', 'FO Cashier Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Cashier Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A1', 'FO Deposit Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A1', 'FO Refund Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A3', $fordate);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A1', 'FO Remaining Payment Summary - Shift ' . $shift['shift_name']);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setCellValue('A3', $fordate);

      $date1 = date('Y-m-d H:i:s', strtotime($date . ' ' . $shift['start_shift']));
      $date2 = date('Y-m-d H:i:s', strtotime($date . ' ' . $shift['end_shift'] . ($shift['start_shift'] > $shift['end_shift'] ? ' +1 day' : '')));

      $user_id = $this->session->userdata('user_id');
      $spreadsheet->getSheetByName('FO Refund Summary')->setCellValue('A5', $user_id);

      ///// FO Cashier Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND created_by='$user_id' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Cashier Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Deposit Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND created_by='$user_id' AND payment_type='deposit' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Deposit Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Refund Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND created_by='$user_id' AND payment_type='refund' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Refund Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      ///// FO Remaining Payment Summary
      $query = "SELECT payment_histories.payment_id, payment_histories.payment_number, payment_histories.payment_desciption, (SELECT guests.guest_name from guests join reservations using(guest_id) where reservations.reservation_id=payment_histories.reservation_id limit 1) AS guest_name, (SELECT reservations.reservation_number from reservations where reservations.reservation_id=payment_histories.reservation_id limit 1) AS reservation_number, group_concat(room_number SEPARATOR ', ') AS room_number, payment_histories.total_payment, (SELECT user_fullname from users WHERE users.user_id=payment_histories.created_by LIMIT 1) as cashier FROM payment_histories JOIN room_reservations USING(reservation_id) JOIN rooms USING(room_id) WHERE created_at BETWEEN '$date1' AND '$date2' AND created_by='$user_id' AND payment_type='remaining' GROUP BY payment_history_id";
      $no = 1;
      $numrow = 5;
      $First_row = $numrow;
      $sheets = 'FO Remaining Payment Summary';
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['payment_number']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['payment_desciption']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['guest_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        $x = $result['payment_id'] == '1' ? 'F' : ($result['payment_id'] == '2' ? 'G' : 'H');
        $spreadsheet->getSheetByName($sheets)->setCellValue($x . $numrow, $result['total_payment']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('I' . $numrow, $result['cashier']);


        $no++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, '=SUM(F' . $First_row . ':F' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('G' . $numrow, '=SUM(G' . $First_row . ':G' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('H' . $numrow, '=SUM(H' . $First_row . ':H' . ($numrow - 1) . ')');
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . ($numrow + 1), '=SUM(F' . $numrow . ':H' . $numrow . ')');
      }

      $filename = 'Daily Shift Report';
      $spreadsheet->setActiveSheetIndexByName('FO Cashier Summary');
      $spreadsheet->getSheetByName('FO Cashier Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Deposit Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Refund Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getSheetByName('FO Remaining Payment Summary')->setSelectedCell('A1')->getProtection()->setSheet(true);
      // $spreadsheet->getSheetByName('Daily Operation Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($filename)->setSubject($filename)->setDescription("Import " . $filename)->setKeywords($filename);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function housekeeping_room_change_report()
  {
    $date = $this->input->post('date_report');
    if ($date != '') {
      $spreadsheet = new Spreadsheet();
      $inputFileType = 'Xlsx';
      $inputFileName = FCPATH . 'files/room-change-report.xlsx';
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
      $spreadsheet = $reader->load($inputFileName);
      $printed = 'Printed : ' . dateFormat(date('Y-m-d')) . date(' H:i:s');
      $fordate = 'For Date : ' . dateFormat($date);
      $spreadsheet->getSheetByName('Housekeeping Room Change Report')->setCellValue('A2', $printed);
      $spreadsheet->getSheetByName('Housekeeping Room Change Report')->setCellValue('A3', $fordate);

      ///// Housekeeping Room Change Report
      $no = 1;
      $rooms = 1;
      $numrow = 5;
      $sheets = 'Housekeeping Room Change Report';
      $query = "SELECT * FROM cleaning_histories JOIN users ON cleaning_histories.created_by=users.user_id JOIN rooms USING(room_id) JOIN room_types USING(room_type_id) WHERE DATE(cleaning_histories.created_at)='$date'";
      foreach ($this->db->query($query)->result_array() as $result) {
        $spreadsheet->getSheetByName($sheets)->setCellValue('A' . $numrow, $no);
        $spreadsheet->getSheetByName($sheets)->setCellValue('B' . $numrow, $result['created_at']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('C' . $numrow, $result['user_fullname']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('D' . $numrow, $result['room_type_name']);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $result['room_number']);
        switch ($result['room_status']) {
          case 'VR':
            $room_status = $this->lang->line('text-VR');
            break;
          case 'VC':
            $room_status = $this->lang->line('text-VC');
            break;
          case 'VD':
            $room_status = $this->lang->line('text-VD');
            break;
          case 'OD':
            $room_status = $this->lang->line('text-OD');
            break;
          case 'OC':
            $room_status = $this->lang->line('text-OC');
            break;
          default:
            $room_status = $this->lang->line('text-OO');
        }
        $spreadsheet->getSheetByName($sheets)->setCellValue('F' . $numrow, $room_status);


        $no++;
        $rooms++;
        $numrow++;
        $spreadsheet->getSheetByName($sheets)->insertNewRowBefore($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->getRowDimension($numrow - 1)->setRowHeight(-1);
      }
      if ($no != 1) {
        $spreadsheet->getSheetByName($sheets)->removeRow($numrow, 1);
        $spreadsheet->getSheetByName($sheets)->setCellValue('E' . $numrow, $rooms . ' Rooms');
      }

      $filename = 'Housekeeping Room Change Report';
      $spreadsheet->setActiveSheetIndexByName('Housekeeping Room Change Report');
      $spreadsheet->getSheetByName('Housekeeping Room Change Report')->setSelectedCell('A1')->getProtection()->setSheet(true);
      $spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($filename)->setSubject($filename)->setDescription("Import " . $filename)->setKeywords($filename);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '  - ' . dateFormat($date) . '.xlsx"');
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    } else {
      show_404();
    }
  }

  public function receipt1()
  {
    $payment_number = $this->uri->segment(3);
    if ($payment_number != '') {
      $this->db->join('payments', 'payment_id');
      $payment = $this->db->get_where('payment_histories', ['payment_number' => $payment_number]);
      if ($payment->num_rows() > 0) {
        $payment = $payment->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A5', 'landscape');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['reservation_id' => $payment['reservation_id']])->row_array();


        $this->pdf->filename = "receipt - $payment_number.pdf";
        $this->pdf->load_view('export/receipt', [
          'reservations' => $reservations,
          'payment'      => $payment,
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function reservation1()
  {
    $reservation_number = $this->uri->segment(3);
    if ($reservation_number != '') {
      $reservation = $this->db->get_where('reservations', ['reservation_number' => $reservation_number]);
      if ($reservation->num_rows() > 0) {
        $reservation = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['reservation_number' => $reservation_number])->row_array();

        $this->pdf->filename = "Folio - $reservation_number.pdf";
        $this->pdf->load_view('export/reservation1', [
          'reservations' => $reservations,
          'room_data'        => $this->admin->getReservationRooms($reservations['reservation_id']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function invoice1()
  {
    $bill_number = $this->uri->segment(3);
    if ($bill_number != '') {
      $reservation = $this->db->get_where('reservations', ['bill_number' => $bill_number, 'receipt_type' => 'invoice']);
      if ($reservation->num_rows() > 0) {
        $reservation = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['bill_number' => $bill_number])->row_array();


        $this->pdf->filename = "Invoice - $bill_number.pdf";
        $this->pdf->load_view('export/invoice1', [
          'reservations' => $reservations,
          'room_data'        => $this->admin->getReservationRooms($reservations['reservation_id']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  public function bill1()
  {
    $bill_number = $this->uri->segment(3);
    if ($bill_number != '') {
      $reservation = $this->db->get_where('reservations', ['bill_number' => $bill_number, 'receipt_type' => 'bill']);
      if ($reservation->num_rows() > 0) {
        $reservation = $reservation->row_array();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->load->model('Admin_model', 'admin');

        $this->db->join('guests', 'guest_id');
        $this->db->join('segments', 'segment_id');
        $this->db->select("*, (SELECT room_plans.room_plan_name FROM room_reservations JOIN room_rates using(room_rate_id) JOIN room_plans using(room_plan_id) WHERE room_reservations.reservation_id=reservations.reservation_id LIMIT 1) AS room_plan_name, (SELECT reservation_histories.created_at FROM reservation_histories WHERE reservation_histories.reservation_id=reservations.reservation_id AND reservation_status='Reservation' LIMIT 1) AS reservation_time");
        $reservations  = $this->db->get_where("reservations", ['bill_number' => $bill_number])->row_array();


        $this->pdf->filename = "Bill - $bill_number.pdf";
        $this->pdf->load_view('export/bill1', [
          'reservations' => $reservations,
          'room_data'        => $this->admin->getReservationRooms($reservations['reservation_id']),
        ]);
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }
}
