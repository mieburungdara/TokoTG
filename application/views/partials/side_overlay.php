<?php
/**
 * partials/side_overlay.php
 *
 * The side overlay of each page (Backend pages)
 * Adapted for CodeIgniter
 */
?>
<!-- Side Overlay-->
<aside id="side-overlay">
  <!-- Side Header -->
  <div class="bg-image" style="background-image: url('<?php echo base_url($dm->assets_folder . '/media/various/bg_side_overlay_header.jpg'); ?>');">
    <div class="bg-primary-op">
      <div class="content-header">
        <!-- User Avatar -->
        <a class="img-link me-1" href="#">
          <?php $dm->get_avatar(10, '', 48); ?>
        </a>
        <!-- END User Avatar -->

        <!-- User Info -->
        <div class="ms-2">
          <a class="text-white fw-semibold" href="#">Super Admin</a>
          <div class="text-white-75 fs-sm">System Administrator</div>
        </div>
        <!-- END User Info -->

        <!-- Close Side Overlay -->
        <a class="ms-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
          <i class="fa fa-times-circle"></i>
        </a>
        <!-- END Close Side Overlay -->
      </div>
    </div>
  </div>
  <!-- END Side Header -->

  <!-- Side Content -->
  <div class="content-side">
    <p>Side Overlay Content..</p>
  </div>
  <!-- END Side Content -->
</aside>
<!-- END Side Overlay -->