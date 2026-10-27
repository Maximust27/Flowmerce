/**
 * Small, dependency-free storefront controller.
 * Server remains authoritative for stock, pricing, tax and payment status.
 */
function initGuestMenu(root) {
    const productsNode = document.getElementById('fm-guest-products');
    if (!productsNode) return;

    let products = [];
    try { products = JSON.parse(productsNode.textContent); } catch { return; }

    const index = new Map(products.map(product => [Number(product.id), product]));
    const cart = new Map();
    const currency = number => 'Rp ' + Math.round(number).toLocaleString('id-ID');

    const countNode = root.querySelector('[data-cart-count]');
    const itemsNode = root.querySelector('[data-cart-items]');
    const subtotalNode = root.querySelector('[data-subtotal]');
    const taxNode = root.querySelector('[data-tax]');
    const totalNode = root.querySelector('[data-total]');
    const submitButton = root.querySelector('[data-submit-order]');
    const orderError = root.querySelector('[data-order-error]');
    const panel = root.querySelector('[data-cart-panel]');
    const backdrop = root.querySelector('[data-cart-backdrop]');
    const confirmation = root.querySelector('[data-confirmation]');
    const qrisPreview = root.querySelector('[data-qris-preview]');

    // Missing local uploads should never display the browser's broken-image
    // icon or alt text on top of the product card. A designed fallback remains.
    root.querySelectorAll('.fm-guest__product-image img').forEach(image => {
        const showFallback = () => {
            image.hidden = true;
        };
        image.addEventListener('error', showFallback, { once: true });
        if (image.complete && image.naturalWidth === 0) showFallback();
    });

    const icon = (name, label) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.setAttribute('aria-label', label);
        const span = document.createElement('span');
        span.className = 'material-symbols-outlined';
        span.setAttribute('aria-hidden', 'true');
        span.textContent = name;
        button.append(span);
        return button;
    };

    const setError = message => {
        orderError.hidden = !message;
        orderError.textContent = message || '';
    };

    function renderCart() {
        let count = 0;
        let subtotal = 0;
        itemsNode.replaceChildren();

        for (const [id, item] of cart) {
            count += item.qty;
            subtotal += item.price * item.qty;

            const row = document.createElement('div');
            row.className = 'fm-guest__cart-row';
            row.dataset.cartProductId = id;
            const details = document.createElement('div');
            details.className = 'fm-guest__cart-row-details';
            const name = document.createElement('strong');
            name.textContent = item.name;
            const price = document.createElement('span');
            price.textContent = currency(item.price * item.qty);
            details.append(name, price);

            const quantity = document.createElement('div');
            quantity.className = 'fm-guest__quantity';
            const minus = icon('remove', 'Kurangi ' + item.name);
            minus.dataset.qty = 'minus';
            minus.dataset.productId = id;
            const amount = document.createElement('span');
            amount.textContent = item.qty;
            const plus = icon('add', 'Tambah ' + item.name);
            plus.dataset.qty = 'plus';
            plus.dataset.productId = id;
            plus.disabled = item.qty >= item.stock;
            quantity.append(minus, amount, plus);
            row.append(details, quantity);
            itemsNode.append(row);
        }

        if (count === 0) {
            const empty = document.createElement('p');
            empty.className = 'fm-guest__cart-empty';
            empty.textContent = 'Keranjang masih kosong. Pilih menu untuk mulai memesan.';
            itemsNode.append(empty);
        }

        countNode.textContent = count;
        subtotalNode.textContent = currency(subtotal);
        taxNode.textContent = currency(Math.round(subtotal * 0.11 * 100) / 100);
        totalNode.textContent = currency(subtotal + Math.round(subtotal * 0.11 * 100) / 100);
        submitButton.disabled = count === 0 || busy;
    }

    function addItem(id) {
        const product = index.get(id);
        if (!product) return;
        const existing = cart.get(id);
        const qty = existing ? existing.qty : 0;
        if (qty >= product.stock) {
            setError('Jumlah melebihi stok yang tersedia.');
            return;
        }
        cart.set(id, {...product, qty: qty + 1});
        setError('');
        renderCart();
        root.dispatchEvent(new CustomEvent('flowmerce:cart-added', {
            bubbles: true,
            detail: { productId: id },
        }));
    }

    let busy = false;
    root.addEventListener('click', event => {
        const add = event.target.closest('[data-add-product]');
        if (add) addItem(Number(add.dataset.addProduct));

        const amount = event.target.closest('[data-qty]');
        if (amount) {
            const id = Number(amount.dataset.productId);
            const item = cart.get(id);
            if (!item) return;
            if (amount.dataset.qty === 'minus') {
                item.qty -= 1;
                if (item.qty <= 0) cart.delete(id);
            } else if (item.qty < item.stock) item.qty += 1;
            renderCart();
            if (amount.dataset.qty === 'plus') {
                root.dispatchEvent(new CustomEvent('flowmerce:cart-added', {
                    bubbles: true, detail: { productId: id },
                }));
            }
        }
        if (event.target.closest('[data-cart-toggle]')) {
            panel.classList.add('is-open');
            backdrop.hidden = false;
        }
        if (event.target.closest('[data-cart-close]') || event.target.closest('[data-cart-backdrop]')) {
            panel.classList.remove('is-open');
            backdrop.hidden = true;
        }
        if (event.target.closest('[data-reload-order]')) window.location.reload();
    });

    const search = root.querySelector('[data-search]');
    let activeCategory = 'Semua';

    function filterProducts() {
        const query = search.value.toLocaleLowerCase('id-ID').trim();
        let visible = 0;
        for (const card of root.querySelectorAll('[data-product-card]')) {
            const name = card.dataset.productName.toLocaleLowerCase('id-ID');
            const category = card.dataset.categoryName;
            const matches = (activeCategory === 'Semua' || category === activeCategory)
                && (!query || name.includes(query) || category.toLocaleLowerCase('id-ID').includes(query));
            card.hidden = !matches;
            if (matches) visible += 1;
        }
        root.querySelector('[data-no-results]').hidden = visible > 0 || products.length === 0;
    }

    search.addEventListener('input', filterProducts);
    root.querySelectorAll('[data-category]').forEach(button => {
        button.addEventListener('click', () => {
            activeCategory = button.dataset.category;
            root.querySelectorAll('[data-category]').forEach(el =>
                el.setAttribute('aria-pressed', String(el === button)));
            filterProducts();
        });
    });

    root.querySelectorAll('input[name="fm-guest-payment"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (qrisPreview) {
                const showingQris = root.querySelector('input[name="fm-guest-payment"]:checked').value === 'QRIS';
                qrisPreview.hidden = !showingQris;
                if (showingQris) root.dispatchEvent(new CustomEvent('flowmerce:qris-revealed', { bubbles: true }));
            }
        });
    });

    submitButton.addEventListener('click', async () => {
        if (busy || !cart.size) return;
        busy = true;
        setError('');
        submitButton.disabled = true;
        submitButton.textContent = 'Mengirim pesanan...';

        const method = root.querySelector('input[name="fm-guest-payment"]:checked')?.value || 'CASHIER';
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch(root.dataset.orderUrl, {
                method:'POST',
                credentials:'same-origin',
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json',
                    'X-CSRF-TOKEN':csrf || '',
                },
                body:JSON.stringify({
                    items:[...cart].map(([id,item])=>({product_id:id,quantity:item.qty})),
                    payment_method:method,
                }),
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                throw new Error(firstError || result.message || 'Pesanan gagal dikirim. Periksa pilihan menu.');
            }

            root.querySelector('[data-order-code]').textContent = result.order_code;
            root.querySelector('[data-order-total]').textContent = currency(result.total);
            confirmation.hidden = false;
            root.dispatchEvent(new CustomEvent('flowmerce:order-confirmed', { bubbles: true }));
            panel.classList.remove('is-open');
            backdrop.hidden = true;
        } catch (error) {
            setError(error.message || 'Koneksi bermasalah. Coba kembali.');
        } finally {
            busy = false;
            submitButton.replaceChildren();
            const iconSpan = document.createElement('span');
            iconSpan.className = 'material-symbols-outlined';
            iconSpan.textContent = 'receipt_long';
            submitButton.append(iconSpan, document.createTextNode('Kirim pesanan'));
            renderCart();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            panel.classList.remove('is-open');
            backdrop.hidden = true;
        }
    });

    renderCart();
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-fm-guest]');
    if (root) initGuestMenu(root);
});
