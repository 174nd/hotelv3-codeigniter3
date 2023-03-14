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
        <th>FOLIO</th>
      </tr>
    </tbody>
  </table>
  <br>
  <?php
  $total_guest = $reservations['adult_guest'] . ' ' . $this->lang->line('text-adult') . ($reservations['child_guest'] != null ? ' & ' . $reservations['child_guest'] . ' ' . $this->lang->line('text-child') : '');
  ?>
  <table class="description-table">
    <tr>
      <th><?= $this->lang->line('text-guests') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['guest_name']; ?></td>
      <th><?= $this->lang->line('text-reservation_number') ?></th>
      <td>:</td>
      <td><?= $reservations['reservation_number']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-total_guest') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $total_guest; ?></td>

      <th><?= $this->lang->line('text-reservation_time') ?></th>
      <td>:</td>
      <td><?= $reservations['reservation_time']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-in_house') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= dateFormat($reservations['checkin_schedule']) . ' - ' . dateFormat($reservations['checkout_schedule']); ?></td>

      <th><?= $this->lang->line('text-checkin_time') ?></th>
      <td>:</td>
      <td><?= $reservations['checkin_time'] ?? '-'; ?></td>
    <tr>
      <th><?= $this->lang->line('text-room_plan') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['room_plan_name']; ?></td>

      <th><?= $this->lang->line('text-checkout_time') ?></th>
      <td>:</td>
      <td><?= $reservations['checkout_time'] ?? '-'; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-segment') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['segment_name']; ?></td>
      <th colspan="3"></th>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-deposit') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= moneyFormat($reservations['deposit']); ?></td>
      <th colspan="3"></th>
    </tr>
  </table>

  <br>

  <table class="folio-table">
    <thead>
      <tr>
        <th class='text-center' style="width: 130px;"><?= $this->lang->line('text-date') ?></th>
        <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
        <th class='text-center'><?= $this->lang->line('text-price') ?></th>
        <th class='text-center'><?= $this->lang->line('text-total') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      foreach ($room_data as $date => $x) {
        foreach ($x as $a) {
          if ($a['type'] == 'payment') {
            if ($a['show']) {
              $total += $a['total_payment']; ?>
              <tr class="payment_histories">
                <td class="text-center"><?= dateFormat($date) ?></td>
                <td class="text-center"><?= $this->lang->line('text-payment') ?></td>
                <td colspan="2"><?= $a['payment_desciption'] ?></td>
                <td class="text-center"><?= moneyFormat($a['total_payment']); ?></td>
                <td class="text-center"><?= moneyFormat($total); ?></td>
              </tr>
            <?php }
          } else if ($a['type'] == 'additional_cost') {
            $total -= $a['additional_cost_price']; ?>
            <tr class="additional_costs">
              <td class="text-center"><?= dateFormat($date) ?></td>
              <td class="text-center"><?= $a['additional_cost_type']; ?></td>
              <td colspan="2"><?= $a['additional_cost_description']; ?></td>
              <td class="text-center"><?= moneyFormat($a['additional_cost_price']); ?></td>
              <td class="text-center"><?= moneyFormat($total); ?></td>
            </tr>
          <?php } else {
            $total -= $a['room_price']; ?>
            <tr>
              <td class="text-center"><?= dateFormat($date) ?></td>
              <td colspan="3"><?= $a['room_number']  ?></td>
              <td class="text-center"><?= moneyFormat($a['room_price']); ?></td>
              <td class="text-center"><?= moneyFormat($total); ?></td>
            </tr>
      <?php }
        }
      } ?>
      <tr class="payment">
        <th class="text-center align-middle" colspan="5"><?= $this->lang->line($total < 0 ? 'text-remaining_payment' : 'text-remaining_return') ?></th>
        <th class="text-center"><?= moneyFormat(abs($total)); ?></th>
      </tr>
      <?php if ($reservations['receipt_type'] == 'invoice') { ?>
        <tr class="payment">
          <th class="text-center align-middle" colspan="5"><?= $this->lang->line('text-deposit_refund') ?></th>
          <th class="text-center"><?= moneyFormat(abs($reservations['deposit'])); ?></th>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</body>

</html>