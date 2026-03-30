import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

/**
 * ==========================================
 * HELPER: Parse response JSON dengan aman
 * Jika server return HTML error page (bukan JSON),
 * .json() akan throw — ini menangkapnya dengan pesan yang jelas
 * ==========================================
 */
async function parseJsonResponse(res) {
    const contentType = res.headers.get("content-type") || "";
    if (!contentType.includes("application/json")) {
        throw new Error(`Server returned non-JSON response (HTTP ${res.status}). Check server logs.`);
    }
    return res.json();
}

/**
 * ==========================================
 * 1. DYNAMIC TOAST SYSTEM
 * ==========================================
 */
window.showToast = function (message, type = "success") {
    const toastId = `toast-${Date.now()}`;
    const progressId = `${toastId}-progress`;

    const isSuccess = type === "success";
    const icon = isSuccess ? "check" : "priority_high";
    const title = isSuccess ? "SUCCESS" : "ERROR";

    const cart = document.getElementById("miniCart");
    const isCartOpen = cart && !cart.classList.contains("translate-x-full");
    const isMobile = window.innerWidth < 768;

    let positionClass = "bottom-5 right-5";
    let animateEnter = "translate-y-10";

    if (isCartOpen) {
        positionClass = isMobile ? "top-24 right-5" : "bottom-5 right-[480px]";
        if (isMobile) animateEnter = "-translate-y-10";
    }

    const borderClass   = isSuccess ? "border-blue-600/50"      : "border-red-600/50";
    const iconBgClass   = isSuccess ? "bg-blue-600/20 text-blue-600" : "bg-red-600/20 text-red-600";
    const titleClass    = isSuccess ? "text-blue-600"            : "text-red-600";
    const progressClass = isSuccess ? "bg-blue-600"              : "bg-red-600";

    document.body.insertAdjacentHTML("beforeend", `
        <div id="${toastId}" class="fixed ${positionClass} z-[200] flex items-center gap-4 bg-[#0a0a0a] border ${borderClass} text-white px-6 py-4 rounded-xl shadow-[0_0_30px_rgba(0,0,0,0.5)] transform transition-all duration-500 ${animateEnter} opacity-0 overflow-hidden">
            <div class="flex items-center justify-center w-8 h-8 ${iconBgClass} rounded-full">
                <span class="material-symbols-outlined text-xl">${icon}</span>
            </div>
            <div>
                <h4 class="font-bold text-sm ${titleClass} uppercase tracking-wider">${title}</h4>
                <p class="text-gray-300 text-xs mt-0.5 whitespace-nowrap">${message}</p>
            </div>
            <button type="button" class="ml-4 text-gray-500 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
            <div id="${progressId}" class="absolute bottom-0 left-0 h-[3px] ${progressClass} w-full transition-all duration-[3000ms] ease-linear"></div>
        </div>
    `);

    const toastEl    = document.getElementById(toastId);
    const progressEl = document.getElementById(progressId);

    const closeToast = () => {
        if (!toastEl) return;
        toastEl.classList.add(animateEnter, "opacity-0");
        setTimeout(() => toastEl.remove(), 500);
    };

    requestAnimationFrame(() => {
        toastEl.classList.remove("translate-y-10", "-translate-y-10", "opacity-0");
        setTimeout(() => {
            progressEl.classList.remove("w-full");
            progressEl.classList.add("w-0");
        }, 50);
    });

    const timer = setTimeout(closeToast, 3000);
    toastEl.querySelector("button")?.addEventListener("click", () => {
        clearTimeout(timer);
        closeToast();
    });
};

document.addEventListener("DOMContentLoaded", function () {
    const flashDiv = document.getElementById("flash-messages");
    if (!flashDiv) return;
    const success    = flashDiv.dataset.success;
    const error      = flashDiv.dataset.error;
    const validation = flashDiv.dataset.validation;
    if (success)    window.showToast(success, "success");
    else if (error) window.showToast(error, "error");
    else if (validation) window.showToast(validation, "error");
});

/**
 * ==========================================
 * 2. MINI CART TOGGLE
 * ==========================================
 */
