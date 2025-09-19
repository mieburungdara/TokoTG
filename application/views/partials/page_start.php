<?php
/**
 * page_start.php
 *
 * Author: pixelcave
 *
 * The block of code used starting the page in every page of the template
 *
 */
?>
<!-- Page Container -->
<!--
  Available classes for #page-container:

  GENERIC

    'remember-sidebar-position'                       Remembers active horizontal sidebar position and restores it when you navigate to other pages
    'sidebar-light'                                   Light Sidebar
    'sidebar-dark'                                    Dark Sidebar
    'page-header-light'                               Light Page Header
    'page-header-dark'                                Dark Page Header
    'main-content-boxed'                              Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                             Full width Main Content with a percentage width (screen width > 1200px)

  SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                       Right Sidebar and left Side Overlay
    'sidebar-mini'                                    Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                       Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                                    Visible Sidebar by default (screen width < 992px)
    'sidebar-o-lg'                                    Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xl'                                    Visible Sidebar by default (screen width > 1199px)
    'sidebar-o-xxl'                                   Visible Sidebar by default (screen width > 1399px)
    'sidebar-push'                                    Push Sidebar (screen width > 991px)
    'sidebar-overlap'                                 Overlap Sidebar (screen width > 991px)

    'side-overlay-hover'                              Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                                  Visible Side Overlay by default
    'side-overlay-push'                               Push Side Overlay
    'side-overlay-overlap'                            Overlap Side Overlay

  HEADER

    'page-header-fixed'                               Fixed Header

  FOOTER

    'page-footer-fixed'                               Fixed Footer
    'page-footer-glass'                               Light Footer with a glass effect
    'page-footer-dark'                                Dark Footer

  LAYOUT

    'rtl-support'                                     Enable RTL support

  PAGE TRANSITIONS (used with [data-toggle="page-transition"])

    'page-transition-none'                            No transition
    'page-transition-opacity'                         Opacity transition
    'page-transition-fade'                            Fade transition
    'page-transition-fade-up'                         Fade Up transition
    'page-transition-fade-down'                       Fade Down transition
    'page-transition-fade-left'                       Fade Left transition
    'page-transition-fade-right'                      Fade Right transition
    'page-transition-slide-up'                        Slide Up transition
    'page-transition-slide-down'                      Slide Down transition
    'page-transition-slide-left'                      Slide Left transition
    'page-transition-slide-right'                     Slide Right transition
    'page-transition-zoom-in'                         Zoom In transition
    'page-transition-zoom-out'                        Zoom Out transition
    'page-transition-flip-x'                          Flip X transition
    'page-transition-flip-y'                          Flip Y transition

  MAIN CONTENT ANIMATIONS (when add to #main-container)

    'main-content-fade'                               Fade in
    'main-content-slide-up'                           Slide up
    'main-content-slide-down'                         Slide down
    'main-content-slide-left'                         Slide left
    'main-content-slide-right'                        Slide right
    'main-content-zoom-in'                            Zoom in
    'main-content-zoom-out'                           Zoom out
    'main-content-flip-x'                             Flip x
    'main-content-flip-y'                             Flip y
-->
<div id="page-container" class="<?php $dm->page_classes(); ?>">
<?php
// Page Loader
// If you would like to override the default loader, you can use the following markup:
//
// <div id="page-loader" class="show"></div>
//
$dm->page_loader();
?>

  <?php if(isset($dm->inc_side_overlay) && $dm->inc_side_overlay) { $this->load->view($dm->inc_side_overlay, array('dm' => $dm)); } ?>
  
  <nav id="sidebar" aria-label="Main Navigation">
    <?php if(isset($dm->inc_sidebar) && $dm->inc_sidebar) { $this->load->view($dm->inc_sidebar, array('dm' => $dm)); } ?>
  </nav>

  <?php if(isset($dm->inc_header) && $dm->inc_header) { $this->load->view($dm->inc_header, array('dm' => $dm)); } ?>

  <!-- Main Container -->
  <main id="main-container">