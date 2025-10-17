<div class="flex flex-col items-center text-center space-y-12">
    <h2 class="text-5xl font-extrabold text-[#ffed00] text-center mb-4">GAMES</h2>

    <div class="flex space-x-2 md:space-x-6 translate-y-[50px]">
        <!-- Card 1 -->
        <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[150px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
            <button onclick="openGamePopup('mlbb')">
                <img src="assets/mlbb-logo.jpg" alt="Mobile Legends" class="rounded-lg">
                <p class="mt-2">Mobile Legends: bang bang</p>
            </button>
        </div>

        <!-- Card 2 -->
        <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[150px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
            <button onclick="openGamePopup('efootball')">
                <img src="assets/efootball-logo.jpg" alt="Efootball" class="rounded-lg">
                <p class="mt-2">eFootball™</p>
            </button>
        </div>

        <!-- Card 3 -->
        <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[150px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
            <button onclick="openGamePopup('ff')">
                <img src="assets/ff-logo.jpg" alt="Free Fire" class="rounded-lg">
                <p class="mt-2">Free Fire: Rampage</p>
            </button>
        </div>
    </div>
</div>

<script>
    function openGamePopup(game) {
        // load ulang isi edit_akun_game.php sesuai game
        fetch("edit_akun_game.php?game=" + game)
            .then(res => res.text())
            .then(html => {
                document.getElementById("idGamePopupContent").innerHTML = html;
                document.getElementById("idGamePopup").classList.remove("hidden");
            });
    }
</script>