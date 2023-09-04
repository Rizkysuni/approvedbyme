@extends('layout.navbar')

@section('content')
<div class="font-lato text-3xl flex items-center justify-between">
  <div>
  <p>Selamat Datang, {{ auth()->user()->name }}</p>
  </div>
  
</div>
    <br>
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
                  <th scope="col" class="px-6 py-3">
                      aksi
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
                  <td class="px-6 py-4">
                    <!-- Tombol Edit -->
<a href="{{ route('sempro.edit', ['id' => $sempro->id]) }}" class="mr-2">
    <ion-icon style="font-size: 24px;" name="pencil-sharp"></ion-icon>
</a>

<!-- Tombol Hapus -->
<form action="{{ route('sempro.destroy', ['id' => $sempro->id]) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="ml-2">
        <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
    </button>
</form>


                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>
  
    
    
@endsection