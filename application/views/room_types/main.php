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
                <div class="col-md-12">
                  <label class="float-right" for="room_type_name"><?= $this->lang->line('field-room_type_name'); ?></label>
                  <input type="text" name="room_type_name" class="form-control" id="room_type_name" placeholder="<?= $this->lang->line('field-room_type_name'); ?>" value="<?= set_valup('room_type_name', $room_type); ?>">
                  <?= form_error('room_type_name', '<span class="invalid-feedback d-block">', '</span>'); ?>
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
              <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data'); ?>" id="field_room_types">
            </div>
          </div>
          <div class="table-responsive">
            <table id="table_room_types" class="table table-bordered table-hover" style="min-width: 400px;">
              <thead>
                <tr>
                  <th><?= $this->lang->line('field-room_type_name'); ?></th>
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

<div class="modal fade" id="room_types-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-room_types-data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="room_type_name">
            <b><?= $this->lang->line('field-room_type_name') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="total_rooms">
            <b><?= $this->lang->line('text-total_rooms') ?></b><span class="float-right">x</span>
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
                    <th><?= $this->lang->line('field-room_type_name') ?></th>
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

<div class="modal fade" id="room_types-delete">
  <form method="POST" action="<?= base_url('room_types/delete') ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('table-delete-room_types'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= sprintf($this->lang->line('modal-delete_confirmation'), $this->lang->line('table-room_types')); ?></h4>
        <input type="hidden" name="room_type_id" id="room_type_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <input type="submit" name="delete-room_types" class="btn btn-outline-danger" value="<?= $this->lang->line('text-delete'); ?>">
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