<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Dashboard</title>
  <link href="./output.css" rel="stylesheet">
  <style>
    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-Regular.ttf') format('truetype');
      font-weight: 400;
      font-style: normal;
    }

    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-SemiBold.ttf') format('truetype');
      font-weight: 600;
      font-style: normal;
    }

    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-Bold.ttf') format('truetype');
      font-weight: 700;
      font-style: normal;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background-attachment: fixed;
      min-height: 100vh;
      color: white;
    }
  </style>
</head>

<body class="bg-[color:#212121] overflow-x-hidden overflow-y-scroll">

  <!-- NAVBAR -->
  <nav id="navbar" class="flex justify-between items-center px-8 py-5 
            sticky top-0 z-50 transition-all duration-500 bg-[color:#18181c]">
    <div class="flex items-center space-x-8 h-[50px]">
      <div class="w-[80px] h-[80px]"></div>
      <div class="w-[80px] h-[80px]"></div>
      <div class="w-[80px] h-[80px]"></div>
    </div>
    <ul class="flex space-x-8 text-xl font-semibold text-white">
      <li><a href="index.php" class="hover:text-[#db2525] transition">Home</a></li>
      <li><a href="orders.php" class="hover:text-[#db2525] transition">Orders</a></li>
      <li><a href="#" class="text-[#db2525]">Inbox</a></li>
      <li><a href="account.php" class="hover:text-[#db2525] transition">Account</a></li>
    </ul>
  </nav>

  <!-- LAYOUT: SIDEBAR + MAIN -->
  <div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[color:#18181c] h-[calc(100vh-90px)] sticky top-[80px] flex flex-col">
      <div class="p-6 text-2xl font-bold text-[#db2525] border-b border-gray-700">
        Admin Dashboard
      </div>
      <nav class="flex-1 p-4 space-y-4">
        <a href="admin.php?page=order" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Order</a>
        <a href="admin.php?page=item" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Item</a>
        <a href="admin.php?page=inbox" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Inbox</a>
        <a href="admin.php?page=user" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">User</a>
      </nav>
      <div class="p-4 border-t border-gray-700">
        <button class="w-full py-2 bg-[#db2525] rounded-lg hover:bg-red-700 transition">
          Logout
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
      <header class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <div class="flex space-x-4">
          <div class="px-4 py-2 bg-[#18181c] rounded-lg">CPU Load: 8%</div>
          <div class="px-4 py-2 bg-[#18181c] rounded-lg">Memory: 1.9 / 6 GB</div>
        </div>
      </header>

      <!-- Example Content -->
      <section class="grid grid-cols-3 gap-6">
        <div class="bg-[#18181c] rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-2">Total Orders</h2>
          <p class="text-3xl font-bold text-[#db2525]">120</p>
        </div>
        <div class="bg-[#18181c] rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-2">Total Items</h2>
          <p class="text-3xl font-bold text-[#db2525]">58</p>
        </div>
        <div class="bg-[#18181c] rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-2">Total Users</h2>
          <p class="text-3xl font-bold text-[#db2525]">340</p>
        </div>
      </section>
    </main>

  </div>

</body>

</html>
