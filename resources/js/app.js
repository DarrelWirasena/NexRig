import './bootstrap';

/**
 * ==========================================
 * 1. DYNAMIC TOAST SYSTEM (SMART POSITIONING)
 * ==========================================
 */
window.showToast = function(message, type = 'success') {
    // 1. Hapus toast lama
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) existingToast.remove();

    // 2. Tentukan Warna & Ikon
    const isSuccess = type === 'success';
    const themeColor = isSuccess ? 'blue-600' : 'red-600';
    const icon = isSuccess ? 'check' : 'priority_high';
    const title = isSuccess ? 'SUCCESS' : 'ERROR';

    // 3. DETEKSI POSISI MINI CART (LOGIC UX FIX)
    const cart = document.getElementById('miniCart');
    
    // Cek apakah cart sedang terbuka (Class translate-x-full HILANG berarti terbuka)
    const isCartOpen = cart && !cart.classList.contains('translate-x-full');
    const isMobile = window.innerWidth < 768;

    let positionClass = 'bottom-5 right-5'; // Posisi Default (Pojok Kanan Bawah)
    let animateEnter = 'translate-y-10';    // Animasi Default (Naik dari bawah)

    if (isCartOpen) {
        if (isMobile) {
            // MOBILE: Jika cart buka, pindahkan notif ke ATAS (bawah navbar) agar tidak menutupi tombol checkout bawah
            positionClass = 'top-24 right-5'; 
            animateEnter = '-translate-y-10'; // Animasi turun dari atas
        } else {
            // DESKTOP: Jika cart buka, geser notif ke SEBELAH KIRI Sidebar (450px sidebar + 20px gap)
            positionClass = 'bottom-5 right-[480px]'; 
        }
    }

    // 4. Buat HTML Toast Baru dengan Smart Position
    const toastHTML = `
        <div id="toast-notification" class="fixed ${positionClass} z-[200] flex items-center gap-4 bg-[#0a0a0a] border border-${themeColor}/50 text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(0,0,0,0.5)] transform transition-all duration-500 ${animateEnter} opacity-0 overflow-hidden">
            
            <div class="flex items-center justify-center w-8 h-8 bg-${themeColor}/20 rounded-full text-${themeColor}">
                <span class="material-symbols-outlined text-xl">${icon}</span>
            </div>
            
            <div>
                <h4 class="font-bold text-sm text-${themeColor} uppercase tracking-wider">${title}</h4>
                <p class="text-gray-300 text-xs mt-0.5 whitespace-nowrap">${message}</p>
            </div>
            
            <button onclick="this.parentElement.remove()" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>

            <div id="toast-progress" class="absolute bottom-0 left-0 h-[3px] bg-${themeColor} w-full transition-all duration-[3000ms] ease-linear"></div>
        </div>
    `;

    // 5. Render ke Body
    document.body.insertAdjacentHTML('beforeend', toastHTML);

    // 6. Trigger Animasi
    const toastEl = document.getElementById('toast-notification');
    const progressEl = document.getElementById('toast-progress');

    requestAnimationFrame(() => {
        // Hapus class animasi masuk agar elemen muncul ke posisi aslinya
        toastEl.classList.remove('translate-y-10', '-translate-y-10', 'opacity-0');
        
        // Jalankan Progress Bar
        setTimeout(() => {
            progressEl.classList.remove('w-full');
            progressEl.classList.add('w-0');
        }, 50); 
    });

    // 7. Auto Close
    setTimeout(() => {
        if (toastEl) {
            // Animasi Keluar (Sesuai posisi masuk tadi)
            toastEl.classList.add(animateEnter, 'opacity-0');
            setTimeout(() => toastEl.remove(), 500);
        }
    }, 3000);
};


/**
 * ==========================================
 * 2. MINI CART LOGIC
 * ==========================================
 */
window.toggleMiniCart = function() {
    const cart = document.getElementById('miniCart');
    const overlay = document.getElementById('miniCartOverlay');
    
    if (!cart || !overlay) return;

    const isHidden = cart.classList.contains('translate-x-full');

    if (isHidden) {
        cart.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    } else {
        cart.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}

/**
 * ==========================================
 * 3. UPDATE UI LOGIC (Penyebab Tombol Rusak Disini)
 * ==========================================
 */
window.updateMiniCartUI = function(data) {
    // A. Update List Item & Subtotal
    const itemsContainer = document.getElementById('miniCartItems');
    const subtotalEl = document.getElementById('miniCartSubtotal');
    
    if(itemsContainer) itemsContainer.innerHTML = data.cartHtml;
    if(subtotalEl) subtotalEl.innerText = data.subtotal;

    // B. Update Tombol Checkout (Logic Kunci)
    const checkoutBtn = document.getElementById('miniCartCheckoutBtn');
    const btnText = document.getElementById('checkoutBtnText');
    const btnIcon = document.getElementById('checkoutBtnIcon');

    if (checkoutBtn) {
        // Cek jumlah item dari data yang dikirim controller
        // Jika undefined, kita anggap kosong untuk safety
        const count = typeof data.cartCount !== 'undefined' ? data.cartCount : 0;

        if (count === 0) {
            // MODE KOSONG (LOCK)
            // Hapus style aktif
            checkoutBtn.classList.remove('bg-primary', 'hover:bg-blue-600', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.4)]');
            
            // Tambah style mati
            checkoutBtn.classList.add('bg-white/10', 'text-gray-600', 'cursor-not-allowed', 'pointer-events-none');
            
            // Ubah Teks & Ikon
            if(btnText) btnText.innerText = 'Empty';
            if(btnIcon) btnIcon.innerText = 'lock';
            
            // Ubah Link jadi # agar tidak bisa diklik (double safety)
            checkoutBtn.setAttribute('href', '#');
            
        } else {
            // MODE ISI (ACTIVE)
            // Hapus style mati
            checkoutBtn.classList.remove('bg-white/10', 'text-gray-600', 'cursor-not-allowed', 'pointer-events-none');
            
            // Tambah style aktif
            checkoutBtn.classList.add('bg-primary', 'hover:bg-blue-600', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.4)]');
            
            // Ubah Teks & Ikon
            if(btnText) btnText.innerText = 'Checkout';
            if(btnIcon) btnIcon.innerText = 'arrow_forward';

            // Balikin Link ke route checkout yang benar
            // Pastikan URL checkout ini sesuai dengan route kamu
            checkoutBtn.setAttribute('href', '/checkout'); 
        }
    }
}


