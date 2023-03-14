<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= FCPATH . 'dist/css/export.css'; ?>" />
  <style>
  </style>
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
        <th>RECEIPT</th>
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

      <th><?= $this->lang->line('text-reservation_number') ?></th>
      <td>:</td>
      <td><?= $reservations['reservation_number']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-total_guest') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $total_guest; ?></td>

      <th><?= $this->lang->line('text-payment_number') ?></th>
      <td>:</td>
      <td><?= $payment['payment_number']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-in_house') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= dateFormat($reservations['checkin_schedule']) . ' - ' . dateFormat($reservations['checkout_schedule']); ?></td>

      <th><?= $this->lang->line('text-payment_type') ?></th>
      <td>:</td>
      <td><?= $payment['payment_name']; ?></td>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-room_plan') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['room_plan_name']; ?></td>
      <th colspan="3"></th>
    </tr>
    <tr>
      <th><?= $this->lang->line('text-segment') ?></th>
      <td>:</td>
      <td class="hasil_mo"><?= $reservations['segment_name']; ?></td>
      <th colspan="3"></th>
    </tr>
  </table>

  <br>

  <table class="payment-table">
    <thead>
      <tr>
        <th class='text-center'><?= $this->lang->line('text-payment_date') ?></th>
        <th class='text-center'><?= $this->lang->line('text-payment_description') ?></th>
        <th class='text-center'><?= $this->lang->line('text-total_payment') ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class='text-center'><?= dateFormat($payment['payment_date']) ?></td>
        <td><?= $payment['payment_desciption'] ?></td>
        <td class='text-center'><?= moneyFormat(abs($payment['total_payment'])) ?></td>
      </tr>
    </tbody>
  </table>

  <br>

  <table style="float: right; font-size:12px; width:150px;">
    <tr>
      <td style="height:90px"></td>
    </tr>
    <tr>
      <td style="text-align: center;">( C A S H I E R )</td>
    </tr>
  </table>
</body>

</html>