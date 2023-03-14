<form method="POST" enctype="multipart/form-data" autocomplete="off" id="form-utama">
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary card-outline">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-8 mb-2">
                  <label class="float-right" for="user_fullname"><?= $this->lang->line('field-user_fullname'); ?></label>
                  <input type="text" name="user_fullname" class="form-control" id="user_fullname" placeholder="<?= $this->lang->line('field-user_fullname'); ?>" value="<?= set_valup('user_fullname', $user); ?>">
                  <?= form_error('user_fullname', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="float-right" for="user_access"><?= $this->lang->line('field-user_access'); ?></label>
                  <select name="user_access" id="user_access" class="form-control custom-select">
                    <option value="admin" <?= cekSama(set_valup('user_access', $user), 'admin'); ?>>Admin</option>
                    <option value="frontoffice" <?= cekSama(set_valup('user_access', $user), 'frontoffice'); ?>>Front-Office</option>
                    <option value="housekeeping" <?= cekSama(set_valup('user_access', $user), 'housekeeping'); ?>>Housekeeping</option>
                    <option value="nightaudit" <?= cekSama(set_valup('user_access', $user), 'nightaudit'); ?>>Night Audit</option>
                  </select>
                  <?= form_error('user_access', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-6 mb-2">
                  <label class="float-right" for="username"><?= $this->lang->line('field-username'); ?></label>
                  <input type="Username" name="username" class="form-control" id="username" placeholder="<?= $this->lang->line('field-username'); ?>" value="<?= set_valup('username', $user); ?>">
                  <?= form_error('username', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="float-right" for="password"><?= $this->lang->line('field-password'); ?></label>
                  <input type="text" name="password" class="form-control" id="password" placeholder="<?= $this->lang->line('field-password'); ?>" value="<?= set_valup('password', $user); ?>">
                  <?= form_error('password', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-12">
                  <label class="float-right" for="user_photo"><?= $this->lang->line('field-user_photo'); ?></label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" name="user_photo" class="custom-file-input" id="user_photo">
                      <label class="custom-file-label" for="user_photo"><?= set_valup('user_photo', $user, $this->lang->line('text-input_file')); ?></label>
                      <?= form_error('user_photo', '<span class="invalid-feedback d-block">', '</span>'); ?>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-block btn-primary"><?= $this->lang->line('text-' . ($this->uri->segment(3) == '' ? 'save' : 'update')); ?></button>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-6">
      <div class="card card-primary card-outline">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-2">
              <select id="column_users" class="form-control custom-select">
                <option value="1"><?= $this->lang->line('field-user_fullname'); ?></option>
                <option value="0"><?= $this->lang->line('field-username'); ?></option>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_users">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_users" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-username'); ?></th>
                  <th><?= $this->lang->line('field-user_fullname'); ?></th>
                  <th><?= $this->lang->line('text-act'); ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
</form>

<div class="modal fade" id="users-delete">
  <form method="POST" action="<?= base_url('users/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-users'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-users')); ?></h4>
        <input type="hidden" name="user_id" id="user_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-users" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->