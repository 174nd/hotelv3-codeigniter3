<div class="login-box">
  <div class="alert alert-warning">
    <h5><i class="icon fas fa-ban"></i> <?= $this->lang->line('text-error'); ?></h5>
    <?= $this->lang->line('message-error_page'); ?>
    <br><br>
    <a href="<?= base_url('auth/logout'); ?>" class="btn btn-warning float-right text-dark" style="text-decoration:none;"><?= $this->lang->line('text-back'); ?></a><br><br>
  </div>

</div>
<!-- /.login-box -->