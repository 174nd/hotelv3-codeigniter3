<div class="container-fluid">
  <!-- Main row -->
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body bg-primary">
          <button type="button" class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#add-reservation"><?= $this->lang->line('text-room_reservation'); ?></button>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.Reservasi Kamar -->
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body bg-purple">
          <button type="button" class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#reservation-data"><?= $this->lang->line('text-reservation_data'); ?></button>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.Cek Reservasi -->
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body bg-olive">
          <button type="button" class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#checkin-data"><?= $this->lang->line('text-checkin_data'); ?></button>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.Cek Checkin -->
    </div>
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

      <form class="card card-warning collapsed-card" method="POST" action="<?= base_url('export/daily_shift_frontoffice') ?>">
        <div class="card-header">
          <h3 class="card-title"><?= $this->lang->line('text-daily_shift_report'); ?></h3>
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
              <label class="float-right" for="shift_id"><?= $this->lang->line('text-shift'); ?></label>
              <select name="shift_id" id="shift_id" class="form-control custom-select" required>
                <?php foreach ($shift as $r) { ?>
                  <option value="<?= $r['shift_id']; ?>"><?= $r['shift_name'] ?></option>
                <?php } ?>
              </select>
            </li>
            <li class="list-group-item">
              <button type="submit" id="all-shift" class="btn btn-block btn-warning"><?= $this->lang->line('text-find_data'); ?></button>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </form>
      <!-- /.Daily Shift Report -->

      <div class="card card-dark collapsed-card" id="guest_history">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="card-header">
          <h3 class="card-title"><?= $this->lang->line('text-guest_history'); ?></h3>
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
              <button type="button" id="check_guest" class="btn btn-block btn-dark"><?= $this->lang->line('text-find_data'); ?></button>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.Guest History -->

    </section>
  </div>
  <!-- /.row (main row) -->
</div>

