<?php
/**
 * footer_end.php
 *
 * Author: pixelcave
 *
 * The last block of code used in every page of the template
 *
 */
?>
<script>
function initMiniApp() {
    const tg = window.Telegram.WebApp;
    tg.ready();
    tg.expand();

    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('main-content');
    const errorMessage = document.getElementById('error-message');
    const notInTelegram = document.getElementById('not-in-telegram');

    if (tg.initData) {
        const url = '<?php echo site_url("auth/telegram_login"); ?>';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ initData: tg.initData })
        })
        .then(response => response.json())
        .then(data => {
            loader.classList.add('hidden');
            if (data.status === 'success') {
                const user = data.user;
                
                // Parse user data from initData to get photo_url
                const params = new URLSearchParams(tg.initData);
                const userParam = JSON.parse(params.get('user'));

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
            loader.classList.add('hidden');
            errorMessage.classList.remove('hidden');
        });
    } else {
        loader.classList.add('hidden');
        notInTelegram.classList.remove('hidden');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($is_miniapp_page) && $is_miniapp_page): ?>
    fetch('<?php echo site_url("miniapp/get_miniapp_content"); ?>')
        .then(response => response.text())
        .then(html => {
            document.getElementById('main-content-container').innerHTML = html;
            initMiniApp();
        });
    <?php endif; ?>
});
</script>
</body>
</html>