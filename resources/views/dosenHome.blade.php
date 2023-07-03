@extends('layout.navbar')

@section('content')
    <div class="text-7xl">
      <h1>Selamat Datang</h1>
      <h1>
        {{ auth()->user()->name }}!
      </h1>
    </div>

    <div class="relative overflow-x-auto">
      <table class=" text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                  <th scope="col" class="px-6 py-3">
                      No
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Nama
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Jurusan
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Ruangan
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Tanggal
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Seminar
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Beri Nilai
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach ($sempros as $index => $sempro)
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ $index + 1 }}
                  </th>
                  <td class="px-6 py-4">
                  {{ $sempro->nama }}
                  </td>
                  <td class="px-6 py-4">
                  {{ $sempro->jurusan }}
                  </td>
                  <td class="px-6 py-4">
                  {{ $sempro->ruangan}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $sempro->tglSempro}}
                  </td>
                  <td class="px-6 py-4">
                  proposal
                  </td>
                  <td>
                      <a href="{{ route('beriNilai', ['id' => $sempro->id]) }}">Beri Nilai</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>
  
    
    <!-- Example: logout link -->
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>

    <!-- Example: logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection