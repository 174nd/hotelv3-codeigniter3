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
                  <label class="float-right" for="guest_name"><?= $this->lang->line('field-guest_name'); ?></label>
                  <input type="text" name="guest_name" class="form-control" id="guest_name" placeholder="<?= $this->lang->line('field-guest_name'); ?>" value="<?= set_valup('guest_name', $guest); ?>">
                  <?= form_error('guest_name', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="float-right" for="national"><?= $this->lang->line('field-national'); ?></label>
                  <input type="text" name="national" class="form-control" id="national" placeholder="<?= $this->lang->line('field-national'); ?>" value="<?= set_valup('national', $guest, 'Indonesia'); ?>">
                  <?= form_error('national', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-3 mb-2">
                  <label class="float-right" for="identity_type"><?= $this->lang->line('field-identity_type'); ?></label>
                  <select name="identity_type" id="identity_type" class="form-control custom-select">
                    <option <?= cekSama(set_valup('identity_type', $guest), 'KTP'); ?>>KTP</option>
                    <option <?= cekSama(set_valup('identity_type', $guest), 'SIM'); ?>>SIM</option>
                    <option <?= cekSama(set_valup('identity_type', $guest), 'Pasport'); ?>>Pasport</option>
                  </select>
                  <?= form_error('identity_type', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-5 mb-2">
                  <label class="float-right" for="identity_number"><?= $this->lang->line('field-identity_number'); ?></label>
                  <input type="text" name="identity_number" class="form-control" id="identity_number" placeholder="<?= $this->lang->line('field-identity_number'); ?>" value="<?= set_valup('identity_number', $guest); ?>">
                  <?= form_error('identity_number', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="float-right" for="phone_number"><?= $this->lang->line('field-phone_number'); ?></label>
                  <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="<?= $this->lang->line('field-phone_number'); ?>" value="<?= set_valup('phone_number', $guest); ?>">
                  <?= form_error('phone_number', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-5 mb-2">
                  <label class="float-right" for="birth_date"><?= $this->lang->line('field-birth_date'); ?></label>
                  <div class="input-group">
                    <input type="text" name="birth_date" class="form-control datepicker" id="birth_date" placeholder="<?= $this->lang->line('field-birth_date'); ?>" value="<?= set_valup('birth_date', $guest); ?>">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                  <?= form_error('birth_date', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-7 mb-2">
                  <label class="float-right" for="email"><?= $this->lang->line('field-email'); ?></label>
                  <input type="text" name="email" class="form-control" id="email" placeholder="<?= $this->lang->line('field-email'); ?>" value="<?= set_valup('email', $guest); ?>">
                  <?= form_error('email', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-12">
                  <label class="float-right" for="guest_address"><?= $this->lang->line('field-guest_address'); ?></label>
                  <textarea name="guest_address" class="form-control" id="guest_address" placeholder="<?= $this->lang->line('field-guest_address'); ?>"><?= set_valup('guest_address', $guest); ?></textarea>
                  <?= form_error('guest_address', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
              <select id="column_guests" class="form-control custom-select">
                <option value="1"><?= $this->lang->line('field-guest_name'); ?></option>
                <option value="0"><?= $this->lang->line('field-identity_number'); ?></option>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_guests">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_guests" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-identity_number'); ?></th>
                  <th><?= $this->lang->line('field-guest_name'); ?></th>
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


<div class="modal fade" id="guests-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-guests-data'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="guest_name">
            <b><?= $this->lang->line('field-guest_name'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="national">
            <b><?= $this->lang->line('field-national'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="identity">
            <b><?= $this->lang->line('field-identity'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="birth_date">
            <b><?= $this->lang->line('field-birth_date'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="phone_number">
            <b><?= $this->lang->line('field-phone_number'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="email">
            <b><?= $this->lang->line('field-email'); ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="guest_address">
            <b><?= $this->lang->line('field-guest_address'); ?></b><span class="float-right">x</span>
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


<div class="modal fade" id="guests-delete">
  <form method="POST" action="<?= base_url('guests/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-guests'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-guests')); ?></h4>
        <input type="hidden" name="guest_id" id="guest_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-guests" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->