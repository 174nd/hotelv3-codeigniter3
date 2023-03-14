<!DOCTYPE html>
<html>

<head>
  <?php $this->view('templates/header'); ?>
</head>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light">
      <div class="container">

        <a href="<?= base_url('frontoffice'); ?>" class="navbar-brand">
          <img src="<?= base_url('dist/img/logo.png'); ?>" alt="Logo" class="brand-image" style="opacity: 0.8;" />
          <span class="brand-text font-weight-light">Nagoya Plasa Hotel</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="<?= base_url('fo_guests'); ?>" class="nav-link">Guests</a>
            </li>
          </ul>
        </div>

        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <img src="<?= base_url($this->session->userdata('user_photo') != '' && file_exists(FCPATH . 'uploads/users/' . $this->session->userdata('user_photo')) ? 'uploads/users/' . rawurlencode($this->session->userdata('user_photo')) : 'dist/img/user.png') ?>" class="user-image" alt="User Image" />
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <!-- User image -->
              <li class="user-header bg-primary">
                <img src="<?= base_url($this->session->userdata('user_photo') != '' && file_exists(FCPATH . 'uploads/users/' . $this->session->userdata('user_photo')) ? 'uploads/users/' . rawurlencode($this->session->userdata('user_photo')) : 'dist/img/user.png') ?>" class="img-circle elevation-2" alt="User Image" />
                <p>
                  <?= $this->session->userdata('user_fullname'); ?>
                  <small>Front-Office</small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-12 btn-group btn-block">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#update-photo"><?= $this->lang->line('text-upload_photo'); ?></button>
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#update-password"><?= $this->lang->line('text-change_password'); ?></button>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <a href="<?= base_url('auth/logout'); ?>" class="btn btn-default btn-flat float-right">Keluar</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?= $set['content']; ?></h1>
              </div>
              <!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <?php
                  if (isset($set['breadcrumb'])) {
                    foreach ($set['breadcrumb'] as $key => $value) {
                      if ($value != '#' && $value != "active") {
                        echo "<li class=\"breadcrumb-item\"><a href=\"$value\">$key</a></li>";
                      } else if ($value == "active") {
                        echo "<li class=\"breadcrumb-item active\">$key</li>";
                      } else {
                        echo "<li class=\"breadcrumb-item\">$key</li>";
                      }
                    }
                  }
                  ?>
                </ol>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
          <?= $this->session->flashdata('message'); ?>
        </div>
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container">
          <?= $content; ?>
        </div><!-- /.container -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <div class="container">
      <footer class="main-footer bg-light">
        <?php $this->view('templates/footer'); ?>
      </footer>
    </div>
  </div>
  <!-- ./wrapper -->


  <div class="modal fade" id="update-password">
    <form method="POST" action="<?= base_url('admin'); ?>" class="modal-dialog">
      <div class="modal-content bg-secondary">
        <div class="modal-header">
          <h4 class="modal-title"><?= $this->lang->line('text-change_password'); ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" style="border-bottom: 1px solid #e9ecef;">
            <div class="input-group">
              <div class="row w-100 ml-0 mr-0">
                <div class="col-md-12 mb-3">
                  <label class="float-right" for="old_pass"><?= $this->lang->line('text-old_password'); ?></label>
                  <input type="password" name="old_pass" class="form-control" id="old_pass" placeholder="<?= $this->lang->line('text-old_password'); ?>">
                </div>
              </div>
            </div>
          </div>
          <div class="form-group m-0" style="border-bottom: 1px solid #e9ecef;">
            <div class="input-group">
              <div class="row w-100 ml-0 mr-0">
                <div class="col-md-6 mb-3">
                  <label class="float-right" for="new_pass1"><?= $this->lang->line('text-new_password'); ?></label>
                  <input type="password" name="new_pass1" class="form-control" id="new_pass1" placeholder="<?= $this->lang->line('text-new_password'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="float-right" for="new_pass2"><?= $this->lang->line('text-confirm_password'); ?></label>
                  <input type="password" name="new_pass2" class="form-control" id="new_pass2" placeholder="<?= $this->lang->line('text-confirm_password'); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row w-100 ml-0 mr-0">
            <div class="col-md-12">
              <input type="submit" name="u-password" class="btn btn-outline-light btn-block" value="<?= $this->lang->line('text-save'); ?>">
            </div>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </form>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="update-photo">
    <form method="POST" action="<?= base_url('admin'); ?>" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?= $this->lang->line('text-upload_photo'); ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <div class="custom-file">
              <input type="file" name="user_photo" class="custom-file-input" id="user_photo">
              <label class="custom-file-label" for="user_photo"><?= $this->lang->line('text-input_file'); ?></label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row w-100 ml-0 mr-0">
            <div class="col-md-12">
              <input type="submit" name="u-foto" class="btn btn-outline-primary btn-block" value="<?= $this->lang->line('text-save'); ?>">
            </div>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </form>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <?php $this->view('templates/script'); ?>
</body>

</html>