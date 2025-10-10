// =====================================
// index.php popup functions
// =====================================
function githubPopup() {
    fetch("github.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("githubPopupContent").innerHTML = html;
            document.getElementById("githubPopup").classList.remove("hidden");
        });
}

function instagramPopup() {
    fetch("instagram.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("instagramPopupContent").innerHTML = html;
            document.getElementById("instagramPopup").classList.remove("hidden");
        });
}

// =====================================
// admin.php
// =====================================

// ================================ ITEM POPUP ================================
function editItemPopup(item) {
    fetch("edit_item.php?id_item=" + item)
        .then(res => res.text())
        .then(html => {
            document.getElementById("editItemPopupContent").innerHTML = html;
            document.getElementById("editItemPopup").classList.remove("hidden");
        });
}

// ============================= ANNOUNCEMENT CHAT ============================

const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');
const chatContainer = document.getElementById('chatContainer');
const chatWrapper = document.getElementById('chatInputWrapper');
const contextMenu = document.getElementById('contextMenu');
const deleteBtn = document.getElementById('deleteMessageBtn');
const editBtn = document.getElementById('editMessageBtn');
const chatImage = document.getElementById('chatImage');

// ==== Discord-like image preview (di atas textarea) ====
const previewInside = document.getElementById('imagePreviewInside');
const previewImgInside = document.getElementById('previewImgInside');
const removePreviewInside = document.getElementById('removePreviewInside');

// === Auto resize dan tetap "naik ke atas" ===
chatInput.addEventListener('input', () => {
    chatInput.style.height = 'auto';
    const scrollH = chatInput.scrollHeight;
    const maxHeight = 225;
    const newHeight = Math.min(scrollH, maxHeight);
    chatInput.style.height = newHeight + 'px';
    chatWrapper.style.height = newHeight + 'px';
    chatInput.style.overflowY = scrollH > maxHeight ? 'scroll' : 'hidden';
});

// === Shift + Enter untuk baris baru ===
chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.shiftKey) return;
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
});

// === KLIK KANAN PESAN ===
let selectedMessageId = null;

chatContainer.addEventListener('contextmenu', (e) => {
    const msg = e.target.closest('.chat-message');
    if (!msg) return;
    e.preventDefault();
    selectedMessageId = msg.dataset.id;
    contextMenu.style.top = e.pageY + 'px';
    contextMenu.style.left = e.pageX + 'px';
    contextMenu.classList.remove('hidden');
});

// Tutup context menu jika klik di luar
document.addEventListener('click', () => {
    contextMenu.classList.add('hidden');
});

// === DELETE PESAN ===
deleteBtn.addEventListener('click', async () => {
    const res = await fetch('delete_announcement.php', {
        method: 'POST',
        body: new URLSearchParams({ id: selectedMessageId })
    });

    if (res.ok) {
        document.querySelector(`.chat-message[data-id="${selectedMessageId}"]`)?.remove();
    }

    contextMenu.classList.add('hidden');
});

// === EDIT PESAN ===
let editingMessageId = null;

editBtn.addEventListener('click', () => {
    const msgElement = document.querySelector(`.chat-message[data-id="${selectedMessageId}"] p`);
    const oldText = msgElement.textContent.trim();
    chatInput.value = oldText;
    chatInput.focus();
    editingMessageId = selectedMessageId;

    chatInput.style.height = 'auto';
    chatInput.style.height = chatInput.scrollHeight + 'px';
    contextMenu.classList.add('hidden');
});

// === PREVIEW GAMBAR DI DALAM AREA INPUT (DISCORD STYLE) ===
chatImage.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (evt) => {
        previewImgInside.src = evt.target.result;
        previewInside.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

removePreviewInside.addEventListener('click', () => {
    chatImage.value = '';
    previewImgInside.src = '';
    previewInside.classList.add('hidden');
});

// === SUBMIT PESAN ===
chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = chatInput.value.trim();
    const imageFile = chatImage.files[0];

    if (!message && !imageFile) return;

    // === MODE EDIT ===
    if (editingMessageId) {
        const res = await fetch('edit_announcement.php', {
            method: 'POST',
            body: new URLSearchParams({
                id: editingMessageId,
                message: message
            })
        });

        if (res.ok) {
            const newMsgHTML = await res.text();
            const oldMsg = document.querySelector(`.chat-message[data-id="${editingMessageId}"]`);
            if (oldMsg) oldMsg.outerHTML = newMsgHTML;

            editingMessageId = null;
            chatInput.value = '';
            chatInput.style.height = 'auto';
        }
        return;
    }

    // === MODE PESAN BARU ===
    const formData = new FormData();
    formData.append('message', message);
    if (imageFile) formData.append('image', imageFile);

    const res = await fetch('save_announcement.php', {
        method: 'POST',
        body: formData
    });

    if (res.ok) {
        const html = await res.text();

        // tambahkan pesan baru di bawah
        chatContainer.insertAdjacentHTML('beforeend', html);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // reset input dan preview
        chatInput.value = '';
        chatInput.style.height = 'auto';
        chatImage.value = '';
        previewImgInside.src = '';
        previewInside.classList.add('hidden');
    }

    // aktifkan klik kanan untuk pesan baru
    const newMsg = chatContainer.lastElementChild;
    if (newMsg) {
        newMsg.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            selectedMessageId = newMsg.dataset.id;
            contextMenu.style.top = e.pageY + 'px';
            contextMenu.style.left = e.pageX + 'px';
            contextMenu.classList.remove('hidden');
        });
    }
});

// ==== POPUP GAMBAR ====
document.addEventListener("DOMContentLoaded", () => {
  const popup = document.getElementById("imagePopup");
  const popupImg = document.getElementById("popupImg");
  const closePopup = document.getElementById("closePopup");

  // 🔹 Delegasi event klik untuk gambar di dalam chatContainer
  document.getElementById("chatContainer").addEventListener("click", (e) => {
    if (e.target.tagName === "IMG") {
      popupImg.src = e.target.src;
      popup.classList.remove("hidden");
    }
  });

  // Tutup popup saat klik tombol X
  closePopup.addEventListener("click", () => {
    popup.classList.add("hidden");
    popupImg.src = "";
  });

  // Tutup popup saat klik area hitam luar
  popup.addEventListener("click", (e) => {
    if (e.target === popup) {
      popup.classList.add("hidden");
      popupImg.src = "";
    }
  });
});