/**
 * ==========================================
 * 4. AJAX OPERATIONS
 * ==========================================
 */
window.addToCartAjax = function(e, form) {
    e.preventDefault(); 
    
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
    btn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // 1. Update Tampilan Cart & Tombol
            window.updateMiniCartUI(data);
            
            // 2. Buka Cart
            window.toggleMiniCart(); 
            
            // 3. Tampilkan Toast Manual (PENTING)
            window.showToast('Product added to your rig successfully!');
        } else {
            window.showToast('Failed to add product', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        window.showToast('System Error: Check Console', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

window.removeCartItem = function(id) {
    const itemElement = document.getElementById(`cart-item-${id}`);
    if(itemElement) {
        itemElement.style.opacity = '0.3';
        itemElement.style.pointerEvents = 'none';
    }

    fetch(`/cart/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.updateMiniCartUI(data);
            window.showToast('Item removed from cart');
        } else {
            window.showToast(data.message || 'Failed to remove', 'error');
            if(itemElement) itemElement.style.opacity = '1';
        }
    })
    .catch(err => {
        console.error(err);
        window.showToast('Failed to remove item', 'error');
        if(itemElement) itemElement.style.opacity = '1';
    });
}

/**
 * ==========================================
 * 5. UPDATE QUANTITY LOGIC
 * ==========================================
 */
window.updateCartQuantity = function(id, change) {
    // 1. Cari elemen angka sekarang (opsional, untuk validasi visual cepat)
    // Tapi kita akan relying pada server response untuk update UI
    
    // 2. Kirim Request ke Server
    fetch('/cart/update', {
        method: 'PATCH', // Gunakan PATCH untuk update data
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        // Kirim ID dan Perubahan (+1 atau -1)
        body: JSON.stringify({ 
            id: id, 
            change: change 
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Update UI Mini Cart dengan HTML baru dari server
            window.updateMiniCartUI(data);
        } else {
            window.showToast(data.message || 'Error updating cart', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        window.showToast('Failed to connect to server', 'error');
    });
}

/**
 * ==========================================
 * 6. MAIN CART PAGE LOGIC (Realtime Update)
 * ==========================================
 */
window.updateMainCartItem = function(id, change) {
    // 1. Visual Feedback (Loading state pada angka)
    const qtySpan = document.getElementById(`qty-display-${id}`);
    if(qtySpan) qtySpan.style.opacity = '0.5';

    // 2. Kirim Request
    fetch('/cart/update', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: id, change: change })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // A. Update Angka di Item Tersebut
            if(qtySpan) {
                qtySpan.innerText = data.item_quantity;
                qtySpan.style.opacity = '1';
            }

            // B. Update Sidebar Summary (Subtotal, Tax, Total)
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryTax = document.getElementById('summary-tax');
            const summaryGrandTotal = document.getElementById('summary-grand-total');

            if(summarySubtotal) summarySubtotal.innerText = data.subtotal;
            if(summaryTax) summaryTax.innerText = data.tax;
            if(summaryGrandTotal) summaryGrandTotal.innerText = data.grand_total;

            // C. Sinkronisasi dengan Mini Cart (Penting!)
            // Agar kalau user buka mini cart, isinya sudah sama
            window.updateMiniCartUI(data);

            window.showToast('Cart updated');
        }
    })
    .catch(err => console.error(err));
};

// Fungsi Remove khusus halaman Cart Utama
window.removeMainCartItem = function(id) {
    if(!confirm('Remove this build from your setup?')) return;

    // Efek visual menghapus baris
    const row = document.getElementById(`cart-row-${id}`);
    if(row) {
        row.style.transition = "all 0.5s";
        row.style.opacity = "0";
        row.style.transform = "translateX(50px)";
    }

    fetch(`/cart/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Hapus elemen dari DOM
            setTimeout(() => { if(row) row.remove(); }, 500);

            // Update Summary
            document.getElementById('summary-subtotal').innerText = data.subtotal;
            document.getElementById('summary-tax').innerText = data.tax;
            document.getElementById('summary-grand-total').innerText = data.grand_total;
            
            // Sync Mini Cart
            window.updateMiniCartUI(data);
            
            // Cek jika cart kosong, reload halaman agar tampil Empty State
            if(data.cartCount === 0) location.reload();
        }
    });
};

// Global Listener untuk animasi scroll (tetap dipertahankan)
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-reveal');
                entry.target.classList.remove('opacity-0');
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.scroll-trigger').forEach((el) => observer.observe(el));
});