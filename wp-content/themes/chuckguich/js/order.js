document.addEventListener('DOMContentLoaded', function() {
    const promoCodes = {
        'SAUCISSE20': { type: 'percentage', value: 20 },
        'CHUCK5': { type: 'fixed', value: 5 }
    };

    function calculateTotals() {
        let subtotal = 0;
        const products = [];
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.getAttribute('data-price')) || 0;
            const id = input.name.match(/products\[(\d+)\]/)[1];
            if (quantity > 0) {
                subtotal += quantity * price;
                products.push({ id, quantity, price });
            }
        });

        const tax = subtotal * 0.20;
        let totalBeforeDiscount = subtotal + tax;
        let total = totalBeforeDiscount;
        let discountAmount = 0;
        const discount = { type: document.getElementById('discountType').value, value: parseFloat(document.getElementById('discountValue').value) || 0 };

        if (discount.type && discount.value) {
            discountAmount = discount.type === 'percentage' ? totalBeforeDiscount * (discount.value / 100) : Math.min(discount.value, totalBeforeDiscount);
            total = totalBeforeDiscount - discountAmount;
            document.getElementById('discountRow').classList.remove('d-none');
            document.getElementById('beforeDiscountRow').classList.remove('d-none');
            document.getElementById('discountAmount').textContent = `-${discountAmount.toFixed(2)}€`;
            document.getElementById('totalBeforeDiscount').textContent = totalBeforeDiscount.toFixed(2) + '€';
        } else {
            document.getElementById('discountRow').classList.add('d-none');
            document.getElementById('beforeDiscountRow').classList.add('d-none');
        }

        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + '€';
        document.getElementById('tax').textContent = tax.toFixed(2) + '€';
        document.getElementById('total').textContent = total.toFixed(2) + '€';

        return { hasProducts: products.length > 0, products, discount: discount.type ? discount : null };
    }

    document.getElementById('applyPromo').addEventListener('click', function() {
        const codeInput = document.getElementById('promoCode');
        const messageEl = document.getElementById('promoMessage');
        const code = codeInput.value.trim().toUpperCase();

        if (promoCodes[code]) {
            document.getElementById('discountType').value = promoCodes[code].type;
            document.getElementById('discountValue').value = promoCodes[code].value;
            messageEl.textContent = 'Code promo appliqué !';
            messageEl.className = 'text-success';
            codeInput.classList.add('is-valid');
            codeInput.classList.remove('is-invalid');
        } else {
            document.getElementById('discountType').value = '';
            document.getElementById('discountValue').value = '';
            messageEl.textContent = 'Code promo invalide';
            messageEl.className = 'text-danger';
            codeInput.classList.add('is-invalid');
            codeInput.classList.remove('is-valid');
        }
        calculateTotals();
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.max) || 0;
            let value = parseInt(this.value) || 0;
            if (value > max) value = max;
            if (value < 0) value = 0;
            this.value = value;
            calculateTotals();
        });
    });

    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const { hasProducts } = calculateTotals();
        if (!hasProducts) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un produit');
        }
    });

    calculateTotals();
});