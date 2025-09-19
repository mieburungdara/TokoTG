<?php $this->load->view('superadmin/templates/header', ['title' => 'Superadmin - Manage Users']); ?>

<h2 class="mb-4 text-center">Manage Telegram Users</h2>

<div class="d-flex justify-content-end align-items-center mb-3">
    <a href="<?php echo site_url('superadmin/dashboard'); ?>" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php if ($this->session->flashdata('success')):
 ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')):
 ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($users)):
 ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Telegram ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Chat ID</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user):
 ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['telegram_id']; ?></td>
                        <td><?php echo $user['first_name']; ?></td>
                        <td><?php echo $user['last_name']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['chat_id']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else:
 ?>
    <p class="text-center">No users found.</p>
<?php endif; ?>

<?php $this->load->view('superadmin/templates/footer'); ?>
