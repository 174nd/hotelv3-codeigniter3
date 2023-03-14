<div class="container-fluid">
  <!-- Main row -->
  <div class="row">
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-primary vacant_ready">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-VR'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-person-booth"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-orange vacant_clean">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-VC'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-recycle"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-danger vacant_dirty">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-VD'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-dumpster-fire"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-info vacant_room">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-vacant') . ' ' . $this->lang->line('text-rooms'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-hotel"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-purple occupied">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-occupied'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-bed"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-success occupied_clean">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-OC'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-user-shield"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-warning occupied_dirty">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-OD'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-trash-restore"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <!-- small box -->
      <div class="small-box bg-dark out_of_service">
        <div class="inner text-light">
          <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-rooms'); ?></sup></h3>
          <p><?= $this->lang->line('text-OO'); ?></p>
        </div>
        <div class="icon">
          <i class="fa text-light fa-tools"></i>
        </div>
      </div>
    </div>

    <section class="col-md-8 connectedSortable">
      <div class="row">
        <div class="col-md-6">
          <!-- small box -->
          <div class="small-box bg-orange expected_departure">
            <div class="inner text-light">
              <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup></h3>
              <p><?= $this->lang->line('text-expected_departure'); ?></p>
            </div>
            <div class="icon">
              <i class="fa text-light fa-sign-out-alt"></i>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <!-- small box -->
          <div class="small-box bg-fuchsia expected_arrival">
            <div class="inner text-light">
              <h3>x <sup style="font-size: 20px"><?= $this->lang->line('text-guests'); ?></sup></h3>
              <p><?= $this->lang->line('text-expected_arrival'); ?></p>
            </div>
            <div class="icon">
              <i class="fa text-light fa-concierge-bell"></i>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="col-md-4 connectedSortable" id="report">
      <form class="card card-primary collapsed-card" method="POST" action="<?= base_url('export/reservation_report') ?>">
        <div class="card-header">
          <h3 class="card-title"><?= $this->lang->line('text-reservation_report'); ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <label class="float-right" for="date_report"><?= $this->lang->line('text-date_report'); ?></label>
              <div class="input-group">
                <input type="text" name="date_report" id="date_report" class="form-control datepicker" placeholder="<?= $this->lang->line('text-date_report'); ?>" value="<?= date('Y-m-d') ?>" required>
                <div class=" input-group-append">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <button type="submit" id="all-reservation" class="btn btn-block btn-primary"><?= $this->lang->line('text-find_data'); ?></button>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </form>
      <!-- /.Reservation Report -->

      <form class="card card-primary collapsed-card" method="POST" action="<?= base_url('export/nightaudit_report') ?>" id="night_audit">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="card-header">
          <h3 class="card-title"><?= $this->lang->line('text-nightaudit_report'); ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <label class="float-right" for="date_report"><?= $this->lang->line('text-date_report'); ?></label>
              <div class="input-group">
                <input type="text" name="date_report" id="date_report" class="form-control datepicker" placeholder="<?= $this->lang->line('text-date_report'); ?>" value="<?= date('Y-m-d') ?>" required>
                <div class=" input-group-append">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <button type="button" id="export-nightaudit" class="btn btn-block btn-primary"><?= $this->lang->line('text-find_data'); ?></button>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </form>
      <!-- /.Reservation Report -->

    </section>
  </div>
  <!-- /.row (main row) -->
</div>



<div class="modal fade" id="confirm_nightaudit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-nightaudit_report') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="date">
        <p class="m-0"><?= $this->lang->line('text-nightaudit_modal1') . '<b></b>' . $this->lang->line('text-nightaudit_modal2') ?></p>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel') ?></button>
          <button type="button" id="submit_nightaudit" class="btn btn-primary"><?= $this->lang->line('text-confirm') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- /.Modul - Room Status -->
<div class="modal fade" id="room-status-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-room_status_data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-5 mb-2">
                <select id="column_room_status_data" class="form-control custom-select">
                  <option value="0"><?= $this->lang->line('text-rooms') ?></option>
                  <option value="1"><?= $this->lang->line('text-floor') ?></option>
                  <option value="2"><?= $this->lang->line('table-room_types') ?></option>
                  <option value="3"><?= $this->lang->line('text-room_status') ?></option>
                </select>
              </div>
              <div class="col-md-7 mb-2">
                <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data') ?>" id="field_room_status_data">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table id="table_room_status_data" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('text-rooms') ?></th>
                    <th><?= $this->lang->line('text-floor') ?></th>
                    <th><?= $this->lang->line('table-room_types') ?></th>
                    <th><?= $this->lang->line('text-room_status') ?></th>
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