window.toggleMiniCart = function () {
    const cart    = document.getElementById("miniCart");
    const overlay = document.getElementById("miniCartOverlay");
    if (!cart || !overlay) return;

    const isHidden = cart.classList.contains("translate-x-full");
    if (isHidden) {
        cart.classList.remove("translate-x-full");
        overlay.classList.remove("hidden");
        setTimeout(() => overlay.classList.remove("opacity-0"), 10);
    } else {
        cart.classList.add("translate-x-full");
        overlay.classList.add("opacity-0");
        setTimeout(() => overlay.classList.add("hidden"), 300);
    }
};

/**
 * ==========================================
 * 3. UPDATE MINI CART UI
 * Fungsi ini selalu menerima flat object { cartHtml, cartCount, subtotal, ... }
 * Caller bertanggung jawab meng-extract response.data sebelum memanggil ini
 * ==========================================
 */
window.updateMiniCartUI = function (data) {
    // Mini cart elements
    const itemsContainer = document.getElementById("miniCartItems");
    const subtotalEl     = document.getElementById("miniCartSubtotal");

    // Cart page elements (sync jika user sedang di halaman cart)
    const cartPageCount     = document.getElementById("cart-page-count");
    const cartPageSubtotal  = document.getElementById("summary-subtotal");
    const cartPageTax       = document.getElementById("summary-tax");
    const cartPageGrandTotal= document.getElementById("summary-grand-total");

    if (itemsContainer)      itemsContainer.innerHTML = data.cartHtml;
    if (subtotalEl)          subtotalEl.innerText     = data.subtotal;
    if (cartPageCount)       cartPageCount.innerText  = data.cartCount;
    if (cartPageSubtotal)    cartPageSubtotal.innerText  = data.subtotal;
    if (cartPageTax)         cartPageTax.innerText       = data.tax;
    if (cartPageGrandTotal)  cartPageGrandTotal.innerText= data.grand_total;

    // Checkout button state
    const checkoutBtn = document.getElementById("miniCartCheckoutBtn");
    const btnText     = document.getElementById("checkoutBtnText");
    const btnIcon     = document.getElementById("checkoutBtnIcon");

    if (checkoutBtn) {
        const count = data.cartCount ?? 0;
        if (count === 0) {
            checkoutBtn.classList.remove("bg-primary", "hover:bg-blue-600", "text-white", "shadow-[0_0_15px_rgba(59,130,246,0.4)]");
            checkoutBtn.classList.add("bg-white/10", "text-gray-600", "cursor-not-allowed", "pointer-events-none");
            if (btnText) btnText.innerText = "Empty";
            if (btnIcon) btnIcon.innerText = "lock";
            checkoutBtn.setAttribute("href", "#");
        } else {
            checkoutBtn.classList.remove("bg-white/10", "text-gray-600", "cursor-not-allowed", "pointer-events-none");
            checkoutBtn.classList.add("bg-primary", "hover:bg-blue-600", "text-white", "shadow-[0_0_15px_rgba(59,130,246,0.4)]");
            if (btnText) btnText.innerText = "Checkout";
            if (btnIcon) btnIcon.innerText = "arrow_forward";
            checkoutBtn.setAttribute("href", "/checkout");
        }
    }

    // Cart badge di navbar
    const cartBadge = document.getElementById("cart-badge");
    const cartCount = document.getElementById("cart-count");
    const count     = data.cartCount ?? 0;

    if (cartBadge) cartBadge.classList.toggle("hidden", count === 0);
    if (cartCount) {
        cartCount.classList.toggle("hidden", count === 0);
        cartCount.classList.toggle("flex", count > 0);
        cartCount.innerText = count;
    }

    // Hapus baris cart jika ada removedId
    if (data.removedId) {
        document.getElementById(`cart-row-${data.removedId}`)?.remove();
    }

    // Reload jika cart kosong dan kita di halaman cart
    if (count === 0 && document.getElementById("cart-page-count")) {
        location.reload();
    }
};

/**
 * ==========================================
 * 4. ADD TO CART (AJAX)
 * ==========================================
 */
window.addToCartAjax = function (e, form) {
    e.preventDefault();

    const btn          = form.querySelector('button[type="submit"]');
    const originalHTML = btn.innerHTML;

    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
    btn.disabled  = true;

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: new FormData(form),
    })
        .then(parseJsonResponse)                    // ← parse aman, throw jika bukan JSON
        .then((response) => {
            if (response.success) {
                const data = response.data;         // ← extract sekali di sini
                window.updateMiniCartUI(data);      // ← updateMiniCartUI terima flat object
                window.toggleMiniCart();
                window.showToast("Product added to your rig successfully!");
            } else {
                window.showToast(response.message || "Failed to add product", "error");
            }
        })
        .catch((err) => {
            console.error("addToCartAjax error:", err);
            window.showToast(err.message || "System Error: Check Console", "error");
        })
        .finally(() => {
            btn.innerHTML = originalHTML;
            btn.disabled  = false;
        });
};

