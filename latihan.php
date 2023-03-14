<?php
$sidebar = [
  'Dashboard' => ['fas fa-window-restore', [
    'xxx' => ['fas fa-window-restore', 'admin'],
  ]],
  'Tamu' => ['fas fa-users', 'tamu'],
  'Kamar' => ['fas fa-bed', 'kamar'],
  'Tipe Kamar' => ['fas fa-hotel', 'tipe-kamar'],
  'User' => ['fas fa-user', 'user'],
];

$pencarian = 'Dashboard';

// var_dump(array_keys($pencarian));
// echo in_array('Dashboard', array_keys($pencarian), TRUE);
// var_dump($pencarian);
function sidebar($sidebar, $pencarian)
{
  foreach ($sidebar as $key => $value) {
    if (is_numeric($key)) {
      echo "<li class='nav-header'>$value</li>";
    } else {
      if (is_string($pencarian) && $pencarian == $key) {
        $navlink = ' active';
        $treeview = ' menu-open';
      } else if (isset($pencarian[0]) && $pencarian[0] == $key) {
        $navlink = isset($pencarian[1]) ? ($pencarian[1] === true ? ' active' : (is_string($pencarian[1]) ? ' ' . $pencarian[1] : '')) : ' active';
        $treeview = isset($pencarian[2]) ? ($pencarian[2] === true ? ' menu-open' : '') : ' menu-open';
        $set_cari = null;
      } else if (is_array($pencarian) && in_array($key, array_keys($pencarian), TRUE)) {
        $navlink = isset($pencarian[0]) ? ($pencarian[0] === true ? ' active' : (is_string($pencarian[0]) ? ' ' . $pencarian[0] : '')) : ' active';
        $treeview = isset($pencarian[1]) ? ($pencarian[1] === true ? ' menu-open' : '') : ' menu-open';
        $set_cari = isset($pencarian[$key]) ? $pencarian[$key] : $pencarian;
      } else {
        $navlink = '';
        $treeview = '';
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
echo sidebar($sidebar, $pencarian);
