<!-- application/views/marketplace/index.php -->
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
        padding: 15px;
    }
    #user-balance-container {
        background-color: var(--secondary-bg-color);
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 15px;
        text-align: center;
        font-size: 1.1em;
    }
    #user-balance-container span {
        font-weight: bold;
        color: var(--link-color);
    }
    #product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    .product-card {
        background-color: var(--secondary-bg-color);
        border-radius: 12px;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-card img {
        max-width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }
    .product-name {
        font-weight: 600;
        font-size: 1em;
        margin: 8px 0 4px 0;
    }
    .product-price {
        color: var(--link-color);
        font-weight: bold;
        font-size: 0.9em;
        margin-bottom: 8px;
    }
    .buy-button {
        background-color: var(--button-color);
        color: var(--button-text-color);
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }
    #loader {
        text-align: center;
        padding: 40px;
        color: var(--hint-color);
    }
</style>

<div id="user-balance-container">Loading balance...</div>
<div id="loader">Loading products...</div>
<div id="product-list"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tg = window.Telegram.WebApp;
        tg.ready();

        const loader = document.getElementById('loader');
        const productList = document.getElementById('product-list');
        const balanceContainer = document.getElementById('user-balance-container');
        
        let cart = [];
        let userBalance = 0;

        // --- CART & MAIN BUTTON MANAGEMENT ---
        function updateMainButton() {
            if (cart.length > 0) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                tg.MainButton.setText(`Checkout (${totalItems} items)`);
                tg.MainButton.show();
            } else {
                tg.MainButton.hide();
            }
        }

        function handleCheckout() {
            if (cart.length === 0) return;

            tg.showConfirm("Proceed to checkout?", (isConfirmed) => {
                if (isConfirmed) {
                    tg.MainButton.showProgress();
                    fetch('<?php echo site_url("api/checkout"); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ cart: cart, payment_method: 'balance' }) // Assuming payment via balance
                    })
                    .then(response => response.json())
                    .then(data => {
                        tg.MainButton.hideProgress();
                        if (data.status === 'success') {
                            tg.showAlert(`Order successfully created!\nOrder ID: ${data.order_id}`);
                            cart = []; // Clear cart
                            updateMainButton();
                            fetchBalance(); // Refresh balance after purchase
                        } else {
                            tg.showAlert(`Checkout failed: ${data.error || 'Unknown error'}`);
                        }
                    })
                    .catch(error => {
                        tg.MainButton.hideProgress();
                        tg.showAlert(`An error occurred: ${error.message}`);
                    });
                }
            });
        }

        // --- DATA FETCHING & RENDERING ---
        function renderProducts(products) {
            productList.innerHTML = ''; // Clear existing products
            if (products && products.length > 0) {
                products.forEach(product => {
                    const card = document.createElement('div');
                    card.className = 'product-card';
                    const placeholderImg = 'https://via.placeholder.com/150';
                    card.innerHTML = `
                        <div>
                            <img src="${placeholderImg}" alt="${product.name}">
                            <div class="product-name">${product.name}</div>
                            <div class="product-price">Rp ${parseFloat(product.price).toLocaleString('id-ID')}</div>
                        </div>
                        <button class="buy-button" data-product-id="${product.id}" data-product-name="${product.name}">Buy</button>
                    `;
                    productList.appendChild(card);
                });
            } else {
                productList.innerHTML = '<p style="color: var(--hint-color);">No products found.</p>';
            }
        }

        function fetchBalance() {
            return fetch('<?php echo site_url("api/balance"); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.balance !== undefined) {
                        userBalance = parseFloat(data.balance);
                        balanceContainer.innerHTML = `Your Balance: <span>Rp ${userBalance.toLocaleString('id-ID')}</span>`;
                    } else {
                        balanceContainer.textContent = 'Could not load balance.';
                    }
                })
                .catch(error => {
                    balanceContainer.textContent = 'Error loading balance.';
                    console.error('Error fetching balance:', error);
                });
        }

        function fetchProducts() {
            return fetch('<?php echo site_url("api/products"); ?>')
                .then(response => response.json())
                .then(products => {
                    renderProducts(products);
                });
        }

        // --- EVENT LISTENERS ---
        productList.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('buy-button')) {
                const productId = e.target.dataset.productId;
                const productName = e.target.dataset.productName;
                cart.push({ product_id: parseInt(productId), quantity: 1 });
                tg.showPopup({ title: 'Added to Cart', message: `${productName} has been added to your cart.` });
                updateMainButton();
            }
        });

        // --- INITIALIZATION ---
        tg.MainButton.onClick(handleCheckout);
        updateMainButton();

        // Fetch initial data in parallel
        Promise.all([fetchBalance(), fetchProducts()])
            .catch(error => {
                tg.showAlert('Could not load initial data. Please try again.');
            })
            .finally(() => {
                loader.style.display = 'none';
            });
    });
</script>
