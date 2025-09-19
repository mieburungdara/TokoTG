    <?php $dm->get_js('js/dashmix.app.min.js'); ?>

    <?php
    // Any extra JS files to include
    if (isset($js_files) && is_array($js_files)) {
        foreach ($js_files as $js_file) {
            $dm->get_js($js_file);
        }
    }
    ?>
  </body>
</html>