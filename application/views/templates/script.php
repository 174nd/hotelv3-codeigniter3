<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js'); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
  paceOptions = {
    eventLag: false,
    elements: {
      selectors: ['body']
    }
  };
</script>
<!-- pace-progress -->
<script src="<?= base_url('plugins/pace-progress/pace.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- bs-custom-file-input -->
<script src="<?= base_url('plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>

<?= (isset($add_script)) ? $add_script : ''; ?>

<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.js'); ?>"></script>
<script src="<?= base_url('dist/js/script.js'); ?>"></script>


<?= (isset($own_script)) ? $own_script : ''; ?>