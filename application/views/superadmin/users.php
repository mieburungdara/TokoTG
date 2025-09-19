<h2><?php echo $title; ?></h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Telegram ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['telegram_id']; ?></td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo isset($user['status']) ? $user['status'] : 'N/A'; ?></td>
                    <td>
                        <a href="<?php echo base_url('superadmin/edit_user/' . $user['id']); ?>" class="btn btn-warning">Edit</a>
                        <?php if (isset($user['status']) && $user['status'] === 'blocked'): ?>
                            <a href="<?php echo base_url('superadmin/unblock_user/' . $user['id']); ?>" class="btn">Unblock</a>
                        <?php else: ?>
                            <a href="<?php echo base_url('superadmin/block_user/' . $user['id']); ?>" class="btn btn-danger">Block</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
