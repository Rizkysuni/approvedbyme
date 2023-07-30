@extends('layout.navbar')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
        <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                    Aksi 
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($semhas as $index => $semhas)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $index + 1 }}
                </th>
                <td class="px-6 py-4">
                {{ $semhas->nama }}
                </td>
                <td class="px-6 py-4">
                {{ $semhas->jurusan }}
                </td>
                <td class="px-6 py-4">
                {{ $semhas->ruangan}}
                </td>
                <td class="px-6 py-4">
                {{ $semhas->tglSempro}}
                </td>
                <td class="px-6 py-4">
                {{ $semhas->seminar}}
                </td>
                <td>
                      <a href="{{ route('dosen.pen09', ['id' => $semhas->id]) }}">Pen09</a>
                  </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
</div>

@endsection