@extends('layout.navbar')

@section('content')

<div class="font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
    <p class=" text-2xl md:text-4xl text-center mb-10">Detail Rekapitulasi Penilaian Sidang Akhir</p>
    <div class="text-base md:text-xl mb-10">
            <p class="">Nama : {{ $sempro->nama }}</p>
            <p class="">NIM : {{ $sempro->nim }}</p>
            <p class="">Judul : {{ $sempro->judul }}</p>
            <p class="">Jurusan : {{ $sempro->jurusan}}</p>
            <p class="">Ruangan : {{ $sempro->ruangan }}</p>
    </div>
    

        <div class="flex items-left  mx-auto relative   rounded-lg overflow-hidden">
      <table class=" text-sm text-left text-gray-500 ">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3 w-1/3">Nama Dosen</th>
                <th scope="col" class="px-6 py-3">Status Penguji</th>
                <th scope="col" class="px-6 py-3">Komponen 1</th>
                <th scope="col" class="px-6 py-3">Komponen 2</th>
                <th scope="col" class="px-6 py-3">Komponen 3</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">1</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaDospem1 }}
                    </td>
                    <td class="px-6 py-4">
                    Sekretaris 
                    </td>
                    <td class="px-6 py-4">{{ $komp1dp1 ?? 'Nilai belum diinput' }}</td>
                    <td class="px-6 py-4">
                    {{ $komp2dp1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3dp1 ?? 'Nilai belum diinput' }}
                    </td>
                    
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">2</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaDospem2 }}
                    </td>
                    <td class="px-6 py-4">
                    Ketua
                    </td>
                    <td class="px-6 py-4">{{ $komp1dp2 ?? 'Nilai belum diinput' }}</td>
                    <td class="px-6 py-4">
                    {{ $komp2dp2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3dp2 ?? 'Nilai belum diinput' }}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">3</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji1 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg1 ?? 'Nilai belum diinput' }}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">4</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji2 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg2 ?? 'Nilai belum diinput' }}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">5</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji3 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg3 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg3 ?? 'Nilai belum diinput' }}
                    </td>
                </tr>
        </tbody>
        <tfoot>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Jumlah:</td>
                <td class="px-6 py-4">{{ $jumlahKomponen1 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen2 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen3 }}</td>
            </tr>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Komponen 2 + 3</td>
                <td class="px-6 py-4">{{ $jumlahKomponen3 + $jumlahKomponen2 }}</td>
            </tr>
        </tfoot>
    </table>
    </div>

    @if (auth()->user()->id === $sempro->dospem2)
    <div class="flex justify-end mt-6 mx-auto">
        <!-- Tombol Kirim -->
        <form action="{{ route('sendDataSidang') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Kirim
            </button>
        </form>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
</div>


  
 
@endsection