<!-- layout.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>My Laravel App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <style>
        /* Apply the desired color to the active menu item */
        a[aria-current="page"] {
            color: green; /* Change this to the desired color */
        }

        body {
            font-family: 'Lato', sans-serif;
        }
    </style>
    @vite('resources/css/style.css')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
</head>
<body class="bg-gradient-to-r from-gray-600 to-blue-900 body-font">

<nav class="font-lato border-gray-200 bg-white dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="#" class="flex items-center">
        <p class="self-center text-blue-900 text-2xl font-semibold whitespace-nowrap ">ApprovedByMe</p>
    </a>
    <button data-collapse-toggle="navbar-multi-level" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-multi-level" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg  md:flex-row md:space-x-8 md:mt-0 md:border-0  dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="{{ Auth::user()->role == 'mahasiswa' ? route('home') : (Auth::user()->role == 'dosen' ? route('dosen.home') : (Auth::user()->role == 'admin' ? route('admin.home') : route('koor.home'))) }}" class="block py-2 pl-3 pr-4 text-blue-700 rounded md:bg-transparent md:hover:text-green-900 md:p-0 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent" >Home</a>
        </li>
        <li>
          <a href="#" class="block py-2 pl-3 pr-4 text-blue-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-900 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">History</a>
        </li>
        @auth
        @if (Auth::user()->role == 'koordinator')
        <li>
          <a href="{{ route('koor.rekap') }}" class="block py-2 pl-3 pr-4 text-blue-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Rekapitulasi</a>
        </li>
        @endif
        @if (in_array(auth()->user()->role, ['dosen', 'koordinator']))
        <li>
            <button id="dropdownNavbarLink" data-dropdown-toggle="PenilaiandropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-blue-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Penilaian <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="PenilaiandropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-600 " aria-labelledby="dropdownLargeButton">
                  <li>
                    <a href="{{ route('dosen.pen05Home') }}" class="block px-4 py-2 text-blue-700 md:hover:text-green-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Pen05</a>
                  </li>
                  
                  <li>
                    <a href="{{ route('dosen.pen09Home') }}" class="block px-4 py-2 text-blue-700 md:hover:text-green-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Pen09</a>
                  </li>

                  <li>
                    <a href="{{ route('dosen.pen14Home') }}" class="block px-4 py-2 text-blue-700 md:hover:text-green-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Pen14</a>
                  </li>
                </ul>
            </div>
        </li>
        @endif
        @if (Auth::user()->role == 'mahasiswa')
            <?php
            $hasSempro = Auth::user()->sempros->where('seminar', 'seminar proposal')->isNotEmpty();
            $hasSemhas = Auth::user()->sempros->where('seminar', 'Seminar Hasil')->isNotEmpty();
            $hasSidang = Auth::user()->sempros->where('seminar', 'Sidang Akhir')->isNotEmpty();
            ?>

            <li>
                <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-blue-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                    Seminar
                    <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-2 text-sm" aria-labelledby="dropdownLargeButton">
                        <li>
                            <a href="/seminar/create" class="block px-4 py-2 text-blue-600 {{ $hasSempro ? 'text-gray-400' : 'md:hover:text-green-700 dark:hover:bg-gray-600 dark:hover:text-white' }}">Sempro</a>
                        </li>
                        <li>
                            <a href="/semhas/create" class="block px-4 py-2 text-blue-600 {{ $hasSemhas ? 'text-gray-400' : 'md:hover:text-green-700 dark:hover:bg-gray-600 dark:hover:text-white' }}">Semhas</a>
                        </li>
                        <li>
                            <a href="{{ route('sidang.create') }}" class="block px-4 py-2 text-blue-600 {{ $hasSidang ? 'text-gray-400' : 'md:hover:text-green-700 dark:hover:bg-gray-600 dark:hover:text-white' }}">Sidang</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @endauth
        <li>
            <button id="dropdownNavbarLink" data-dropdown-toggle="ProfiledropdownNavbar" class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-blue-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Profile <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="ProfiledropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-blue-600 md:hover:text-green-700" aria-labelledby="dropdownLargeButton">
                  <li>
                    <a href="/profile" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                  </li>
                </ul>
                <div class="py-1">
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-blue-600 md:hover:text-green-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Sign out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
                </div>
            </div>
        </li>
      </ul>
    </div>
  </div>
</nav>



    

    <div class="mt-10 ml-20 mr-20 text-white font-bold font-[montserrat]">
        @yield('content')
    </div>



    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    
</body>
</html>