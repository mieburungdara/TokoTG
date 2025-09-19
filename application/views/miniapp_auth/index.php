<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenticating...</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: sans-serif; background-color: #222; color: white; }
    </style>
</head>
<body>
    <p>Please wait, authenticating...</p>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Telegram.WebApp.ready();

            const initData = Telegram.WebApp.initData;

            const botId = <?php echo json_encode($bot_id); ?>;

            if (!initData || !botId) {
                window.location.href = '<?php echo site_url("miniapp_auth/invalid_access"); ?>';
                return;
            }

            fetch('<?php echo site_url("auth/telegram_login"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ initData: initData, bot_id: botId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '<?php echo site_url("miniapp_auth/dashboard"); ?>';
                } else {
                    // The data is invalid or another error occurred.
                    window.location.href = '<?php echo site_url("miniapp_auth/invalid_access"); ?>';
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                window.location.href = '<?php echo site_url("miniapp_auth/invalid_access"); ?>';
            });
        });
    </script>
</body>
</html>
