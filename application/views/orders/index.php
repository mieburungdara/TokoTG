<!-- application/views/orders/index.php -->
<style>
    :root {
        color-scheme: light dark;
        --bg-color: var(--tg-theme-bg-color, #ffffff);
        --text-color: var(--tg-theme-text-color, #222222);
        --hint-color: var(--tg-theme-hint-color, #999999);
        --secondary-bg-color: var(--tg-theme-secondary-bg-color, #f1f1f1);
    }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
        padding: 15px;
    }
    .order-list-container {
        background-color: var(--secondary-bg-color);
        border-radius: 12px;
        padding: 1px 15px 15px 15px;
    }
    .order-item {
        border-bottom: 1px solid var(--hint-color);
        padding: 15px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .order-id {
        font-size: 1.1em;
    }
    .order-status {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.9em;
        text-transform: capitalize;
    }
    .status-pending { background-color: #ffc107; color: #333; }
    .status-paid { background-color: #28a745; color: white; }
    .status-completed { background-color: #17a2b8; color: white; }
    .status-cancelled { background-color: #dc3545; color: white; }
    .order-details {
        font-size: 0.95em;
        color: var(--hint-color);
    }
    .order-total {
        font-weight: bold;
    }
</style>

<div class="order-list-container">
    <h2 style="text-align: center;">My Orders</h2>
    <?php if (empty($orders)): ?>
        <p style="text-align: center; color: var(--hint-color);">You have no orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-item">
                <div class="order-header">
                    <div class="order-id">Order #<?php echo $order['id']; ?></div>
                    <div class="order-status status-<?php echo htmlspecialchars($order['status']); ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </div>
                </div>
                <div class="order-details">
                    <div>Date: <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></div>
                    <div class="order-total">Total: Rp <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
