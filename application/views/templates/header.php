<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?= (isset($set['title'])) ? $set['title'] . ' - ' : ''; ?>Nagoya Plasa Hotel</title>
<link rel="icon" type="image/png" href="<?= base_url('dist/img/logo.ico') ?>">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css'); ?>" />
<!-- pace-progress -->
<link rel="stylesheet" href="<?= base_url('plugins/pace-progress/themes/blue/pace-theme-center-simple.css'); ?>" />
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>" />

<?= (isset($add_css)) ? $add_css : ''; ?>

<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css'); ?>" />
<link rel="stylesheet" href="<?= base_url('dist/css/style.css'); ?>" />
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />

<?= (isset($own_css)) ? $own_css : ''; ?>