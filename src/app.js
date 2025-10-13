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
// emyucoin.php
// =====================================
function qrisPopup(id_emyucoin) {
    fetch("qris.php?id_emyucoin=" + id_emyucoin)
        .then(res => res.text())
        .then(html => {
            document.getElementById("qrisPopupContent").innerHTML = html;
            document.getElementById("qrisPopup").classList.remove("hidden");
        });
}

// === HANDLE FORM QRIS ===
document.addEventListener("change", function (e) {
    if (e.target && e.target.id === "bukti_transfer") {
        const form = e.target.closest("form");
        if (form) form.submit();
    }
});

// =====================================
// admin.php
// =====================================

// =============================== ORDERS POPUP ===============================
document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("buktiTransferPopup");
    const popupImg = document.getElementById("popupBukti");
    const closePopup = document.getElementById("closeBuktiTransferPopup");

    document.addEventListener("click", (e) => {
        const target = e.target;
        if (target.matches('a[data-bukti]')) {
            e.preventDefault();
            popupImg.src = target.getAttribute("data-bukti");
            popup.classList.remove("hidden");

            setTimeout(() => {
                popupImg.classList.remove("opacity-0", "scale-95");
                popupImg.classList.add("opacity-100", "scale-100");
            }, 10);
        }
    });

    closePopup.addEventListener("click", () => closePopupPopup());
    popup.addEventListener("click", (e) => { if (e.target === popup) closePopupPopup(); });

    function closePopupPopup() {
        popupImg.classList.add("opacity-0", "scale-95");
        popupImg.classList.remove("opacity-100", "scale-100");
        setTimeout(() => {
            popup.classList.add("hidden");
            popupImg.src = "";
        }, 200);
    }
});

// ================================ ITEM POPUP ================================
function editItemPopup(type, id) {
    const content = document.getElementById("editItemPopupContent");
    const popup = document.getElementById("editItemPopup");

    // Tentukan target file PHP
    const url = type === 'item' ? "edit_item.php" : "edit_emyucoin.php";

    // Kirim data ID lewat POST
    const formData = new URLSearchParams();
    if (type === 'item') {
        formData.append('id_item', id);
    } else {
        formData.append('id_emyucoin', id);
    }

    // Tampilkan popup dan loading
    popup.classList.remove("hidden");
    content.innerHTML = `<p class="text-center text-white">Memuat...</p>`;

    // Fetch konten popup
    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(html => {
            content.innerHTML = html;

            // ===== Tambahkan event tombol Hapus =====
            const hapusBtn = document.getElementById("hapusItemBtn");
            if (hapusBtn) {
                hapusBtn.addEventListener("click", () => {
                    const id_item = content.querySelector('input[name="id_item"]')?.value;
                    const id_emyucoin = content.querySelector('input[name="id_emyucoin"]')?.value;

                    const id = id_item || id_emyucoin;
                    const bodyData = id_item ? { id_item: id } : { id_emyucoin: id };

                    if (!id) return alert("Item tidak valid");
                    if (!confirm("Yakin ingin menghapus item ini?")) return;

                    fetch("hapus_item.php", {
                        method: "POST",
                        body: new URLSearchParams(bodyData)
                    })
                        .then(res => res.text())
                        .then(txt => {
                            alert(txt);
                            popup.classList.add("hidden");
                            location.reload();
                        });
                });
            }
        })
        .catch(err => {
            content.innerHTML = `<p class="text-red-500 text-center">Gagal memuat data: ${err}</p>`;
        });
}


function openAddPopup(type) {
    const popup = document.getElementById('editItemPopup');
    const content = document.getElementById('editItemPopupContent');
    popup.classList.remove('hidden');

    // Nama item otomatis berdasarkan id game
    let defaultNama = "";
    if (type == 1) defaultNama = "diamond";
    else if (type == 2) defaultNama = "coin";
    else if (type == 3) defaultNama = "diamond";

    if (type === 'emyucoin') {
        content.innerHTML = `
      <h2 class="text-2xl font-bold mb-4 text-[#db2525]">Tambah Item Emyucoin</h2>
      <form method="post" action="tambah_emyucoin.php" class="space-y-4">
        <div>
          <label class="block mb-1">Jumlah Emyucoin</label>
          <input type="number" name="jumlah" required class="w-full p-2 rounded bg-[#212121] text-white border border-gray-600">
        </div>
        <div>
          <label class="block mb-1">Harga (IDR)</label>
          <input type="number" name="harga" required class="w-full p-2 rounded bg-[#212121] text-white border border-gray-600">
        </div>
        <button type="submit" class="bg-[#db2525] hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Tambah</button>
      </form>`;
    } else {
        content.innerHTML = `
      <h2 class="text-2xl font-bold mb-4 text-[#db2525]">Tambah Item Game</h2>
      <form method="post" action="tambah_item.php" class="space-y-4">
        <input type="hidden" name="id_game" value="${type}">
        <div>
          <label class="block mb-1">Jumlah Item</label>
          <input type="text" name="jumlah_item" required class="w-full p-2 rounded bg-[#212121] text-white border border-gray-600">
        </div>
        <div>
          <label class="block mb-1">Harga (Emyucoin)</label>
          <input type="number" name="harga_item" required class="w-full p-2 rounded bg-[#212121] text-white border border-gray-600">
        </div>
        <button type="submit" class="bg-[#db2525] hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Tambah</button>
      </form>`;
    }
}