/**
 * ==========================================
 * 5. REMOVE CART ITEM (dari mini cart)
 * ==========================================
 */
window.removeCartItem = function (id) {
    const itemElement = document.getElementById(`cart-item-${id}`);
    if (itemElement) {
        itemElement.style.opacity      = "0.3";
        itemElement.style.pointerEvents= "none";
    }

    fetch(`/cart/${id}`, {
        method: "DELETE",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
    })
        .then(parseJsonResponse)
        .then((response) => {
            if (response.success) {
                itemElement?.remove();
                const data = response.data;
                // removedId ada di root response (bukan di data), inject ke data
                window.updateMiniCartUI({ ...data, removedId: response.removedId });
                window.showToast("Item removed from cart");
            } else {
                if (response.data?.cartHtml !== undefined) {
                    window.updateMiniCartUI(response.data);
                }
                if (itemElement) {
                    itemElement.style.opacity      = "1";
                    itemElement.style.pointerEvents= "auto";
                }
                window.showToast(response.message || "Failed to remove", "error");
            }
        })
        .catch((err) => {
            console.error("removeCartItem error:", err);
            window.showToast("Failed to remove item", "error");
            if (itemElement) itemElement.style.opacity = "1";
        });
};

/**
 * ==========================================
 * 6. UPDATE QUANTITY (dari mini cart)
 * ==========================================
 */
window.updateCartQuantity = function (id, change) {
    fetch("/cart/update", {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ id, change }),
    })
        .then(parseJsonResponse)
        .then((response) => {
            if (response.success) {
                const data = response.data;
                window.updateMiniCartUI(data);
                const qtyDisplay = document.getElementById(`qty-display-${id}`);
                if (qtyDisplay) qtyDisplay.innerText = data.item_quantity;
            } else {
                window.showToast(response.message || "Error updating cart", "error");
            }
        })
        .catch((err) => {
            console.error("updateCartQuantity error:", err);
            window.showToast("Failed to connect to server", "error");
        });
};

/**
 * ==========================================
 * 7. UPDATE QUANTITY (dari halaman cart utama)
 * FIX: tambah error handling + sync state tombol minus
 * ==========================================
 */
window.updateMainCartItem = function (id, change) {
    const qtySpan = document.getElementById(`qty-display-${id}`);
    if (qtySpan) qtySpan.style.opacity = "0.5";

    fetch("/cart/update", {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ id, change }),
    })
        .then(parseJsonResponse)
        .then((response) => {
            if (response.success) {
                const data   = response.data;
                const newQty = data.item_quantity ?? 1;

                if (qtySpan) {
                    qtySpan.innerText     = newQty;
                    qtySpan.style.opacity = "1";
                }

                // Sync state tombol minus — enable/disable berdasarkan qty baru
                const row      = document.getElementById(`cart-row-${id}`);
                const minusBtn = row?.querySelector(`button[onclick="updateMainCartItem('${id}', -1)"]`);
                if (minusBtn) {
                    if (newQty <= 1) {
                        minusBtn.classList.add("opacity-30", "pointer-events-none");
                    } else {
                        minusBtn.classList.remove("opacity-30", "pointer-events-none");
                    }
                }

                // Update summary sidebar
                const el = (elId) => document.getElementById(elId);
                if (el("summary-subtotal"))    el("summary-subtotal").innerText    = data.subtotal;
                if (el("summary-tax"))         el("summary-tax").innerText         = data.tax;
                if (el("summary-grand-total")) el("summary-grand-total").innerText = data.grand_total;

                window.updateMiniCartUI(data);
                window.showToast("Cart updated");
            } else {
                if (qtySpan) qtySpan.style.opacity = "1";
                window.showToast(response.message || "Failed to update cart", "error");
            }
        })
        .catch((err) => {
            console.error("updateMainCartItem error:", err);
            if (qtySpan) qtySpan.style.opacity = "1";
            window.showToast("Failed to connect to server", "error");
        });
};

