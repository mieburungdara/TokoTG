<!-- application/views/admin/add_balance.php -->
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
    }
    .form-container {
        max-width: 500px;
        margin: 0 auto;
        background-color: var(--secondary-bg-color);
        padding: 20px;
        border-radius: 12px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .form-group input {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid var(--hint-color);
        background-color: var(--bg-color);
        color: var(--text-color);
        box-sizing: border-box;
    }
    .submit-btn {
        width: 100%;
        padding: 12px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        background-color: var(--button-color);
        color: var(--button-text-color);
        border: none;
        border-radius: 8px;
    }
</style>

<div class="form-container">
    <h2 style="text-align: center; margin-top: 0;">Add Balance</h2>
    <form id="add-balance-form">
        <div class="form-group">
            <label for="target_user_id">Target User ID</label>
            <input type="number" id="target_user_id" name="target_user_id" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" placeholder="e.g., Initial deposit">
        </div>
        <button type="submit" class="submit-btn">Submit</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tg = window.Telegram.WebApp;
        tg.ready();

        const form = document.getElementById('add-balance-form');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            tg.MainButton.showProgress();

            const formData = {
                target_user_id: parseInt(document.getElementById('target_user_id').value),
                amount: parseFloat(document.getElementById('amount').value),
                description: document.getElementById('description').value
            };

            fetch('<?php echo site_url("api/admin_add_balance"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                tg.MainButton.hideProgress();
                if (data.status === 'success') {
                    tg.showAlert('Success! Balance has been added.');
                    form.reset();
                } else {
                    tg.showAlert(`Error: ${data.error || 'An unknown error occurred.'}`);
                }
            })
            .catch(error => {
                tg.MainButton.hideProgress();
                tg.showAlert(`Request failed: ${error.message}`);
                console.error('Form submission error:', error);
            });
        });
    });
</script>
