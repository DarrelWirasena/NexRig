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

/**
 * ==========================================
 * 7. CHATBOT (SAKA Assistant)
 * ==========================================
 */
let chatHistoryLoaded = false;
let isChatAnimating = false;

window.toggleChat = function() {
    const chatWindow = document.getElementById('chat-window');
    
    // Cek apakah chat sedang terbuka (memiliki class chat-show)
    if (chatWindow.classList.contains('chat-show')) {
        closeChat();
    } else {
        openChat();
    }
}

/**
 * FUNGSI BUKA (OPEN)
 */
function openChat() {
    if (isChatAnimating) return;
    const chatWindow = document.getElementById('chat-window');
    const chatBtn = document.getElementById('chat-toggle-btn');

    // 1. Hapus class 'closed' dan 'hide' (agar elemen dirender dan siap animasi)
    chatWindow.classList.remove('chat-closed', 'chat-hide');

    // 2. Load History
    if (!chatHistoryLoaded) {
        loadChatHistory();
        chatHistoryLoaded = true;
        if (getChatHistory().length === 0) window.clearChatHistory(); 
    }

    // 3. Trigger animasi masuk
    // requestAnimationFrame memastikan browser merender state sebelum menambah class baru
    requestAnimationFrame(() => {
        chatWindow.classList.add('chat-show');
    });

    // 4. Efek tombol (Optional: putar icon)
    chatBtn.classList.add('rotate-180');

    // 5. Focus ke input
    setTimeout(() => document.getElementById('chat-input').focus(), 100);
}

/**
 * FUNGSI TUTUP (CLOSE)
 */
window.closeChat = function() {
    const chatWindow = document.getElementById('chat-window');
    const chatBtn = document.getElementById('chat-toggle-btn');

    // Jika sudah tertutup, abaikan
    if (chatWindow.classList.contains('chat-closed')) return;

    isChatAnimating = true;

    // 1. Hapus class 'show' (Trigger transisi opacity/scale)
    chatWindow.classList.remove('chat-show');
    
    // 2. Tambah class 'hide' (Memastikan posisi turun ke bawah)
    chatWindow.classList.add('chat-hide');

    // 3. Reset tombol
    chatBtn.classList.remove('rotate-180');

    // 4. Tunggu animasi CSS selesai (0.4s = 400ms), baru set visibility: hidden
    setTimeout(() => {
        chatWindow.classList.add('chat-closed');
        isChatAnimating = false;
    }, 400); // Sesuaikan angka ini dengan CSS transition duration
}

window.sendMessage = async function() {
    const input    = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    const sendBtn  = document.getElementById('send-btn');
    const text     = input.value.trim();

    if (!text) return;

    appendChatMessage('user', text);
    saveChatHistory('user', text);
    input.value      = '';
    sendBtn.disabled = true;
    showChatLoading();

    try {
        const res = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: text })
        });

        if (!res.ok) throw new Error('Request gagal');

        const data = await res.json();
        hideChatLoading();

        if (data.reply) {
            appendChatMessage('bot', data.reply);
            saveChatHistory('bot', data.reply, data.products ?? []);
        }

        if (data.products && data.products.length > 0) {
            appendChatProductCards(data.products);
        }

    } catch (error) {
        hideChatLoading();
        appendChatMessage('bot', 'Maaf, terjadi kesalahan. Silakan coba lagi.');
    }

    messages.scrollTop = messages.scrollHeight;
    sendBtn.disabled   = false;
}

function appendChatMessage(type, content) {
    const messages = document.getElementById('chat-messages');
    const isUser   = type === 'user';

    const wrapper        = document.createElement('div');
    wrapper.className    = isUser ? 'flex justify-end' : 'flex justify-start';

    const bubble         = document.createElement('div');
    bubble.className     = isUser
        ? 'bg-primary text-white text-sm px-3 py-2 rounded-2xl rounded-tr-sm max-w-[85%]'
        : 'bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%] prose prose-invert prose-sm';

    bubble.innerHTML = isUser
        ? escapeHtml(content)
        : (window.marked ? marked.parse(content) : content);

    wrapper.appendChild(bubble);
    messages.appendChild(wrapper);
    messages.scrollTop = messages.scrollHeight;
}

