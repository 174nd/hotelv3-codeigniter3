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
                <div class="col-md-3 mb-2 mb-md-0">
                  <label class="float-right" for="room_number"><?= $this->lang->line('field-room_number'); ?></label>
                  <input type="text" name="room_number" class="form-control" id="room_number" placeholder="<?= $this->lang->line('field-room_number'); ?>" value="<?= set_valup('room_number', $room); ?>">
                  <?= form_error('room_number', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-5 mb-2 mb-md-0">
                  <div class="row">
                    <div class="col-12">
                      <label class="float-right" for="room_type_id"><?= $this->lang->line('text-room_type'); ?></label>
                    </div>
                    <div class="col-12">
                      <select name="room_type_id" id="room_type_id" class="form-control custom-select select2">
                        <?php foreach ($room_types as $a) { ?>
                          <option value="<?= $a['room_type_id']; ?>" <?= set_valup('room_type_id', $room) == $a['room_type_id'] ? 'selected' : ''; ?>><?= $a['room_type_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?= form_error('id_area', '<span class="invalid-feedback d-block">', '</span>'); ?>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                  <div class="row">
                    <div class="col-12">
                      <label class="float-right" for="floor_id"><?= $this->lang->line('text-floor'); ?></label>
                    </div>
                    <div class="col-12">
                      <select name="floor_id" id="floor_id" class="form-control custom-select select2">
                        <?php foreach ($floors as $a) { ?>
                          <option value="<?= $a['floor_id']; ?>" <?= set_valup('floor_id', $room) == $a['floor_id'] ? 'selected' : ''; ?>><?= $a['floor_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <?= form_error('id_area', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
              <select id="column_rooms" class="form-control custom-select">
                <option value="0"><?= $this->lang->line('field-room_number'); ?></option>
                <option value="1"><?= $this->lang->line('text-floor'); ?></option>
                <option value="2"><?= $this->lang->line('text-room_type'); ?></option>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_rooms">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_rooms" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-room_number'); ?></th>
                  <th><?= $this->lang->line('text-floor'); ?></th>
                  <th><?= $this->lang->line('text-room_type'); ?></th>
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