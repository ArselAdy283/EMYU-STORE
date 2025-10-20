<div class="flex flex-col items-center text-center space-y-12 h-[500px] overflow-y-auto">
  <h2 class="text-5xl font-extrabold text-[#ffed00] text-center mb-4">GAMES</h2>

  <div class="flex md:flex-row flex-col md:space-x-6 space-y-4 md:space-y-0 translate-y-[30px]">
    <!-- Card 1 -->
    <div
      class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[230px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
      <button onclick="openGamePopup('mlbb')"
        class="flex sm:flex-row flex-row md:flex-col items-center md:items-center text-left md:text-center space-x-3 md:space-x-0 md:space-y-2 w-full">
        <img src="assets/mlbb-logo.jpg" alt="Mobile Legends"
          class="rounded-lg w-14 h-14 md:w-auto md:h-auto object-cover">
        <p class="text-sm md:text-base font-medium">Mobile Legends: Bang Bang</p>
      </button>
    </div>

    <!-- Card 2 -->
    <div
      class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[230px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
      <button onclick="openGamePopup('efootball')"
        class="flex sm:flex-row flex-row md:flex-col items-center md:items-center text-left md:text-center space-x-3 md:space-x-0 md:space-y-2 w-full">
        <img src="assets/efootball-logo.jpg" alt="Efootball"
          class="rounded-lg w-14 h-14 md:w-auto md:h-auto object-cover">
        <p class="text-sm md:text-base font-medium">eFootball™</p>
      </button>
    </div>

    <!-- Card 3 -->
    <div
      class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[230px] md:w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
      <button onclick="openGamePopup('ff')"
        class="flex sm:flex-row flex-row md:flex-col items-center md:items-center text-left md:text-center space-x-3 md:space-x-0 md:space-y-2 w-full">
        <img src="assets/ff-logo.jpg" alt="Free Fire"
          class="rounded-lg w-14 h-14 md:w-auto md:h-auto object-cover">
        <p class="text-sm md:text-base font-medium">Free Fire: Rampage</p>
      </button>
    </div>
  </div>
</div>

<script>
  function openGamePopup(game) {
    fetch("edit_akun_game.php?game=" + game)
      .then(res => res.text())
      .then(html => {
        document.getElementById("idGamePopupContent").innerHTML = html;
        document.getElementById("idGamePopup").classList.remove("hidden");
      });
  }
</script>
