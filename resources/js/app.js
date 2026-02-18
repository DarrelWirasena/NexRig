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
 * 3. UPDATE UI LOGIC (DIPERBAIKI)
 * ==========================================
 */
window.updateMiniCartUI = function(data) {
    // A. Update List Item & Subtotal
    const itemsContainer = document.getElementById('miniCartItems');
    const subtotalEl = document.getElementById('miniCartSubtotal');
    
    if(itemsContainer) itemsContainer.innerHTML = data.cartHtml;
    if(subtotalEl) subtotalEl.innerText = data.subtotal;

    // Hitung jumlah item
    const count = typeof data.cartCount !== 'undefined' ? data.cartCount : 0;

    // ==========================================
    // [BARU] C. UPDATE BADGE NAVBAR (TITIK MERAH / ANGKA)
    // ==========================================
    const cartCountEl = document.getElementById('cart-count'); // ID elemen angka di navbar
    const cartBadge = document.getElementById('cart-badge');   // ID elemen titik merah (jika pakai dot)

    // Update Angka
    if (cartCountEl) {
        cartCountEl.innerText = count;
        // Sembunyikan angka jika 0 (opsional, sesuaikan selera)
        cartCountEl.style.display = count > 0 ? 'flex' : 'none';
    }

    // Update Titik Merah (Badge)
    if (cartBadge) {
        if (count > 0) {
            cartBadge.classList.remove('hidden');
        } else {
            cartBadge.classList.add('hidden');
        }
    }

    // ==========================================
    // B. Update Tombol Checkout
    // ==========================================
    const checkoutBtn = document.getElementById('miniCartCheckoutBtn');
    const btnText = document.getElementById('checkoutBtnText');
    const btnIcon = document.getElementById('checkoutBtnIcon');

    if (checkoutBtn) {
        if (count === 0) {
            // MODE KOSONG (LOCK)
            checkoutBtn.classList.remove('bg-primary', 'hover:bg-blue-600', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.4)]');
            checkoutBtn.classList.add('bg-white/10', 'text-gray-600', 'cursor-not-allowed', 'pointer-events-none');
            
            if(btnText) btnText.innerText = 'Empty';
            if(btnIcon) btnIcon.innerText = 'lock';
            checkoutBtn.setAttribute('href', '#');
            
        } else {
            // MODE ISI (ACTIVE)
            checkoutBtn.classList.remove('bg-white/10', 'text-gray-600', 'cursor-not-allowed', 'pointer-events-none');
            checkoutBtn.classList.add('bg-primary', 'hover:bg-blue-600', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.4)]');
            
            if(btnText) btnText.innerText = 'Checkout';
            if(btnIcon) btnIcon.innerText = 'arrow_forward';
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
 * 6. MAIN CART PAGE LOGIC
 * ==========================================
 */

// A. UPDATE QUANTITY (Minus & Plus)
window.updateMainCartItem = function(id, change) {
    const qtySpan = document.getElementById(`qty-display-${id}`);
    const btnMinus = document.querySelector(`button[onclick="updateMainCartItem('${id}', -1)"]`);
    
    // 1. Cek Batas Minimum (Mencegah request jika quantity sudah 1 dan mau dikurangi)
    let currentQty = parseInt(qtySpan.innerText);
    if (change === -1 && currentQty <= 1) {
        // Opsional: Goyangkan angka atau beri visual feedback "mentok"
        return; 
    }

    // 2. Visual Loading
    if(qtySpan) qtySpan.style.opacity = '0.5';
    
    // Disable tombol sementara agar tidak spam klik
    if(btnMinus) btnMinus.disabled = true;

    // 3. Kirim Request
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
            // Update Angka di Item
            if(qtySpan) {
                qtySpan.innerText = data.item_quantity;
                qtySpan.style.opacity = '1';
            }

            // Update Summary (Subtotal, Tax, Total)
            const ids = ['summary-subtotal', 'summary-tax', 'summary-grand-total'];
            const keys = ['subtotal', 'tax', 'grand_total'];
            
            ids.forEach((id, index) => {
                const el = document.getElementById(id);
                if(el) el.innerText = data[keys[index]];
            });

            // Update status tombol minus (disable jika quantity jadi 1)
            const minusBtn = document.querySelector(`button[onclick="updateMainCartItem('${id}', -1)"]`);
            if(minusBtn) {
                if (data.item_quantity <= 1) {
                    minusBtn.classList.add('opacity-30', 'pointer-events-none');
                } else {
                    minusBtn.classList.remove('opacity-30', 'pointer-events-none');
                }
            }

            // Sync Mini Cart
            window.updateMiniCartUI(data);
        }
    })
    .catch(err => console.error(err))
    .finally(() => {
        if(btnMinus) btnMinus.disabled = false;
    });
};

// B. REMOVE ITEM (Dipanggil oleh Modal di blade)
// Ganti nama dari 'removeMainCartItem' menjadi 'executeRemoveCartItem'
// Agar tidak konflik dengan logika modal
window.executeRemoveCartItem = function(id) {
    
    // Efek visual menghapus baris
    const row = document.getElementById(`cart-row-${id}`);
    if(row) {
        row.style.transition = "all 0.5s ease-out";
        row.style.opacity = "0";
        row.style.transform = "translateX(50px) scale(0.95)";
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
            // Hapus elemen dari DOM setelah animasi selesai
            setTimeout(() => { if(row) row.remove(); }, 500);

            // Update Summary
            const ids = ['summary-subtotal', 'summary-tax', 'summary-grand-total'];
            const keys = ['subtotal', 'tax', 'grand_total'];
            ids.forEach((id, index) => {
                const el = document.getElementById(id);
                if(el) el.innerText = data[keys[index]];
            });
            
            // Sync Mini Cart
            window.updateMiniCartUI(data);
            
            // Tampilkan Toast
            window.showToast('Item removed from build', 'success');

            // Cek jika cart kosong, reload halaman agar tampil Empty State
            if(data.cartCount === 0) {
                setTimeout(() => location.reload(), 600);
            }
        }
    })
    .catch(err => {
        console.error(err);
        if(row) {
            // Kembalikan row jika gagal
            row.style.opacity = "1";
            row.style.transform = "none";
        }
        window.showToast('Failed to remove item', 'error');
    });
};

// Global Listener untuk animasi scroll (tetap dipertahankan)
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. SCROLL ANIMATION (Kode Lama Kamu)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-reveal');
                entry.target.classList.remove('opacity-0');
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.scroll-trigger').forEach((el) => observer.observe(el));

    // 2. FLASH MESSAGE HANDLER (Best Practice)
    // Ini akan membaca data dari layout dan memunculkan Toast
    const flashEl = document.getElementById('flash-messages');
    if (flashEl) {
        const successMsg = flashEl.dataset.success;
        const errorMsg = flashEl.dataset.error;
        const validationMsg = flashEl.dataset.validation;

        if (successMsg) window.showToast(successMsg, 'success');
        if (errorMsg) window.showToast(errorMsg, 'error');
        if (validationMsg) window.showToast(validationMsg, 'error');
    }

});