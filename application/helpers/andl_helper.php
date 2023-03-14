<?php

function kicked($akses = '')
{
  $ci = get_instance();
  if (!$ci->session->userdata('username') || !$ci->session->userdata('password') || !$ci->session->userdata('user_access')) {
    $ci->session->set_flashdata('message', $ci->lang->line('alert-kicked'));
    redirect('auth/logout');
  } else {
    if ($akses != '' && $ci->session->userdata('user_access') != $akses) {
      $ci->session->set_flashdata('message', $ci->lang->line('alert-kicked'));
      redirect('auth/logout');
    } else {
      if ($ci->db->get_where('users', [
        'username'    => $ci->session->userdata('username'),
        'password'    => $ci->session->userdata('password'),
        'user_access' => $ci->session->userdata('user_access'),
      ])->row_array() < 1) {
        $ci->session->set_flashdata('message', $ci->lang->line('alert-kicked'));
        redirect('auth/logout');
      }
    }
  }
}

function set_sidebar($akses)
{
  $ci = get_instance();
  if ($akses == 'admin') {
    return [
      $ci->lang->line('text-dashboard')   => ['fas fa-window-restore', base_url('admin')],
      $ci->lang->line('table-guests')     => ['fas fa-users', base_url('guests')],
      $ci->lang->line('table-rooms')      => ['fas fa-bed', base_url('rooms')],
      $ci->lang->line('table-room_types') => ['fas fa-person-booth', base_url('room_types')],
      $ci->lang->line('table-room_plans') => ['fas fa-clipboard-check', base_url('room_plans')],
      $ci->lang->line('table-room_rates') => ['fas fa-receipt', base_url('room_rates')],
      $ci->lang->line('table-segments')   => ['fas fa-hands-helping', base_url('segments')],
      // $ci->lang->line('table-payments')   => ['fas fa-money-check', base_url('payments')],
      $ci->lang->line('table-sessions')   => ['fas fa-business-time', base_url('sessions')],
      $ci->lang->line('table-requests')    => ['fas fa-money-bill-wave', base_url('requests')],
      $ci->lang->line('table-floors')     => ['fas fa-hotel', base_url('floors')],
      $ci->lang->line('table-users')      => ['fas fa-user', base_url('users')],


      'Other',
      'Restoran <span class="badge badge-info right">Extends</span>' => ['fas fa-utensils', '#'],
      'Laundry <span class="badge badge-info right">Extends</span>' => ['fas fa-socks', '#'],
      'Taxi <span class="badge badge-info right">Extends</span>' => ['fas fa-taxi', '#'],
      'Keuangan <span class="badge badge-info right">Extends</span>' => ['fas fa-money-bill-wave', '#'],
    ];
  } else {
    return [];
  }
}

function setCode($huruf, $digit, $field, $tabel, $pencarian = null, $tambah = 0)
{
  $ci = get_instance();
  $ci->db->select("MAX($field) AS taking");
  $cekdata = $ci->db->get_where($tabel, $pencarian);
  if ($cekdata->num_rows() > 0) {
    $ada = $cekdata->row_array();
    $depan = strlen($huruf);
    $angka = $digit - $depan;
    $IDAuto = ((int) substr($ada['taking'], $depan, $digit)) + 1 + $tambah;
    $IDAuto = $huruf . str_pad($IDAuto, $angka, "0", STR_PAD_LEFT);
  } else {
    $depan = strlen($huruf);
    $angka = $digit - $depan;
    $IDAuto = $huruf . str_pad("1", $angka, "0", STR_PAD_LEFT);
  }
  return $IDAuto;
}