/**
 * ==========================================
 * 8. REMOVE ITEM (dari halaman cart utama)
 * Dipanggil oleh confirmDeleteBtn di cart/index.blade.php
 * ==========================================
 */
window.executeRemoveCartItem = function (id) {
    const row = document.getElementById(`cart-row-${id}`);
    if (row) {
        row.style.transition = "all 0.5s";
        row.style.opacity    = "0";
        row.style.transform  = "translateX(50px)";
    }

    fetch(`/cart/${id}`, {
        method: "DELETE",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
    })
        .then(parseJsonResponse)
        .then((response) => {
            if (response.success) {
                const data = response.data;

                setTimeout(() => row?.remove(), 500);

                const el = (elId) => document.getElementById(elId);
                if (el("summary-subtotal"))   el("summary-subtotal").innerText   = data.subtotal;
                if (el("summary-tax"))        el("summary-tax").innerText        = data.tax;
                if (el("summary-grand-total"))el("summary-grand-total").innerText= data.grand_total;

                window.updateMiniCartUI(data);

                if (data.cartCount === 0) location.reload();
            } else {
                if (row) {
                    row.style.opacity  = "1";
                    row.style.transform= "none";
                }
                window.showToast(response.message || "Failed to remove", "error");
            }
        })
        .catch((err) => {
            console.error("executeRemoveCartItem error:", err);
            if (row) {
                row.style.opacity  = "1";
                row.style.transform= "none";
            }
            window.showToast("Failed to remove item", "error");
        });
};

// Alias untuk backward compatibility
window.removeMainCartItem = window.executeRemoveCartItem;

/**
 * ==========================================
 * 9. CHATBOT (NexRig Assistant)
 * ==========================================
 */
let chatHistoryLoaded = false;
let isChatAnimating   = false;

window.toggleChat = function () {
    const chatWindow = document.getElementById("chat-window");
    chatWindow.classList.contains("chat-show") ? closeChat() : openChat();
};

function openChat() {
    if (isChatAnimating) return;
    const chatWindow = document.getElementById("chat-window");
    const chatBtn    = document.getElementById("chat-toggle-btn");

    chatWindow.style.display = "flex";
    chatWindow.setAttribute("aria-hidden", "false");
    chatWindow.classList.remove("chat-closed", "chat-hide");

    if (!chatHistoryLoaded) {
        loadChatHistory();
        chatHistoryLoaded = true;
        if (getChatHistory().length === 0) window.clearChatHistory();
    }

    requestAnimationFrame(() => chatWindow.classList.add("chat-show"));
    chatBtn.classList.add("rotate-360");
    setTimeout(() => document.getElementById("chat-input").focus(), 100);
}

