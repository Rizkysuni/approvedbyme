<!-- layout.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Approved by Me</title>
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
<body class=" bg-gray-100 body-font">

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
         </button>
        <a href="" class="flex ml-2 md:mr-24">
          <img src="{{ asset('images/unsyiah.png') }}" class="h-8 mr-3" alt="FlowBite Logo" />
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Approved by Me</span>
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ml-3">
            <div>
              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="{{ asset('images/' . auth()->user()->gambar) }}" alt="user photo">
              </button>
            </div>
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
              <div class="px-4 py-3" role="none">
                <p class="text-sm font-semibold text-gray-900 dark:text-white" role="none">
                {{ auth()->user()->name }}
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                {{ auth()->user()->nim }}
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profile</a>
                </li>
                <li>
                  <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
      <ul class="space-y-2 font-medium">

         <li>
            <a href="{{ Auth::user()->role == 'mahasiswa' ? route('home') : (Auth::user()->role == 'dosen' ? route('dosen.home') : (Auth::user()->role == 'admin' ? route('admin.home') : route('koor.home'))) }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
               </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                  <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
              </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">History</span>
            </a>
         </li>

         @auth
        @if (Auth::user()->role == 'koordinator')
        <li>
            <a href="{{ route('koor.rekap') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                  <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
              </svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Rekapitulasi</span>
            </a>
         </li>
        @endif

        @if (in_array(auth()->user()->role, ['dosen', 'koordinator']))
        <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                     <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                  </svg>
                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Penilaian</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{ route('dosen.pen05Home') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">PEN 05</a>
                  </li>
                  <li>
                     <a href="{{ route('dosen.pen09Home') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">PEN 09</a>
                  </li>
                  <li>
                     <a href="{{ route('dosen.pen14Home') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">PEN 09</a>
                  </li>
            </ul>
         </li>
        @endif

         @if (Auth::user()->role == 'mahasiswa')
            <?php
            $hasSempro = Auth::user()->sempros->where('seminar', 'seminar proposal')->isNotEmpty();
            $hasSemhas = Auth::user()->sempros->where('seminar', 'Seminar Hasil')->isNotEmpty();
            $hasSidang = Auth::user()->sempros->where('seminar', 'Sidang Akhir')->isNotEmpty();
            ?>
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM2 6h7v6H2V6Zm9 6V6h7v6h-7Z"/>
                  </svg>
                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Seminar</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example" class="hidden py-2 space-y-2">
                  <li>
                     <a href="/seminar/create" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Proposal</a>
                  </li>
                  <li>
                     <a href="/semhas/create" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Hasil</a>
                  </li>
                  <li>
                     <a href="{{ route('sidang.create') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Sidang</a>
                  </li>
            </ul>
         </li>
         @endif
         @endauth
         
      </ul>
   </div>
</aside>

<div class="p-4 sm:ml-64">
   <div class="p-4  border-gray-200  dark:border-gray-700 mt-14">
   @yield('content')
        
    </div>
   </div>
</div>






























<!-- <nav class="font-lato border-gray-200 bg-white dark:border-gray-700">
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
</nav> -->



    

    <!-- <div class="mt-10 ml-20 mr-20 text-white font-bold font-[montserrat]">
        @yield('content')
    </div> -->



    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    
</body>
</html>