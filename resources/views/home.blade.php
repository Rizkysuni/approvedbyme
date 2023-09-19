@extends('layout.navbar')

@section('content')
<div class="font-lato text-3xl flex items-center justify-between">
  <div>
  <p>Selamat Datang, {{ auth()->user()->name }}</p>
  </div>
  

</div>
    <br>
    <div class="relative overflow-x-auto">
    @if(session('error'))

<div id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
  <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div class="ml-3 text-sm font-medium">
  {{ session('error') }}
  
</div>
  <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
    <span class="sr-only">Close</span>
    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
    </svg>
  </button>
</div>
@endif
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
                      Jam
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Seminar
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach ($sempros as $index => $sempro)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" onclick="window.location.href='{{ route('detailSempro', ['id' => $sempro->id]) }}'">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ $index + 1 }}
                  </td>
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
                  {{ $sempro->jam}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $sempro->seminar}}
                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>
  
  <div id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" id="error-message">
                        
                    </h3>
                <button data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Oke
                </button>
            </div>
        </div>
    </div>
</div> 

@endsection