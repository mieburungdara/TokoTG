<h2><?php echo $title; ?></h2>

<form action="<?php echo base_url('superadmin/edit_user/' . $user['id']); ?>" method="post">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">

    <label for="role">Role:</label>
    <select id="role" name="role">
        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
        <option value="superadmin" <?php echo ($user['role'] === 'superadmin') ? 'selected' : ''; ?>>Superadmin</option>
    </select>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="active" <?php echo (isset($user['status']) && $user['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
        <option value="blocked" <?php echo (isset($user['status']) && $user['status'] === 'blocked') ? 'selected' : ''; ?>>Blocked</option>
    </select>

    <button type="submit">Update User</button>
</form>
