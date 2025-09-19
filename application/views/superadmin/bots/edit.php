<?php $this->load->view('superadmin/templates/header', ['title' => 'Superadmin - Edit Bot']); ?>

    <div class="form-container">
        <h2 class="mb-4 text-center">Edit Bot</h2>
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo validation_errors(); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo site_url('superadmin/bots/edit/' . $bot['id']); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Bot Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', $bot['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="api_key" class="form-label">Bot API Key:</label>
                <input type="text" class="form-control" id="api_key" name="api_key" value="<?php echo set_value('api_key', $bot['api_key']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="mode" class="form-label">Mode:</label>
                <select class="form-control" id="mode" name="mode">
                    <option value="webhook" <?php echo set_select('mode', 'webhook', ($bot['mode'] == 'webhook')); ?>>Webhook</option>
                    <option value="longpolling" <?php echo set_select('mode', 'longpolling', ($bot['mode'] == 'longpolling')); ?>>Long Polling</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Bot</button>
        </form>
        <a href="<?php echo site_url('superadmin/bots'); ?>" class="btn btn-link mt-3">Back to Bot List</a>
    </div>

<?php $this->load->view('superadmin/templates/footer'); ?>
