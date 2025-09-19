<!-- application/views/seller/add_product.php -->
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
        max-width: 600px;
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
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid var(--hint-color);
        background-color: var(--bg-color);
        color: var(--text-color);
        box-sizing: border-box;
    }
    textarea {
        min-height: 100px;
        resize: vertical;
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
    <h2 style="text-align: center; margin-top: 0;">Add New Product</h2>
    <form id="add-product-form">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="product_code">Product Code (5 digits)</label>
            <input type="text" id="product_code" name="product_code" required maxlength="5" pattern="[0-9]{5}">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="stock_quantity">Stock Quantity</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required>
        </div>
        <button type="submit" class="submit-btn">Save Product</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tg = window.Telegram.WebApp;
        tg.ready();

        const form = document.getElementById('add-product-form');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            tg.MainButton.showProgress();

            const formData = {
                name: document.getElementById('name').value,
                product_code: document.getElementById('product_code').value,
                description: document.getElementById('description').value,
                price: parseFloat(document.getElementById('price').value),
                stock_quantity: parseInt(document.getElementById('stock_quantity').value),
                is_active: 1 // Default to active when creating
            };

            fetch('<?php echo site_url("api/create_product"); ?>', {
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
                    tg.showAlert('Product created successfully!', () => {
                        // Redirect back to the product list
                        window.location.href = '<?php echo site_url("seller/products"); ?>';
                    });
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