<!-- /.Modul - Add Reservation -->
<div class="modal fade" id="add-reservation" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-add_reservation'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-6 mb-2">
                <label class="float-right" for="checkin"><?= $this->lang->line('text-checkin'); ?></label>
                <div class="input-group">
                  <input type="text" name="checkin" id="checkin" class="form-control datepicker" placeholder="<?= $this->lang->line('text-checkin'); ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label class="float-right" for="checkout"><?= $this->lang->line('text-checkout'); ?></label>
                <div class="input-group">
                  <input type="text" name="checkout" id="checkout" class="form-control datepicker" placeholder="<?= $this->lang->line('text-checkout'); ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="col-md-12">
              <div class="row">
                <div class="col-12">
                  <label class="float-right" for="room_plans"><?= $this->lang->line('text-room_plan'); ?></label>
                </div>
                <div class="col-12">
                  <select name="room_plans" id="room_plans" class="form-control custom-select select2" required>
                  </select>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel') ?></button>
          <button type="button" id="find_rooms" class="btn btn-primary"><?= $this->lang->line('text-find_rooms') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="rooms-data" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-rooms_data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered m-0">
          <?php foreach ($floors as $result) { ?>
            <tr>
              <th class="text-center"><?= $result['floor_name']; ?></th>
            </tr>
            <tr>
              <td>
                <div class="row">
                  <?php foreach ($result['rooms'] as $room) { ?>
                    <div class="col-4 col-md-2 d-flex align-items-center">
                      <div class="small-box bg-primary w-100" room_id="<?= $room['room_id']; ?>">
                        <div class="inner text-light text-center">
                          <h3><?= $room['room_number']; ?></h3>
                          <p class="m-0"><?= $room['room_type_name']; ?></p>
                          <span class="font-italic"></span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </td>
            </tr>
          <?php } ?>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel') ?></button>
          <button type="button" id="choose_rooms" class="btn btn-primary"><?= $this->lang->line('text-choose_rooms') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="reservation-fill" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-reservation_fill') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-9 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="guest_id"><?= $this->lang->line('text-guests') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="guest_id" id="guest_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="float-right" for="status">Status</label>
                <select name="status" id="status" class="form-control custom-select" required>
                  <option value="Check-In"><?= $this->lang->line('text-checkin') ?></option>
                  <option value="Reservation"><?= $this->lang->line('text-reservation') ?></option>
                </select>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="btn-group btn-block">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-guests"><?= $this->lang->line('text-add_guests') ?></button>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-additional_costs_fill"><?= $this->lang->line('text-additional_costs') ?></button>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-4 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="segment_id"><?= $this->lang->line('text-segment') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="segment_id" id="segment_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="adult_guest"><?= $this->lang->line('text-adult_guest') ?></label>
                <div class="input-group">
                  <input type="number" name="adult_guest" id="adult_guest" class="form-control" placeholder="<?= $this->lang->line('text-adult_guest') ?>" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><?= $this->lang->line('text-people') ?></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="child_guest"><?= $this->lang->line('text-child_guest') ?></label>
                <div class="input-group">
                  <input type="number" name="child_guest" id="child_guest" class="form-control" placeholder="<?= $this->lang->line('text-child_guest') ?>" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><?= $this->lang->line('text-people') ?></span>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item checkin_support">
            <div class="row w-100 mx-0">
              <div class="col-md-6 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="payment_id"><?= $this->lang->line('text-payment') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="payment_id" id="payment_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="float-right" for="deposit"><?= $this->lang->line('text-deposit') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="deposit" class="form-control money_format" id="deposit" placeholder="<?= $this->lang->line('text-deposit') ?>">
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="float-right" for="checkin_time"><?= $this->lang->line('text-checkin_time') ?></label>
                <div class="input-group">
                  <input type="text" name="checkin_time" id="checkin_time" class="form-control clockpicker" placeholder="<?= $this->lang->line('text-checkin_time') ?>" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-clock"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item" id="in_house">
            <b><?= $this->lang->line('text-in_house') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel') ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="add-guests" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-add_guests') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-8 mb-2">
                <label class="float-right" for="guest_name"><?= $this->lang->line('field-guest_name') ?></label>
                <input type="text" name="guest_name" class="form-control" id="guest_name" placeholder="<?= $this->lang->line('field-guest_name') ?>">
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="national"><?= $this->lang->line('field-national') ?></label>
                <input type="text" name="national" class="form-control" id="national" placeholder="<?= $this->lang->line('field-national') ?>" value="Indonesia">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-3 mb-2">
                <label class="float-right" for="identity_type"><?= $this->lang->line('field-identity_type') ?></label>
                <select name="identity_type" id="identity_type" class="form-control custom-select">
                  <option>KTP</option>
                  <option>SIM</option>
                  <option>Pasport</option>
                </select>
              </div>
              <div class="col-md-5 mb-2">
                <label class="float-right" for="identity_number"><?= $this->lang->line('field-identity_number') ?></label>
                <input type="text" name="identity_number" class="form-control" id="identity_number" placeholder="<?= $this->lang->line('field-identity_number') ?>">
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="phone_number"><?= $this->lang->line('field-phone_number') ?></label>
                <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="<?= $this->lang->line('field-phone_number') ?>">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-5 mb-2">
                <label class="float-right" for="birth_date"><?= $this->lang->line('field-birth_date') ?></label>
                <div class="input-group">
                  <input type="text" name="birth_date" class="form-control datepicker" id="birth_date" placeholder="<?= $this->lang->line('field-birth_date') ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-7 mb-2">
                <label class="float-right" for="email"><?= $this->lang->line('field-email') ?></label>
                <input type="text" name="email" class="form-control" id="email" placeholder="<?= $this->lang->line('field-email') ?>">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-12">
                <label class="float-right" for="guest_address"><?= $this->lang->line('field-guest_address') ?></label>
                <textarea name="guest_address" class="form-control" id="guest_address" placeholder="<?= $this->lang->line('field-guest_address') ?>"></textarea>
              </div>
            </div>
          </li>
        </ul>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="add-additional_costs_fill" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-additional_costs') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-8 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="room_id"><?= $this->lang->line('text-rooms') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="room_id" id="room_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="additional_cost_type"><?= $this->lang->line('text-additional_cost_type') ?></label>
                <select name="additional_cost_type" id="additional_cost_type" class="form-control custom-select" required>
                  <option value="request"><?= $this->lang->line('text-request') ?></option>
                  <option value="discount"><?= $this->lang->line('text-discount') ?></option>
                </select>
              </div>
            </div>
          </li>
          <li class="list-group-item description">
            <div class="row w-100 mx-0">
              <div class="col-md-12 mb-2">
                <label class="float-right" for="additional_cost_description">Description</label>
                <textarea name="additional_cost_description" id="additional_cost_description" class="form-control" placeholder="Description"></textarea>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-12 price">
                <label class="float-right" for="additional_cost_price">Price</label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="additional_cost_price" class="form-control money_format text-right" id="additional_cost_price" placeholder="Price">
                </div>
              </div>
              <div class="col-md-12 d-none requests">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="request_id"><?= $this->lang->line('text-request'); ?></label>
                  </div>
                  <div class="col-12">
                    <select name="request_id" id="request_id" class="form-control custom-select select2">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- /.Modul - Reservation Data -->
