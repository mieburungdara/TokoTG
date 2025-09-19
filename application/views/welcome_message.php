<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>TokoTG</title>
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
    </style>
</head>
<body>

    <div id="loader"></div>

    <div id="main-content" class="hidden">
        <div class="profile-card">
            <img id="user-avatar" src="" alt="User Avatar" class="avatar">
            <h2 id="user-name" class="user-name"></h2>
            <p id="user-username" class="user-username"></p>
        </div>
    </div>

    <div id="error-message" class="hidden">
        <h3>Authentication Failed</h3>
        <p>Please try again or contact support.</p>
    </div>
    
    <div id="not-in-telegram" class="hidden">
        <h3>Unsupported Environment</h3>
        <p>This web application is a Telegram Mini App and must be opened inside Telegram.</p>
    </div>

    <div id="log-container" style="background-color: #eee; padding: 10px; margin-top: 20px; text-align: left; font-size: 12px; max-height: 200px; overflow-y: auto;"></div>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tg = window.Telegram.WebApp;
            const logContainer = document.getElementById('log-container');

            function log(message) {
                const p = document.createElement('p');
                p.textContent = JSON.stringify(message, null, 2);
                logContainer.appendChild(p);
            }

            log('Telegram WebApp SDK loaded.');
            tg.ready();
            tg.expand();

            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('main-content');
            const errorMessage = document.getElementById('error-message');
            const notInTelegram = document.getElementById('not-in-telegram');

            if (tg.initData) {
                log('initData found.');
                const url = 'auth/telegram_login';
                log('Fetching URL: ' + url);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ initData: tg.initData })
                })
                .then(response => {
                    log('Received response from server.');
                    return response.json();
                })
                .then(data => {
                    log('Parsed server data:');
                    log(data);
                    loader.classList.add('hidden');
                    if (data.status === 'success') {
                        const user = data.user;
                        log('User data:');
                        log(user);
                        
                        // Parse user data from initData to get photo_url
                        const params = new URLSearchParams(tg.initData);
                        const userParam = JSON.parse(params.get('user'));
                        log('User param from initData:');
                        log(userParam);

                        document.getElementById('user-avatar').src = userParam.photo_url || '';
                        document.getElementById('user-name').textContent = `${user.first_name || ''} ${user.last_name || ''}`.trim();
                        document.getElementById('user-username').textContent = user.username ? `@${user.username}` : 'No username';
                        
                        mainContent.classList.remove('hidden');

                        tg.MainButton.setText("Close");
                        tg.MainButton.show();
                        tg.MainButton.onClick(() => tg.close());
                    } else {
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    log('Fetch Error:');
                    log(error.message);
                    log(error.stack);
                    loader.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                });
            } else {
                log('initData not found.');
                loader.classList.add('hidden');
                notInTelegram.classList.remove('hidden');
            }
        });
    </script>

</body>
</html>
