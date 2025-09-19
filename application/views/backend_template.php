<?php
/**
 * backend_template.php
 *
 * This is the main orchestrator file for the backend template.
 * It loads the partials in the correct order to build the final page.
 *
 */

// Load partials in order
$this->load->view('partials/_head_start');
$this->load->view('partials/_head_end');
$this->load->view('partials/_page_start');

// Main page content is echoed here
echo $main_content;

// Load footer partials in the correct order
$this->load->view('partials/_page_end');

if ($dm->inc_footer) {
  $this->load->view('partials/footer');
}

$this->load->view('partials/_footer_end');