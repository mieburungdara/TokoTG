<?php $this->load->view('superadmin/templates/header', ['title' => 'Superadmin Dashboard']); ?>

        <h2 class="mb-4 text-center">Welcome, Superadmin!</h2>
        <p class="text-center">You are logged in as <strong><?php echo $this->session->userdata('superadmin_username'); ?></strong></p>

        <div class="d-grid gap-2 col-6 mx-auto mt-4">
            <a href="<?php echo site_url('superadmin/bots'); ?>" class="btn btn-primary btn-lg">Manage Bots</a>
            <a href="<?php echo site_url('superadmin/user'); ?>" class="btn btn-info btn-lg">Manage Users</a>
            <!-- Add more dashboard links here -->
        </div>

<?php $this->load->view('superadmin/templates/footer'); ?>
