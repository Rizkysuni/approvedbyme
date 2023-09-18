@extends('layout.navbar')

@section('content')

<div class=" font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
    <p class=" text-2xl md:text-4xl text-center mb-10">Informasi {{$dospem1->seminar}}</p>
        <div class="text-base md:text-xl mb-10">
            <p class="">Nama : {{ $dospem1->nama }}</p>
            <p class="">NIM : {{ $dospem1->nim }}</p>
            <p class="">Judul : {{ $dospem1->judul }}</p>
            <p class="">Jurusan : {{ $dospem1->jurusan}}</p>
            <p class="">Ruangan : {{ $dospem1->ruangan }}</p>
            <p class="">Tanggal : {{ $dospem1->tglSempro }} </p>
            <p class="">Jam : {{ $dospem1->jam }} </p>
        </div>

    
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400  bg-gray-50">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Nip
                </th>
                <th scope="col" class="px-6 py-3">
                    Jabatan
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900  dark:text-white dark:bg-gray-800">
                    Pembimbing 1
                </th>
                <td class="px-6 py-4">
                    {{ $dospem1->namaDosen }}
                </td>
                <td class="px-6 py-4">
                    {{ $dospem1->nip }}
                </td>
                <td class="px-6 py-4">
                    {{ $dospem1->jabatan }}
                </td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    Pembimbing 2
                </th>
                <td class="px-6 py-4">
                    {{ $dospem2->namaDosen }}
                </td>
                <td class="px-6 py-4">
                    {{ $dospem2->nip }}
                </td>
                <td class="px-6 py-4">
                    {{ $dospem2->jabatan }}
                </td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    Penguji 1
                </th>
                <td class="px-6 py-4">
                    {{ $penguji1->namaDosen }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji1->nip }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji1->jabatan }}
                </td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    Penguji 2
                </th>
                <td class="px-6 py-4">
                    {{ $penguji2->namaDosen }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji2->nip }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji2->jabatan }}
                </td>
            </tr>
            <tr>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    Penguji 3
                </th>
                <td class="px-6 py-4">
                    {{ $penguji3->namaDosen }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji3->nip }}
                </td>
                <td class="px-6 py-4">
                    {{ $penguji3->jabatan }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

</div>

  
 
@endsection