<!-- layout.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>My Laravel App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/css/style.css')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="bg-gradient-to-r from-gray-600 to-blue-900">
    <nav class="p-5 bg-gradient-to-r from-gray-600 to-blue-900 md:flex md:items-center md:justify-between ">
        <div class="ml-20 flex justify-between items-center">
            <span class="text-2xl font-[poppins] cursor-pointer">
                <p class="text-green-600">ApprovedByMe</p>
            </span>

            <span class="text-3x1 cursor-pointer mx-2 md:hidden block">
                <ion-icon name="menu" onclick="Menu(this)"></ion-icon>
            </span>
        </div>

        <ul class="md:flex md:items-center z-[-1] md:z-auto md:static absolute bg-green-600 w-full left-0 md:w-auto md:py-0 py-4 md:pl-0 pl-7 md:opacity-100 opacity-0 top-[-400px] transition-all ease-in duration-500">
            <li class="mx-4 text-white">
                <a href="{{ route('home') }}" class="text-xl hover:text-cyan-500 duration-500">Home</a>
            </li>
            <li class="mx-4 text-white">
                <a href="#" class="text-xl hover:text-cyan-500 duration-500">History</a>
            </li>
            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Dropdown button <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                    <a href="{{ route('seminar.create') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sempro</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Semhas</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sidang</a>
                </li>
                </ul>
            </div>
            <li class="mx-4 text-white">
                <a href="#" class="text-xl hover:text-cyan-500 duration-500">Profile</a>
            </li>
        </ul>
    </nav>

    <script>
        function Menu(e){
            let list = document.querySelector('ul');

            e.name === 'menu' ? (e.name = "close",list.classList.add('top-[80px]') , list.classList.add('opacity-100')) :(e.name = "menu" ,list.classList.remove('top-[80px]'),list.classList.remove('opacity-100'))
        }
    </script>
    

    <div class="container mt-10 ml-20 mr-20 text-white font-bold font-[montserrat]">
        @yield('content')
    </div>

    <script>
    // Ambil elemen tombol dropdown
    const dropdownButton = document.getElementById('dropdownDefaultButton');

    // Tambahkan event listener untuk mengatur perilaku dropdown
    dropdownButton.addEventListener('click', function () {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    });
    </script>
</body>
</html>