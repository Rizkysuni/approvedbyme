@extends('layout.navbar')

@section('content')

<div class=" font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
    <p class=" text-2xl md:text-4xl text-center mb-10">Informasi {{$dospem1->seminar}}</p>
    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm mt-5">
        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 ">Detail Data</h5>     
            <div class="grid grid-cols-1  md:grid-cols-4 text-lg">
                <div class="block p-4 bg-white text-gray-600">
                    @if ($dospem1->mahasiswa)
                    <img class="object-cover w-full md:h-44 md:w-36 md:rounded-none border-black border-8 border-solid md:border-solid" src="{{ asset('images/' . $dospem1->mahasiswa->gambar) }}" alt="{{ $dospem1->nama }}"> 
                    @else
                    <p>Tidak ada gambar mahasiswa yang tersedia.</p>
                    @endif   
                </div>
                <div class="block p-4 bg-white text-gray-600">
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Nama :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $dospem1->nama }} </p>
                    </div>
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">NIM :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $dospem1->nim }} </p>
                    </div>
                    <div>
                        <p class="font-normal  dark:text-gray-400">Jurusan :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $dospem1->jurusan}} </p>
                    </div>   
                </div>
                <div class="block p-4 bg-white text-gray-600">
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Ruangan :</p>
                        <p class="font-bold  dark:text-gray-400"> {{ $dospem1->ruangan }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Tanggal Seminar :</p>
                        <p class="font-bold  dark:text-gray-400"> {{ $dospem1->tglSempro }}</p>
                    </div>
                    <div>
                        <p class="font-normal  dark:text-gray-400">Jam :</p>
                        <p class="font-bold  dark:text-gray-400"> {{ $dospem1->jam }}</p>
                    </div>   
                </div>
                <div class="block p-4 bg-white   text-gray-600">
                    <p class="font-normal  dark:text-gray-400">Judul :</p>
                    <p class="font-bold  dark:text-gray-400">{{ $dospem1->judul }} </p>
                </div>
                
            </div>   
            
    </div>

    
<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-10">
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