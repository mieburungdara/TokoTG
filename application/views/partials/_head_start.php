<?php
/**
 * _head_start.php
 *
 * Author: pixelcave
 * 
 * The first part of the head section
 *
 */
?>
<!doctype html>
<html lang="en"<?php $dm->html_classes(); ?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title><?php echo $dm->title; ?></title>

    <meta name="description" content="<?php echo $dm->description; ?>">
    <meta name="author" content="<?php echo $dm->author; ?>">
    <meta name="robots" content="<?php echo $dm->robots; ?>">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="<?php echo $dm->title; ?>">
    <meta property="og:site_name" content="<?php echo $dm->og_url_site; ?>">
    <meta property="og:description" content="<?php echo $dm->description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $dm->og_url_site; ?>">
    <meta property="og:image" content="<?php echo $dm->og_url_image; ?>">

    <!-- Icons -->
    <link rel="shortcut icon" href="<?php echo base_url($dm->assets_folder . '/media/favicons/favicon.png'); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url($dm->assets_folder . '/media/favicons/favicon-192x192.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url($dm->assets_folder . '/media/favicons/apple-touch-icon-180x180.png'); ?>">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <link rel="stylesheet" id="css-main" href="<?php echo base_url($dm->assets_folder . '/css/dashmix.min.css'); ?>">

    <?php
    // Any extra CSS stylesheets to include
    if (isset($css_files) && is_array($css_files)) {
        foreach ($css_files as $css_file) {
            $dm->get_css($css_file);
        }
    }
    ?>
    <!-- END Stylesheets -->