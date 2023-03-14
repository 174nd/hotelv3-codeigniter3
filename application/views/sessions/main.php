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
                <div class="col-md-12 mb-2">
                  <label class="float-right" for="session_name"><?= $this->lang->line('field-session_name'); ?></label>
                  <input type="text" name="session_name" class="form-control" id="session_name" placeholder="<?= $this->lang->line('field-session_name'); ?>" value="<?= set_valup('session_name', $session); ?>">
                  <?= form_error('session_name', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-6 mb-2 mb-md-0">
                  <label class="float-right" for="start_session"><?= $this->lang->line('field-start_session'); ?></label>
                  <div class="input-group">
                    <input type="text" name="start_session" class="form-control datepicker" id="start_session" placeholder="<?= $this->lang->line('field-start_session'); ?>" value="<?= set_valup('start_session', $session); ?>">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                  <?= form_error('start_session', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-6">
                  <label class="float-right" for="end_session"><?= $this->lang->line('field-end_session'); ?></label>
                  <div class="input-group">
                    <input type="text" name="end_session" class="form-control datepicker" id="end_session" placeholder="<?= $this->lang->line('field-end_session'); ?>" value="<?= set_valup('end_session', $session); ?>">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                  <?= form_error('end_session', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
            <div class="col-md-12 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_sessions">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_sessions" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-session_name'); ?></th>
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


<div class="modal fade" id="sessions-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-sessions-data'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="session_name">
            <b><?= $this->lang->line('field-session_name'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="session_length">
            <b><?= $this->lang->line('text-session_length'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="btn-group w-100 set-button">
              <button type="button" class="btn btn-sm btn-danger set-delete"><?= $this->lang->line('text-delete'); ?></button>
              <a href="#" class="btn btn-sm bg-info set-update"><?= $this->lang->line('text-update'); ?></a>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="sessions-delete">
  <form method="POST" action="<?= base_url('sessions/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-sessions'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-sessions')); ?></h4>
        <input type="hidden" name="session_id" id="session_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-sessions" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->