<?php
/**
 * head_end.php
 *
 * Author: pixelcave
 *
 * The a block of code used in every page of the template
 *
 */
?>

<script src="https://telegram.org/js/telegram-web-app.js"></script>
<style>
        :root {
            color-scheme: light dark;
            --bg-color: var(--tg-theme-bg-color, #ffffff);
            --text-color: var(--tg-theme-text-color, #222222);
            --hint-color: var(--tg-theme-hint-color, #999999);
            --link-color: var(--tg-theme-link-color, #2481cc);
            --button-color: var(--tg-theme-button-color, #2481cc);
            --button-text-color: var(--tg-theme-button-text-color, #ffffff);
            --secondary-bg-color: var(--tg-theme-secondary-bg-color, #f1f1f1);
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 95vh;
            text-align: center;
        }
        .hidden {
            display: none;
        }
        #loader {
            border: 4px solid var(--secondary-bg-color);
            border-top: 4px solid var(--button-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .profile-card {
            background-color: var(--secondary-bg-color);
            border-radius: 12px;
            padding: 25px;
            width: 85%;
            max-width: 320px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--bg-color);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            margin-bottom: 15px;
        }
        .user-name {
            font-size: 1.4em;
            font-weight: 600;
            margin: 0;
        }
        .user-username {
            font-size: 1em;
            color: var(--hint-color);
            margin: 4px 0 0 0;
        }
        #error-message {
            color: var(--tg-theme-destructive-text-color, #ff4444);
        }

        #header {
            width: 100%;
            background-color: var(--secondary-bg-color);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-sizing: border-box;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        #header h1 {
            margin: 0;
            font-size: 1.2em;
            margin-left: 20px;
        }

        #menu-icon {
            font-size: 1.5em;
            cursor: pointer;
        }

        #sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 200;
            top: 0;
            left: -250px;
            background-color: var(--secondary-bg-color);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        #sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1.1em;
            color: var(--text-color);
            display: block;
            transition: 0.3s;
        }

        #sidebar a:hover {
            background-color: var(--bg-color);
        }

        #main-content {
            margin-top: 80px;
        }

        body.sidebar-open #sidebar {
            left: 0;
        }

        body.sidebar-open #main-content {
            padding-left: 250px;
        }
    </style>
<!-- Dashmix JS -->
<script src="<?php echo base_url($dm->assets_folder . '/js/dashmix.app.min.js'); ?>"></script>
</head>
<body>