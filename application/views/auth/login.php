<div class="login-box">
  <div class="login-logo">
    <a href="<?= base_url(); ?>" class="text-dark"><b>Nagoya </b> Plasa Hotel</a>
  </div>
  <?= $this->session->flashdata('message'); ?>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form action="" method="POST" autocomplete="off">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="<?= $this->lang->line('field-username'); ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <?= form_error('username', '<span class="invalid-feedback d-block">', '</span>'); ?>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="<?= $this->lang->line('field-password'); ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <?= form_error('password', '<span class="invalid-feedback d-block">', '</span>'); ?>
        </div>
        <div class="row">
          <div class="col-md-4 offset-md-8">
            <button type="submit" name="login" class="btn btn-primary btn-block"><?= $this->lang->line('text-login'); ?></button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.login-box -->