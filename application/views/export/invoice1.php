<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= FCPATH . 'dist/css/export.css'; ?>" />
</head>

<body>

  <table>
    <tr>
      <td>
        <img src="<?= FCPATH . 'dist/img/logo sunera hotel.png' ?>" style="width: 300px; margin-left:200px;">
      </td>
    </tr>
  </table>
  <table class="header-table">
    <tbody>
      <tr>
        <th>INVOICE</th>
      </tr>
    </tbody>
  </table>
  <br>
  <?php
  $longstay = date_diff(date_create($reservations['checkout_schedule']), date_create($reservations['checkin_schedule']))->format("%a");
  $longstay = intval($longstay);
  $total_guest = $reservations['adult_guest'] . ' ' . $this->lang->line('text-adult') . ($reservations['child_guest'] != null ? ' & ' . $reservations['child_guest'] . ' ' . $this->lang->line('text-child') : '');
  ?>
  <table class="description-table">
    <tr>
      <th><?= $this->lang->line('text-guest_name') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['guest_name']; ?></td>
      <th><?= $this->lang->line('text-invoice_number') ?></th>
      <td>:</td>
      <td><?= $reservations['bill_number']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-segment') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['segment_name']; ?></td>
      <th><?= $this->lang->line('text-reservation_number') ?></th>
      <td>:</td>
      <td><?= $reservations['reservation_number']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-total_guest') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $total_guest; ?></td>
      <th><?= $this->lang->line('text-in_house') ?></th>
      <td>:</td>
      <td><?= $longstay . ' ' . $this->lang->line('text-night'); ?></td>
    </tr>
    <tr>
      <th colspan="3"></th>
      <th><?= $this->lang->line('text-room_plan') ?></th>
      <td>:</td>
      <td><?= $reservations['room_plan_name']; ?></td>
    </tr>
    <tr>
      <th colspan="3"></th>
      <th><?= $this->lang->line('text-deposit') ?></th>
      <td>:</td>
      <td><?= moneyFormat($reservations['deposit']); ?></td>
    </tr>
    <tr>
      <th colspan="3"></th>
      <th><?= $this->lang->line('text-checkin_time') ?></th>
      <td>:</td>
      <td><?= $reservations['checkin_time']; ?></td>
    </tr>
    <tr>
      <th colspan="3"></th>
      <th><?= $this->lang->line('text-reservation_time') ?></th>
      <td>:</td>
      <td><?= $reservations['reservation_time']; ?></td>
    </tr>
  </table>

  <br>

  <table class="folio-table">
    <thead>
      <tr>
        <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
        <th class='text-center'><?= $this->lang->line('text-price') ?></th>
        <th class='text-center'><?= $this->lang->line('text-qty') ?></th>
        <th class='text-center'><?= $this->lang->line('text-total') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      foreach ($room_data['rooms'] as $r1) {
        foreach ($r1['room_data'] as $r2) {
          $in_house = round((strtotime($r2['checkout']) - strtotime($r2['checkin'])) / (60 * 60 * 24));
          $sessions = isset($r2['session_name']) ? '<br><span class="font-italic">Sessions : ' + $r2['session_name'] + '</span>' : '';
          $total += intval($r2['room_price']) * $in_house; ?>
          <tr>
            <td colspan="3" rowspan="<?= $r2['row_number'] ?>" class="align-middle"><?= 'Room reserved type : ' . $r2['row_number'] . ' No. ' . $r2['room_number'] . $sessions  ?></td>
            <td class="align-middle text-center"><?= moneyFormat($r2['room_price']) ?></td>
            <td rowspan="<?= $r2['row_number'] ?>" class="align-middle text-center"><?= $in_house . ' ' . $this->lang->line('text-night') ?></td>
            <td rowspan="<?= $r2['row_number'] ?>" class="align-middle text-center"><?= moneyFormat(intval($r2['room_price']) * $in_house); ?></td>
          </tr>
          <?php foreach ($r2['price_change'] as $r3) { ?>
            <tr>
              <td class="align-middle text-center"><?= moneyFormat($r3) ?></td>
            </tr>
          <?php }
        }
        foreach ($r1['additional_costs'] as $r2) {
          $cost_type;
          if ($r2['additional_cost_type'] == 'discount') {
            $total = $total - intval($r2['additional_cost_price']);
            $cost_type = $this->lang->line('text-discount');
          } else {
            $total = $total + intval($r2['additional_cost_price']);
            $cost_type = $r2['additional_cost_type'] == 'request' ? $this->lang->line('text-request') : $this->lang->line('text-loss_or_damage');
          } ?>
          <tr class="additional_costs">
            <td class="align-middle text-center"><?= $cost_type; ?></td>
            <td colspan="4" class="align-middle"><?= $r2['additional_cost_description']; ?></td>
            <td class="align-middle text-center"><?= moneyFormat($r2['additional_cost_price']); ?></td>
          </tr>
      <?php }
      } ?>
      <tr class="payment">
        <th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-total_price') ?></th>
        <th class="text-center"><?= moneyFormat($total); ?></th>
      </tr>
      <?php if (count($room_data['payment_histories']) > 0) {
        $remaining = false;
        foreach ($room_data['payment_histories'] as $r1) {
          if ($r1['payment_desciption'] != 'Remaining Payment') {
            $remaining = true;
            $total = $total - intval($r1['total_payment']); ?>
            <tr class="payment_histories">
              <td colspan=" 2" class="align-middle text-center"><?= dateFormat($r1['payment_date']); ?></td>
              <td colspan="3" class="align-middle"><?= $r1['payment_desciption']; ?></td>
              <td class="align-middle text-center"><?= moneyFormat($r1['total_payment']); ?></td>
            </tr>
          <?php }
        }
        if ($remaining) { ?>
          <tr class="payment">
            <th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-remaining_payment') ?></th>
            <th class="text-center"><?= moneyFormat($total); ?></th>
          </tr>
      <?php }
      } ?>
    </tbody>
  </table>
</body>

</html>