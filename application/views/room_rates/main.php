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
                <div class="col-md-7 mb-2">
                  <div class="row">
                    <div class="col-12">
                      <label class="float-right" for="room_type_id"><?= $this->lang->line('text-room_type'); ?></label>
                    </div>
                    <div class="col-12">
                      <select name="room_type_id" id="room_type_id" class="form-control custom-select select2">
                        <?php foreach ($room_types as $a) { ?>
                          <option value="<?= $a['room_type_id']; ?>" <?= set_valup('room_type_id', $room_rate) == $a['room_type_id'] ? 'selected' : ''; ?>><?= $a['room_type_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?= form_error('room_type_id', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-5 mb-2">
                  <label class="float-right" for="room_price"><?= $this->lang->line('field-room_price'); ?></label>
                  <div class="input-group">
                    <div class="input-group-append">
                      <span class="input-group-text">Rp.</span>
                    </div>
                    <input type="text" name="room_price" class="form-control money_format" id="room_price" placeholder="<?= $this->lang->line('field-room_price'); ?>" value="<?= set_valup('room_price', $room_rate); ?>">
                  </div>
                  <?= form_error('room_price', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row w-100 mx-0">
                <div class="col-md-6 mb-2 mb-md-0">
                  <div class="row">
                    <div class="col-12">
                      <label class="float-right" for="room_plan_id"><?= $this->lang->line('text-room_plan'); ?></label>
                    </div>
                    <div class="col-12">
                      <select name="room_plan_id" id="room_plan_id" class="form-control custom-select select2">
                        <?php foreach ($room_plans as $a) { ?>
                          <option value="<?= $a['room_plan_id']; ?>" <?= set_valup('room_plan_id', $room_rate) == $a['room_plan_id'] ? 'selected' : ''; ?>><?= $a['room_plan_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?= form_error('room_plan_id', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-12">
                      <label class="float-right" for="session_id"><?= $this->lang->line('text-session'); ?></label>
                    </div>
                    <div class="col-12">
                      <select name="session_id" id="session_id" class="form-control custom-select select2" data-placeholder="<?= $this->lang->line('text-session'); ?>" data-allow-clear='true'>
                        <option></option>
                        <?php foreach ($sessions as $a) { ?>
                          <option value="<?= $a['session_id']; ?>" <?= set_valup('session_id', $room_rate) == $a['session_id'] ? 'selected' : ''; ?>><?= $a['session_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?= form_error('session_id', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
              <select id="column_room_rates" class="form-control custom-select">
                <option value="0"><?= $this->lang->line('text-room_type'); ?></option>
                <option value="1"><?= $this->lang->line('text-room_plan'); ?></option>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_room_rates">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_room_rates" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('text-room_type'); ?></th>
                  <th><?= $this->lang->line('text-room_plan'); ?></th>
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

<div class="modal fade" id="room_rates-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-room_rates-data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="room_type_name">
            <b><?= $this->lang->line('text-room_type') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="room_plan_name">
            <b><?= $this->lang->line('text-room_plan') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="session_name">
            <b><?= $this->lang->line('text-session') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="room_price">
            <b><?= $this->lang->line('field-room_price') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="btn-group w-100 set-button">
              <button type="button" class="btn btn-sm btn-danger set-delete"><?= $this->lang->line('text-delete') ?></button>
              <a href="#" class="btn btn-sm bg-info set-update"><?= $this->lang->line('text-update') ?></a>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table id="table_rooms" class="table table-bordered table-hover" style="min-width: 300px; width:100%;">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('field-room_number') ?></th>
                    <th><?= $this->lang->line('text-act') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
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

<div class="modal fade" id="room_rates-delete">
  <form method="POST" action="<?= base_url('room_rates/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-room_rates'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-room_rates')); ?></h4>
        <input type="hidden" name="room_rate_id" id="room_rate_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-room_rates" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="rooms-delete">
  <form method="POST" action="<?= base_url('rooms/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-rooms'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-rooms')); ?></h4>
        <input type="hidden" name="room_id" id="room_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-rooms" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->