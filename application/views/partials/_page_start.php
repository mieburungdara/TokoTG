    <div id="page-container"<?php $dm->page_classes(); ?>>
      <?php if ($dm->inc_side_overlay) { $this->load->view('partials/side_overlay'); } ?>
      <?php if ($dm->inc_sidebar) { $this->load->view('partials/sidebar'); } ?>
      <?php if ($dm->inc_header) { $this->load->view('partials/header'); } ?>

      <!-- Main Container -->
      <main id="main-container">