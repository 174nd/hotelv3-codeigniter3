<!DOCTYPE html>
<html>

<head>
  <?php $this->view('templates/header'); ?>
</head>

<body class="hold-transition login-page bg-light">
  <?= $content; ?>
  <?php $this->view('templates/script'); ?>
</body>

</html>