window.closeChat = function () {
    const chatWindow = document.getElementById("chat-window");
    const chatBtn    = document.getElementById("chat-toggle-btn");

    if (chatWindow.classList.contains("chat-closed")) return;

    isChatAnimating = true;
    chatWindow.classList.remove("chat-show");
    chatWindow.classList.add("chat-hide");
    chatBtn.classList.remove("rotate-360");

    setTimeout(() => {
        chatWindow.classList.add("chat-closed");
        chatWindow.style.display = "none";
        chatWindow.setAttribute("aria-hidden", "true");
        isChatAnimating = false;
    }, 400);
};

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

    // Create bot bubble immediately
    const botBubble = document.createElement('div');
    botBubble.className = 'flex justify-start';
    botBubble.innerHTML = `
        <div class="bot-bubble bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%] prose prose-invert prose-sm">
            <span class="chat-dot w-1.5 h-1.5 bg-gray-400 rounded-full inline-block animate-pulse"></span>
        </div>`;
    messages.appendChild(botBubble);
    messages.scrollTop = messages.scrollHeight;

    const bubble = botBubble.querySelector('.bot-bubble');
    let fullText = '';
    let streamDone = false;

    try {
        const res = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: text })
        });

        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        bubble.innerHTML = '';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            const chunk = decoder.decode(value, { stream: true });
            const lines = chunk.split('\n');

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    const data = line.slice(6);
                    if (data === '[DONE]') break;

                    try {
                        const json = JSON.parse(data);
                        if (json.token) {
                            fullText += json.token;
                            if (!streamDone) {
                                const displayText = fullText.replace(/\s*\[PRODUCTS\][\s\S]*?(\[\/PRODUCTS\])?\s*/g, '').trim();
                                bubble.innerHTML = window.marked 
                                    ? marked.parse(displayText) 
                                    : displayText;
                                messages.scrollTop = messages.scrollHeight;
                            }
                        }
                    } catch {}
                }
            }
        }

        streamDone = true;
        // After stream ends, extract product cards if any

        const productMatch = fullText.match(/\[PRODUCTS\]\s*([\s\S]*?)\s*\[\/PRODUCTS\]/);
        if (productMatch) {
            const cleanText = fullText.replace(/\s*\[PRODUCTS\][\s\S]*?\[\/PRODUCTS\]\s*/g, '').trim();
            bubble.innerHTML = window.marked ? marked.parse(cleanText) : cleanText;

            const slugs = JSON.parse(productMatch[1]) ?? [];
            if (slugs.length > 0) {
                // Fetch product cards from server
                const cardRes = await fetch('/chatbot/products', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ slugs: slugs.map(s => s.slug) })
                });
                const cardData = await cardRes.json();
                if (cardData.products?.length > 0) {
                    appendChatProductCards(cardData.products);
                    saveChatHistory('bot', fullText, cardData.products);
                    return; // exit early, history already saved with products
                }
            }
        }

        // Only save history here if no products were found
        saveChatHistory('bot', fullText);

    } catch (error) {
        bubble.innerHTML = 'Maaf, terjadi kesalahan. Silakan coba lagi.';
    }

    messages.scrollTop = messages.scrollHeight;
    sendBtn.disabled = false;
}

function appendChatMessage(type, content) {
    const messages = document.getElementById("chat-messages");
    const isUser   = type === "user";
    const wrapper  = document.createElement("div");
    wrapper.className = isUser ? "flex justify-end" : "flex justify-start";

    const bubble  = document.createElement("div");
    bubble.className = isUser
        ? "bg-primary text-white text-sm px-3 py-2 rounded-2xl rounded-tr-sm max-w-[85%]"
        : "bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%] prose prose-invert prose-sm";
    bubble.innerHTML = isUser
        ? escapeHtml(content)
        : window.marked ? marked.parse(content) : content;

    wrapper.appendChild(bubble);
    messages.appendChild(wrapper);
    messages.scrollTop = messages.scrollHeight;
}