function dateFormat($date)
{
  $ci = get_instance();
  $ci->lang->load('calendar');
  $month = [
    1 =>   $ci->lang->line('cal_january'),
    $ci->lang->line('cal_february'),
    $ci->lang->line('cal_march'),
    $ci->lang->line('cal_april'),
    $ci->lang->line('cal_mayl'),
    $ci->lang->line('cal_june'),
    $ci->lang->line('cal_july'),
    $ci->lang->line('cal_august'),
    $ci->lang->line('cal_september'),
    $ci->lang->line('cal_october'),
    $ci->lang->line('cal_november'),
    $ci->lang->line('cal_december')
  ];

  $split    = explode('-', $date);
  return $split[2] . ' ' . $month[(int) $split[1]] . ' ' . $split[0];
}

function moneyFormat($angka, $prefix = null)
{

  $hasil_rupiah = ($prefix ? $prefix : "Rp. ") . number_format($angka, 0, ',', '.');
  return $hasil_rupiah;
}


function getAutoIncrement($tabel)
{

  $ci = get_instance();
  $database = $ci->db->database;
  $get = $ci->db->query("SELECT `AUTO_INCREMENT` AS id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$tabel'")->row_array();
  return $get['id'];
}

function sidebar($sidebar, $pencarian = null)
{
  // FORMAT PENCARIAN => Nama. background, treeview
  foreach ($sidebar as $key => $value) {
    if (is_numeric($key)) {
      echo "<li class='nav-header'>$value</li>";
    } else {

      if (is_string($pencarian) && $pencarian == $key) {
        $navlink = ' active';
        $treeview = ' menu-open';
      } else if (isset($pencarian[0]) && $pencarian[0] == $key) {
        $navlink = isset($pencarian[1]) ? ($pencarian[1] === true ? ' active' : (is_string($pencarian[1]) ? ' ' . $pencarian[1] : '')) : ' active';
        $treeview = isset($pencarian[2]) && is_bool($pencarian[2]) ? ($pencarian[2] === true ? ' menu-open' : '') : ' menu-open';
        if (isset($pencarian[2]) && is_bool($pencarian[2])) array_splice($pencarian, 2, 1);
        if (isset($pencarian[1])) array_splice($pencarian, 1, 1);
        array_splice($pencarian, 0, 1);
        $set_cari = null;
      } else if (is_array($pencarian) && in_array($key, array_keys($pencarian), TRUE)) {
        $navlink = isset($pencarian[0]) ? ($pencarian[0] === true ? ' active' : (is_string($pencarian[0]) ? ' ' . $pencarian[0] : '')) : ' active';
        $treeview = isset($pencarian[1]) && is_bool($pencarian[1]) ? ($pencarian[1] === true ? ' menu-open' : '') : ' menu-open';
        if (isset($pencarian[1]) && is_bool($pencarian[1])) array_splice($pencarian, 1, 1);
        if (isset($pencarian[1])) array_splice($pencarian, 1, 1);
        $set_cari = $pencarian[$key];
        unset($pencarian[$key]);
      } else {
        $navlink = '';
        $treeview = '';
        $set_cari = null;
      }
      if (is_array($value[1])) {
        echo "<li class='nav-item has-treeview$treeview'><a href='#' class='nav-link$navlink'><i class='nav-icon $value[0]'></i><p>$key<i class='right fas fa-angle-left'></i></p></a><ul class='nav nav-treeview'>";
        sidebar($value[1], $set_cari);
        echo "</ul></li>";
      } else {
        echo "<li class='nav-item'><a href='$value[1]' class='nav-link$navlink'><i class='nav-icon $value[0]'></i><p>$key</p></a></li>";
      }
    }
  }
}

function cekSama($kiriman, $returnnya, $val = "selected", $notsame = "")
{
  if ((is_array($returnnya)) ? in_array($kiriman, $returnnya) : $kiriman == $returnnya) {
    return $val;
  } else {
    return $notsame;
  }
}

function set_valup($akses, $data, $hasil = '')
{
  return  set_value($akses) != '' ? set_value($akses) : (isset($data[$akses]) ? $data[$akses] : $hasil);
}
