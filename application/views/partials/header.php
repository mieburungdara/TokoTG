<?php
/**
 * partials/header.php
 *
 * The header of each page (Backend pages)
 * Adapted for CodeIgniter
 */
?>

<!-- Header -->
<header id="page-header">
  <!-- Header Content -->
  <div class="content-header">
    <!-- Left Section -->
    <div class="space-x-1">
      <!-- Toggle Sidebar -->
      <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
        <i class="fa fa-fw fa-bars"></i>
      </button>
      <!-- END Toggle Sidebar -->

      <!-- Open Search Section -->
      <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="header_search_on">
        <i class="fa fa-fw opacity-50 fa-search"></i> <span class="ms-1 d-none d-sm-inline-block">Search</span>
      </button>
      <!-- END Open Search Section -->
    </div>
    <!-- END Left Section -->

    <!-- Right Section -->
    <div class="space-x-1">
      <!-- User Dropdown -->
      <div class="dropdown d-inline-block">
        <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-fw fa-user d-sm-none"></i>
          <span class="d-none d-sm-inline-block">Admin</span>
          <i class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
          <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
            User Options
          </div>
          <div class="p-2">
            <a class="dropdown-item" href="#">
              <i class="far fa-fw fa-user me-1"></i> Profile
            </a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo site_url('superadmin_login/logout'); ?>">
              <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Sign Out
            </a>
          </div>
        </div>
      </div>
      <!-- END User Dropdown -->

      <!-- Toggle Side Overlay -->
      <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="side_overlay_toggle">
        <i class="far fa-fw fa-list-alt"></i>
      </button>
      <!-- END Toggle Side Overlay -->
    </div>
    <!-- END Right Section -->
  </div>
  <!-- END Header Content -->

  <!-- Header Search -->
  <div id="page-header-search" class="overlay-header bg-header-dark">
    <div class="bg-white-10">
      <div class="content-header">
        <form class="w-100" action="#" method="POST">
          <div class="input-group">
            <button type="button" class="btn btn-alt-primary" data-toggle="layout" data-action="header_search_off">
              <i class="fa fa-fw fa-times-circle"></i>
            </button>
            <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END Header Search -->

  <!-- Header Loader -->
  <div id="page-header-loader" class="overlay-header bg-header-dark">
    <div class="bg-white-10">
      <div class="content-header">
        <div class="w-100 text-center">
          <i class="fa fa-fw fa-sun fa-spin text-white"></i>
        </div>
      </div>
    </div>
  </div>
  <!-- END Header Loader -->
</header>
<!-- END Header -->