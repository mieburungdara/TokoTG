<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Superadmin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 90%; margin: auto; overflow: hidden; padding: 20px 0; }
        header { background: #333; color: #fff; padding-top: 20px; min-height: 70px; border-bottom: #77aaff 3px solid; }
        header a { color: #fff; text-decoration: none; text-transform: uppercase; font-size: 16px; }
        header ul { padding: 0; list-style: none; }
        header li { display: inline; padding: 0 20px; }
        header #branding { float: left; }
        header #branding h1 { margin: 0; }
        header nav { float: right; margin-top: 10px; }
        header .highlight, header .current a { color: #77aaff; font-weight: bold; }
        header a:hover { color: #77aaff; font-weight: bold; }
        .main-content { padding: 20px; background: #fff; margin-top: 20px; border-radius: 5px; }
        .message { padding: 10px; margin-bottom: 10px; border-radius: 3px; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; color: #fff; background-color: #007bff; padding: 8px 15px; border-radius: 5px; text-decoration: none; margin-right: 5px; }
        .btn-danger { background-color: #dc3545; }
        .btn-warning { background-color: #ffc107; color: #333; }
        form { margin-top: 20px; }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form input[type="text"], form input[type="email"], form select { width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ddd; }
        form button { background-color: #28a745; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        form button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">Superadmin</span> Panel</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo base_url('superadmin'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('superadmin/users'); ?>">Users</a></li>
                    <li><a href="<?php echo base_url('superadmin/settings'); ?>">Settings</a></li>
                    <li><a href="<?php echo base_url('superadmin/logs'); ?>">Logs</a></li>
                    <li><a href="<?php echo base_url('auth/logout'); ?>">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="message success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="message error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <div class="main-content">
