<!-- application/views/seller/products_index.php -->
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
        --destructive-color: var(--tg-theme-destructive-text-color, #dc3545);
    }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
        padding: 15px;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .add-new-btn {
        background-color: var(--button-color);
        color: var(--button-text-color);
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--secondary-bg-color);
        border-radius: 12px;
        overflow: hidden;
    }
    .product-table th, .product-table td {
        padding: 12px 15px;
        text-align: left;
    }
    .product-table thead {
        background-color: rgba(0,0,0,0.05);
    }
    .product-table tbody tr {
        border-bottom: 1px solid var(--bg-color);
    }
    .product-table tbody tr:last-child {
        border-bottom: none;
    }
    .actions a, .actions button {
        color: var(--link-color);
        text-decoration: none;
        margin-right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: inherit;
        padding: 0;
    }
    .delete-btn {
        color: var(--destructive-color);
    }
</style>

<div class="page-header">
    <h2>My Products</h2>
    <a href="<?php echo site_url('seller/add_product'); ?>" class="add-new-btn">+ Add New</a>
</div>

<div class="table-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="product-table-body">
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--hint-color);">You have not added any products yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr id="product-row-<?php echo $product['id']; ?>">
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_code']); ?></td>
                        <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                        <td><?php echo number_format($product['stock_quantity']); ?></td>
                        <td class="actions">
                            <a href="<?php echo site_url('seller/edit_product/' . $product['id']); ?>">Edit</a>
                            <button class="delete-btn" data-product-id="<?php echo $product['id']; ?>" data-product-name="<?php echo htmlspecialchars($product['name']); ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tg = window.Telegram.WebApp;
        tg.ready();

        const productTableBody = document.getElementById('product-table-body');

        productTableBody.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('delete-btn')) {
                const button = e.target;
                const productId = button.dataset.productId;
                const productName = button.dataset.productName;

                tg.showConfirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`, (isConfirmed) => {
                    if (isConfirmed) {
                        fetch(`<?php echo site_url("api/delete_product"); ?>/${productId}`, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Remove the row from the table
                                const row = document.getElementById(`product-row-${productId}`);
                                if (row) {
                                    row.remove();
                                }
                                tg.showAlert('Product deleted successfully.');
                            } else {
                                tg.showAlert(`Error: ${data.error || 'An unknown error occurred.'}`);
                            }
                        })
                        .catch(error => {
                            tg.showAlert(`Request failed: ${error.message}`);
                        });
                    }
                });
            }
        });
    });
</script>