<div class="modal fade" id="reservation-data" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-reservation_data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-3 mb-2">
                <select id="column_reservation_data" class="form-control custom-select">
                  <option value="1"><?= $this->lang->line('text-guests') ?></option>
                  <option value="2"><?= $this->lang->line('text-rooms') ?></option>
                  <option value="3"><?= $this->lang->line('text-in_house') ?></option>
                  <option value="0"><?= $this->lang->line('text-reservation_number') ?></option>
                </select>
              </div>
              <div class="col-md-9 mb-2">
                <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data') ?>" id="field_reservation_data">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table id="table_reservation_data" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('text-reservation_number') ?></th>
                    <th><?= $this->lang->line('text-guests') ?></th>
                    <th><?= $this->lang->line('text-rooms') ?></th>
                    <th><?= $this->lang->line('text-in_house') ?></th>
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

<div class="modal fade" id="reservation-detail" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-reservation_detail') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="guest_name">
            <b><?= $this->lang->line('text-guests') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_number">
            <b><?= $this->lang->line('text-reservation_number') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="segment">
            <b><?= $this->lang->line('text-segment') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="in_house">
            <b><?= $this->lang->line('text-in_house') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="total_guest">
            <b><?= $this->lang->line('text-total_guest') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="room_plan_name">
            <b><?= $this->lang->line('text-room_plan') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_time">
            <b><?= $this->lang->line('text-reservation_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-toggle="modal" data-target="#cancel-reservation"><?= $this->lang->line('text-cancel_reservation') ?></button>
          <button class="btn btn-info" data-toggle="modal" data-target="#add-additional_costs_data"><?= $this->lang->line('text-additional_costs') ?></button>
          <a href="#" class="btn btn-primary" target="_blank" id="print_folio"><?= $this->lang->line('text-print_folio') ?></a>
          <button class="btn btn-success" data-toggle="modal" data-target="#checkin-reservation"><?= $this->lang->line('text-checkin_reservation') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="cancel-reservation" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-cancel_reservation') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= $this->lang->line('modal-cancel_reservation'); ?></h4>
        <input type="hidden" id="reservation_id">
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button class="btn btn-outline-danger" id="save"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="checkin-reservation" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-checkin'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="checkin_date">
        <input type="hidden" id="reservation_id">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-7 mb-2">
                <label class="float-right" for="deposit"><?= $this->lang->line('text-deposit') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="deposit" class="form-control money_format" id="deposit" placeholder="<?= $this->lang->line('text-deposit') ?>">
                </div>
              </div>
              <div class="col-md-5 mb-2">
                <label class="float-right" for="checkin_time"><?= $this->lang->line('text-checkin_time') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-clock"></i></span>
                  </div>
                  <input type="text" name="checkin_time" id="checkin_time" class="form-control clockpicker" placeholder="<?= $this->lang->line('text-checkin_time') ?>" required>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="col-md-12">
              <div class="row">
                <div class="col-12">
                  <label class="float-right" for="payment_id"><?= $this->lang->line('text-payment') ?></label>
                </div>
                <div class="col-12">
                  <select name="payment_id" id="payment_id" class="form-control custom-select select2" required>
                  </select>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="add-additional_costs_data" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-additional_costs') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-8 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="room_id"><?= $this->lang->line('text-rooms') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="room_id" id="room_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="additional_cost_type"><?= $this->lang->line('text-additional_cost_type') ?></label>
                <select name="additional_cost_type" id="additional_cost_type" class="form-control custom-select" required>
                  <option value="request"><?= $this->lang->line('text-request') ?></option>
                  <option value="discount"><?= $this->lang->line('text-discount') ?></option>
                </select>
              </div>
            </div>
          </li>
          <li class="list-group-item description">
            <div class="row w-100 mx-0">
              <div class="col-md-12 mb-2">
                <label class="float-right" for="additional_cost_description">Description</label>
                <textarea name="additional_cost_description" id="additional_cost_description" class="form-control" placeholder="Description"></textarea>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-12 price">
                <label class="float-right" for="additional_cost_price">Price</label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="additional_cost_price" class="form-control money_format text-right" id="additional_cost_price" placeholder="Price">
                </div>
              </div>
              <div class="col-md-12 d-none requests">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="request_id"><?= $this->lang->line('text-request'); ?></label>
                  </div>
                  <div class="col-12">
                    <select name="request_id" id="request_id" class="form-control custom-select select2">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="delete-additional_costs_data" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-delete_additional_costs') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= $this->lang->line('modal-delete_additional_costs'); ?></h4>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="additional_cost_id">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button class="btn btn-outline-danger" id="save"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- /.Modul - Check-In Data -->
<div class="modal fade" id="checkin-data" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-checkin_data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-3 mb-2">
                <select id="column_checkin_data" class="form-control custom-select">
                  <option value="1"><?= $this->lang->line('text-guests') ?></option>
                  <option value="2"><?= $this->lang->line('text-rooms') ?></option>
                  <option value="3"><?= $this->lang->line('text-in_house') ?></option>
                  <option value="0"><?= $this->lang->line('text-reservation_number') ?></option>
                </select>
              </div>
              <div class="col-md-9 mb-2">
                <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data') ?>" id="field_checkin_data">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table id="table_checkin_data" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('text-reservation_number') ?></th>
                    <th><?= $this->lang->line('text-guests') ?></th>
                    <th><?= $this->lang->line('text-rooms') ?></th>
                    <th><?= $this->lang->line('text-in_house') ?></th>
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

<div class="modal fade" id="checkin-detail" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-checkin_detail') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="guest_name">
            <b><?= $this->lang->line('text-guests') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_number">
            <b><?= $this->lang->line('text-reservation_number') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="segment">
            <b><?= $this->lang->line('text-segment') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="in_house">
            <b><?= $this->lang->line('text-in_house') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="total_guest">
            <b><?= $this->lang->line('text-total_guest') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="room_plan_name">
            <b><?= $this->lang->line('text-room_plan') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="checkin_time">
            <b><?= $this->lang->line('text-checkin_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_time">
            <b><?= $this->lang->line('text-reservation_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="btn-group btn-block">
              <button class="btn btn-primary" data-toggle="modal" data-target="#add-payment"><?= $this->lang->line('text-add_payment') ?></button>
              <button class="btn btn-warning" data-toggle="modal" data-target="#extend-days"><?= $this->lang->line('text-extend_days') ?></button>
              <button class="btn btn-info" data-toggle="modal" data-target="#change-rooms"><?= $this->lang->line('text-change_rooms') ?></button>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="4"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-info" data-toggle="modal" data-target="#add-additional_costs_checkin"><?= $this->lang->line('text-additional_costs') ?></button>
          <a href="#" class="btn btn-primary" target="_blank" id="print_folio"><?= $this->lang->line('text-print_folio') ?></a>
          <button class="btn btn-success" data-toggle="modal" data-target="#checkout-reservation"><?= $this->lang->line('text-checkout_reservation') ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="checkout-reservation" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-checkout'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deposit">
        <input type="hidden" id="remaining">
        <input type="hidden" id="checkout_date">
        <input type="hidden" id="reservation_id">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-7 mb-2">
                <label class="float-right" for="remaining_payment"><?= $this->lang->line('text-remaining_payment') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="remaining_payment" class="form-control money_format" id="remaining_payment" placeholder="<?= $this->lang->line('text-remaining_payment') ?>">
                </div>
              </div>
              <div class="col-md-5 mb-2">
                <label class="float-right" for="checkout_time"><?= $this->lang->line('text-checkout_time') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-clock"></i></span>
                  </div>
                  <input type="text" name="checkout_time" id="checkout_time" class="form-control clockpicker" placeholder="<?= $this->lang->line('text-checkout_time') ?>" required>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-9 mb-2 mb-md-0">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="payment_id"><?= $this->lang->line('text-payment') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="payment_id" id="payment_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <label class="float-right" for="receipt_type"><?= $this->lang->line('text-receipt') ?></label>
                <select name="receipt_type" id="receipt_type" class="form-control custom-select" required>
                  <option value="bill"><?= $this->lang->line('text-bill') ?></option>
                  <option value="invoice"><?= $this->lang->line('text-invoice') ?></option>
                </select>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="change-rooms" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-change_rooms') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered m-0">
          <?php foreach ($floors as $result) { ?>
            <tr>
              <th class="text-center"><?= $result['floor_name']; ?></th>
            </tr>
            <tr>
              <td>
                <div class="row">
                  <?php foreach ($result['rooms'] as $room) { ?>
                    <div class="col-4 col-md-2 d-flex align-items-center">
                      <div class="small-box bg-primary w-100" room_id="<?= $room['room_id']; ?>">
                        <div class="inner text-light text-center">
                          <h3><?= $room['room_number']; ?></h3>
                          <p class="m-0"><?= $room['room_type_name']; ?></p>
                          <span class="font-italic"></span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="check-rooms-change" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-check_change_rooms') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="extend_days">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="extend-days" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-extend_days') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-12">
                <label class="float-right" for="extend_days"><?= $this->lang->line('text-extend_days') ?></label>
                <div class="input-group">
                  <input type="text" name="extend_days" class="form-control datepicker" id="extend_days" placeholder="<?= $this->lang->line('text-extend_days') ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="reservation_id">
          <input type="hidden" id="checkout_schedule">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="check_price" class="btn btn-primary"><?= $this->lang->line('text-extend'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="check-extend-days" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-check_extend_days') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="extend_days">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="add-payment" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-add_payment') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-5 mb-2">
                <label class="float-right" for="payment_date"><?= $this->lang->line('text-payment_date') ?></label>
                <div class="input-group">
                  <input type="text" name="payment_date" class="form-control datepicker" id="payment_date" placeholder="<?= $this->lang->line('text-payment_date') ?>">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-7 mb-2">
                <label class="float-right" for="total_payment"><?= $this->lang->line('text-total_payment') ?></label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="total_payment" class="form-control money_format text-right" id="total_payment" placeholder="<?= $this->lang->line('text-total_payment') ?>">
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="col-md-12 mb-2">
              <div class="row">
                <div class="col-12">
                  <label class="float-right" for="payment_id"><?= $this->lang->line('text-payment') ?></label>
                </div>
                <div class="col-12">
                  <select name="payment_id" id="payment_id" class="form-control custom-select select2" required>
                  </select>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item description">
            <div class="row w-100 mx-0">
              <div class="col-md-12">
                <label class="float-right" for="payment_description"><?= $this->lang->line('text-payment_description') ?></label>
                <textarea name="payment_description" id="payment_description" class="form-control" placeholder="<?= $this->lang->line('text-payment_description') ?>"></textarea>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="add-additional_costs_checkin" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-additional_costs') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-8 mb-2">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="room_id"><?= $this->lang->line('text-rooms') ?></label>
                  </div>
                  <div class="col-12">
                    <select name="room_id" id="room_id" class="form-control custom-select select2" required>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label class="float-right" for="additional_cost_type"><?= $this->lang->line('text-additional_cost_type') ?></label>
                <select name="additional_cost_type" id="additional_cost_type" class="form-control custom-select" required>
                  <option value="request"><?= $this->lang->line('text-request') ?></option>
                  <option value="discount"><?= $this->lang->line('text-discount') ?></option>
                  <option value="loss or damage"><?= $this->lang->line('text-loss_or_damage') ?></option>
                </select>
              </div>
            </div>
          </li>
          <li class="list-group-item description">
            <div class="row w-100 mx-0">
              <div class="col-md-12 mb-2">
                <label class="float-right" for="additional_cost_description">Description</label>
                <textarea name="additional_cost_description" id="additional_cost_description" class="form-control" placeholder="Description"></textarea>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row w-100 mx-0">
              <div class="col-md-12 price">
                <label class="float-right" for="additional_cost_price">Price</label>
                <div class="input-group">
                  <div class="input-group-append">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" name="additional_cost_price" class="form-control money_format text-right" id="additional_cost_price" placeholder="Price">
                </div>
              </div>
              <div class="col-md-12 d-none requests">
                <div class="row">
                  <div class="col-12">
                    <label class="float-right" for="request_id"><?= $this->lang->line('text-request'); ?></label>
                  </div>
                  <div class="col-12">
                    <select name="request_id" id="request_id" class="form-control custom-select select2">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-danger" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button type="button" id="save" class="btn btn-primary"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="delete-additional_costs_checkin" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-delete_additional_costs') ?> xxx</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="modal-title"><?= $this->lang->line('modal-delete_additional_costs'); ?></h4>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-block">
          <input type="hidden" id="additional_cost_id">
          <input type="hidden" id="reservation_id">
          <button class="btn btn-outline-success" data-dismiss="modal"><?= $this->lang->line('text-cancel'); ?></button>
          <button class="btn btn-outline-danger" id="save"><?= $this->lang->line('text-save'); ?></button>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->






<!-- /.Modul - Guest History Data -->
<div class="modal fade" id="guest-data" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-guest_data') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-3 mb-2">
                <select id="column_guest_data" class="form-control custom-select">
                  <option value="1"><?= $this->lang->line('text-guests') ?></option>
                  <option value="2"><?= $this->lang->line('text-rooms') ?></option>
                  <option value="3"><?= $this->lang->line('text-in_house') ?></option>
                  <option value="0"><?= $this->lang->line('text-reservation_number') ?></option>
                </select>
              </div>
              <div class="col-md-9 mb-2">
                <input type="text" class="form-control" placeholder="<?= $this->lang->line('text-search_data') ?>" id="field_guest_data">
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table id="table_guest_data" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?= $this->lang->line('text-reservation_number') ?></th>
                    <th><?= $this->lang->line('text-guests') ?></th>
                    <th><?= $this->lang->line('text-rooms') ?></th>
                    <th><?= $this->lang->line('text-in_house') ?></th>
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

<div class="modal fade" id="guest-detail" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="overlay d-flex justify-content-center align-items-center invisible">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title"><?= $this->lang->line('text-guest_detail') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item" id="guest_name">
            <b><?= $this->lang->line('text-guests') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_number">
            <b><?= $this->lang->line('text-reservation_number') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_status">
            <b><?= $this->lang->line('text-reservation_status') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="segment">
            <b><?= $this->lang->line('text-segment') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="in_house">
            <b><?= $this->lang->line('text-in_house') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="total_guest">
            <b><?= $this->lang->line('text-total_guest') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="room_plan_name">
            <b><?= $this->lang->line('text-room_plan') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="checkin_time">
            <b><?= $this->lang->line('text-checkin_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="checkout_time">
            <b><?= $this->lang->line('text-checkout_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item" id="reservation_time">
            <b><?= $this->lang->line('text-reservation_time') ?></b><span class="float-right">x</span>
          </li>
          <li class="list-group-item">
            <div class="btn-group btn-block">
              <a href="#" class="btn btn-primary" target="_blank" id="print_folio"><?= $this->lang->line('text-print_folio') ?></a>
              <a href="#" class="btn btn-success" target="_blank" id="print_billinvoice"><?= $this->lang->line('text-print_billinvoice') ?></a>
            </div>
          </li>
          <li class="list-group-item">
            <div class="table-responsive">
              <table class="table table-bordered" style="margin: 0; min-width: 700px; width:100%;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('text-reservation_description') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-price') ?></th>
                    <th class='text-center' style="width: 90px;"><?= $this->lang->line('text-qty') ?></th>
                    <th class='text-center' style="width: 150px;"><?= $this->lang->line('text-total') ?></th>
                    <th class='text-center' style="width: 10px;"><?= $this->lang->line('text-act') ?></th>
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