function appendChatProductCards(products) {
    const messages      = document.getElementById('chat-messages');
    const wrapper       = document.createElement('div');
    wrapper.className   = 'flex justify-start w-full';

    const container     = document.createElement('div');
    container.className = 'flex flex-col gap-2 w-full max-w-[85%]';

    products.forEach(p => {
        const card      = document.createElement('a');
        card.href       = `/products/${p.slug}`;
        card.className  = 'flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-primary rounded-xl p-2 transition-all group';
        card.innerHTML  = `
            <img src="${p.image ?? 'https://via.placeholder.com/60'}"
                 class="w-12 h-12 object-cover rounded-lg shrink-0"
                 onerror="this.src='https://via.placeholder.com/60'">
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-bold truncate group-hover:text-primary transition-colors">${p.name}</p>
                <p class="text-gray-400 text-[10px] truncate">${p.category}</p>
                <p class="text-primary text-xs font-bold">${p.price}</p>
            </div>
            <span class="material-symbols-outlined text-gray-400 text-sm shrink-0">arrow_forward_ios</span>`;

        container.appendChild(card);
    });

    wrapper.appendChild(container);
    messages.appendChild(wrapper);
    messages.scrollTop = messages.scrollHeight;
}

function showChatLoading() {
    const messages    = document.getElementById('chat-messages');
    const loading     = document.createElement('div');
    loading.id        = 'chat-loading';
    loading.className = 'flex justify-start';
    loading.innerHTML = `
        <div class="bg-white/10 text-gray-400 text-sm px-4 py-3 rounded-2xl rounded-tl-sm flex gap-1 items-center">
            <span class="chat-dot w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
            <span class="chat-dot w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
            <span class="chat-dot w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
        </div>`;
    messages.appendChild(loading);
    messages.scrollTop = messages.scrollHeight;
}

function hideChatLoading() {
    document.getElementById('chat-loading')?.remove();
}

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

    // Simpan riwayat ke localStorage
function saveChatHistory(type, content, products = null) {
    const history = getChatHistory();
    history.push({ type, content, products, time: Date.now() });
    // Batasi 50 pesan agar tidak terlalu besar
    if (history.length > 50) history.shift();
    localStorage.setItem('saka_chat_history', JSON.stringify(history));
}

// Ambil riwayat dari localStorage
function getChatHistory() {
    try {
        return JSON.parse(localStorage.getItem('saka_chat_history') ?? '[]');
    } catch {
        return [];
    }
}

window.clearChatHistory = function() {
    localStorage.removeItem('saka_chat_history');
    const messages = document.getElementById('chat-messages');
    messages.innerHTML = `
        <div class="flex justify-start">
            <div class="bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%]">
                Halo! Saya <strong>SAKA</strong>, asisten virtual toko ini. Ada yang bisa saya bantu? 👋
            </div>
        </div>`;
}

// Render ulang riwayat saat halaman dibuka
function loadChatHistory() {
    const history = getChatHistory();
    if (history.length === 0) return;

    history.forEach(item => {
        appendChatMessage(item.type, item.content);
        if (item.products && item.products.length > 0) {
            appendChatProductCards(item.products);
        }
    });
}

// Tutup chat saat klik di luar
document.addEventListener('click', (e) => {
    const chatWindow = document.getElementById('chat-window');
    const chatBtn = document.getElementById('chat-toggle-btn');

    if (!chatWindow || !chatBtn) return;

    // Logika: Jika chat SEDANG BUKA (chat-show) dan klik BUKAN di window/tombol
    if (chatWindow.classList.contains('chat-show') && 
        !chatWindow.contains(e.target) && 
        !chatBtn.contains(e.target)) {
        
        closeChat();
    }
});

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