function appendChatProductCards(products) {
    const messages  = document.getElementById("chat-messages");
    const wrapper   = document.createElement("div");
    wrapper.className = "flex justify-start w-full";
    const container = document.createElement("div");
    container.className = "flex flex-col gap-2 w-full max-w-[85%]";

    products.forEach((p) => {
        const card    = document.createElement("a");
        card.href     = `/products/${p.slug}`;
        card.className= "flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-primary rounded-xl p-2 transition-all group";
        card.innerHTML= `
            <img src="${p.image ?? "https://via.placeholder.com/60"}" class="w-12 h-12 object-cover rounded-lg shrink-0" onerror="this.src='https://via.placeholder.com/60'">
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
    const messages = document.getElementById("chat-messages");
    const loading  = document.createElement("div");
    loading.id     = "chat-loading";
    loading.className = "flex justify-start";
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
    document.getElementById("chat-loading")?.remove();
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;").replace(/</g, "&lt;")
        .replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

function saveChatHistory(type, content, products = null) {
    const cleanContent = type === 'bot'
        ? content.replace(/\s*\[PRODUCTS\][\s\S]*?\[\/PRODUCTS\]\s*/g, '').trim()
        : content;
    const history = getChatHistory();
    history.push({ type, content: cleanContent, products, time: Date.now() });
    if (history.length > 50) history.shift();
    localStorage.setItem("nexrig_chat_history", JSON.stringify(history));
}

function getChatHistory() {
    try { return JSON.parse(localStorage.getItem("nexrig_chat_history") ?? "[]"); }
    catch { return []; }
}

window.clearChatHistory = function () {
    localStorage.removeItem("nexrig_chat_history");
    document.getElementById("chat-messages").innerHTML = `
        <div class="flex justify-start">
            <div class="bg-white/10 text-white text-sm px-3 py-2 rounded-2xl rounded-tl-sm max-w-[85%]">
                Halo! Saya <strong>NexRig</strong>, asisten virtual toko ini. Ada yang bisa saya bantu? 👋
            </div>
        </div>`;
};

function loadChatHistory() {
    getChatHistory().forEach((item) => {
        appendChatMessage(item.type, item.content);
        if (item.products?.length > 0) appendChatProductCards(item.products);
    });
}

document.addEventListener("click", (e) => {
    const chatWindow = document.getElementById("chat-window");
    const chatBtn    = document.getElementById("chat-toggle-btn");
    if (!chatWindow || !chatBtn) return;
    if (chatWindow.classList.contains("chat-show") && !chatWindow.contains(e.target) && !chatBtn.contains(e.target)) {
        closeChat();
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver(
        (entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-reveal");
                entry.target.classList.remove("opacity-0");
            }
        }),
        { threshold: 0.1 }
    );
    document.querySelectorAll(".scroll-trigger").forEach((el) => observer.observe(el));
});

/**
 * ==========================================
 * ARTICLE DETAIL — SCROLL & TOC
 * ==========================================
 */
document.addEventListener("DOMContentLoaded", function () {
    if (!document.getElementById("mainContent")) return;

    const progressBar = document.getElementById("myBar");
    const heroImage   = document.getElementById("heroImage");
    let ticking = false;

    window.addEventListener("scroll", () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const winScroll = document.documentElement.scrollTop;
            const height    = document.documentElement.scrollHeight - document.documentElement.clientHeight;

            if (progressBar) progressBar.style.width = Math.min((winScroll / height) * 100, 100) + "%";
            if (heroImage && window.innerWidth > 768 && winScroll < window.innerHeight) {
                heroImage.style.transform = `translateY(${winScroll * 0.3}px) scale(1.1)`;
            }
            updateActiveToc();
            ticking = false;
        });
    });

    const mainContent  = document.getElementById("mainContent");
    const tocList      = document.getElementById("toc-list");
    const tocContainer = document.getElementById("toc-container");
    let headingElements = [];

    if (mainContent && tocList) {
        const headings = mainContent.querySelectorAll("h2");
        if (headings.length > 0) {
            headings.forEach((heading, index) => {
                const id  = `section-${index}`;
                heading.id = id;
                headingElements.push(heading);
                const num = String(index + 1).padStart(2, "0");
                const li  = document.createElement("li");
                li.innerHTML = `
                    <a href="#${id}" class="toc-link flex items-center gap-3 py-2 px-2 rounded-lg" style="font-size:12px; color:#6b7280; text-decoration:none;">
                        <span class="toc-num font-mono shrink-0" style="font-size:10px; color:rgba(19,55,236,0.4);">${num}</span>
                        <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${heading.innerText}</span>
                    </a>`;
                tocList.appendChild(li);
            });

            tocList.addEventListener("click", (e) => {
                const link = e.target.closest(".toc-link");
                if (!link) return;
                e.preventDefault();
                document.getElementById(link.getAttribute("href").slice(1))
                    ?.scrollIntoView({ behavior: "smooth", block: "start" });
            });
        } else if (tocContainer) {
            tocContainer.style.display = "none";
        }
    }

    function updateActiveToc() {
        if (!headingElements.length) return;
        let activeIndex = 0;
        headingElements.forEach((h, i) => { if (h.getBoundingClientRect().top <= 110) activeIndex = i; });
        document.querySelectorAll(".toc-link").forEach((link, i) => {
            const num = link.querySelector(".toc-num");
            if (i === activeIndex) {
                link.classList.add("is-active");
                link.style.color = "white";
                if (num) num.style.color = "#1337ec";
            } else {
                link.classList.remove("is-active");
                link.style.color = "#6b7280";
                if (num) num.style.color = "rgba(19,55,236,0.4)";
            }
        });
    }

    const copyBtn = document.getElementById("copyLinkBtn");
    if (copyBtn) {
        copyBtn.addEventListener("click", async () => {
            try { await navigator.clipboard.writeText(window.location.href); }
            catch {
                const ta = document.createElement("textarea");
                ta.value = window.location.href;
                Object.assign(ta.style, { position: "fixed", opacity: "0" });
                document.body.appendChild(ta);
                ta.select();
                document.execCommand("copy");
                document.body.removeChild(ta);
            }
            window.showToast("Link copied to clipboard!");
        });
    }
});