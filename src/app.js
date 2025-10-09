// index.php

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

// admin.php

// ================================/item/=======================================================

function editItemPopup(item) {
    fetch("edit_item.php?id_item=" + item)
        .then(res => res.text())
        .then(html => {
            document.getElementById("editItemPopupContent").innerHTML = html;
            document.getElementById("editItemPopup").classList.remove("hidden");
        });
}

// =============================/announcement/================================================

const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');
const chatContainer = document.getElementById('chatContainer');
const chatWrapper = document.getElementById('chatInputWrapper');

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

// Shift + Enter = baris baru
chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.shiftKey) return;
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
});

// === KLIK KANAN PESAN ===
const contextMenu = document.getElementById('contextMenu');
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

document.addEventListener('click', () => {
    contextMenu.classList.add('hidden');
});

// Tombol Delete
document.getElementById('deleteMessageBtn').addEventListener('click', async () => {
    const res = await fetch('delete_announcement.php', {
        method: 'POST',
        body: new URLSearchParams({
            id: selectedMessageId
        })
    });
    if (res.ok) {
        document.querySelector(`.chat-message[data-id="${selectedMessageId}"]`)?.remove();
    }
    contextMenu.classList.add('hidden');
});

let editingMessageId = null;

// Tombol Edit
document.getElementById('editMessageBtn').addEventListener('click', () => {
    const msgElement = document.querySelector(`.chat-message[data-id="${selectedMessageId}"] p`);
    const oldText = msgElement.textContent.trim();
    chatInput.value = oldText;
    chatInput.focus();
    editingMessageId = selectedMessageId;

    chatInput.style.height = 'auto';
    chatInput.style.height = chatInput.scrollHeight + 'px';
    contextMenu.classList.add('hidden');
});

// === SUBMIT PESAN ===
chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const message = chatInput.value.trim();
    if (!message) return;

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
            // Ambil HTML pesan yang sudah diperbarui dari server
            const newMsgHTML = await res.text();
            const oldMsg = document.querySelector(`.chat-message[data-id="${editingMessageId}"]`);
            if (oldMsg) {
                // Ganti hanya elemen pesan, tidak pindah posisi
                oldMsg.outerHTML = newMsgHTML;
            }

            editingMessageId = null;
            chatInput.value = '';
            chatInput.style.height = 'auto';
        }
        return;
    }

    // === MODE PESAN BARU ===
    const formData = new FormData();
    formData.append('message', message);

    const res = await fetch('save_announcement.php', {
        method: 'POST',
        body: formData
    });

    if (res.ok) {
        const html = await res.text();

        // tambahkan pesan baru di bawah (akhir list)
        chatContainer.insertAdjacentHTML('beforeend', html);

        // scroll ke bawah untuk lihat pesan baru
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // reset input
        chatInput.value = '';
        chatInput.style.height = 'auto';
    }
    // Pastikan klik kanan aktif juga untuk pesan baru
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