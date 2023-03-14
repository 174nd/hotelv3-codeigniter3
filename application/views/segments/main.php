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
                <div class="col-md-8 mb-2 mb-md-0">
                  <label class="float-right" for="segment_name"><?= $this->lang->line('field-segment_name'); ?></label>
                  <input type="text" name="segment_name" class="form-control" id="segment_name" placeholder="<?= $this->lang->line('field-segment_name'); ?>" value="<?= set_valup('segment_name', $segment); ?>">
                  <?= form_error('segment_name', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-4">
                  <label class="float-right" for="segment_type"><?= $this->lang->line('field-segment_type'); ?></label>
                  <select name="segment_type" id="segment_type" class="form-control custom-select">
                    <option value="tentative" <?= cekSama(set_valup('segment_type', $segment), 'tentative'); ?>>Tentative</option>
                    <option value="guaranted" <?= cekSama(set_valup('segment_type', $segment), 'guaranted'); ?>>Guaranted</option>
                    <option value="non-guaranted" <?= cekSama(set_valup('segment_type', $segment), 'non-guaranted'); ?>>Non-Guaranted</option>
                  </select>
                  <?= form_error('segment_type', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
              <select id="column_segments" class="form-control custom-select">
                <option value="0"><?= $this->lang->line('field-segment_name'); ?></option>
                <option value="1"><?= $this->lang->line('field-segment_type'); ?></option>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_segments">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_segments" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-segment_name'); ?></th>
                  <th><?= $this->lang->line('field-segment_type'); ?></th>
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

<div class="modal fade" id="segments-delete">
  <form method="POST" action="<?= base_url('segments/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-segments'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-segments')); ?></h4>
        <input type="hidden" name="segment_id" id="segment_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-segments" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->