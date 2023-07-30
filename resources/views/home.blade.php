@extends('layout.navbar')

@section('content')
    <div class="font-lato text-7xl">
      <h1>Selamat Datang</h1>
      <h1>
        {{ auth()->user()->name }}!
      </h1>
    </div>

    <div class="relative overflow-x-auto">
    <table class="font-lato w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
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
              </tr>
          </thead>
          <tbody>
            @foreach ($sempros as $index => $sempro)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                  {{ $sempro->seminar}}
                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>
  
    
    
@endsection