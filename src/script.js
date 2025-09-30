document.querySelectorAll(".item-link").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault(); // cegah langsung pindah

        // ambil input akun
        let idAkun = document.querySelector("input[name='id_akun_game']");
        let idZona = document.querySelector("input[name='id_zona_game']");
        let username = document.querySelector("input[name='username_efootball']");

        if ((idAkun && idAkun.value.trim() === "") ||
            (idZona && idZona.required && idZona.value.trim() === "") ||
            (username && username.value.trim() === "")) {
            alert("Harap isi data akun terlebih dahulu!");
            return;
        }

        // kalau valid, arahkan ke pembayaran.php
        window.location.href = this.getAttribute("href");
    });
});

// MODAL PEMBAYARAN

function openPembayaran(idItem) {
    fetch("pembayaran.php?id_item=" + idItem)
        .then(res => res.text())
        .then(html => {
            document.getElementById("popupPembayaranContent").innerHTML = html;
            document.getElementById("popupPembayaran").classList.remove("hidden");

            // handle submit form pembayaran
            const form = document.querySelector("#popupPembayaranContent form");
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Pesanan berhasil disimpan!");
                            closePembayaran();
                            // tandai item sudah dibayar (disable link)
                            const link = document.querySelector(`[data-item='${idItem}']`);
                            if (link) {
                                link.removeAttribute("href");
                                link.classList.add("opacity-50", "pointer-events-none");
                            }
                        } else {
                            alert("Gagal: " + data.message);
                        }
                    });
                });
            }
        });
}

function closePembayaran() {
    document.getElementById("popupPembayaran").classList.add("hidden");
    document.getElementById("popupPembayaranContent").innerHTML = "";
}

// bind ke semua item
document.querySelectorAll(".item-link").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        const idItem = this.getAttribute("data-item");
        openPembayaran(idItem);
    });
});