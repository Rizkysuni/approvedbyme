@extends('layout.navbar')

@section('content')

<div class="font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
        <p class=" text-2xl md:text-4xl text-center mb-10">Detail Rekapitulasi Penilaian Seminar Proposal</p>
        <div class="text-base md:text-xl mb-10">
            <p class="">Nama : {{ $sempro->nama }}</p>
            <p class="">NIM : {{ $sempro->nim }}</p>
            <p class="">Judul : {{ $sempro->judul }}</p>
            <p class="">Jurusan : {{ $sempro->jurusan}}</p>
            <p class="">Ruangan : {{ $sempro->ruangan }}</p>
        </div>

        <p class="text-2xl text-center">Berdasarkan nilai rata-rata dari tim penguji, maka mahasiswa tersebut dinyatakan:</p>
        <div class=" p-4 rounded-lg mb-6 content-center mx-auto">
            @if ($totalRerataNilaiKeseluruhan > 69)
                <button class="bg-green-600 text-white px-10 py-2 rounded-lg">Lulus Dengan kategori nilai huruf : 
                @if ($totalRerataNilaiKeseluruhan >= 87)
                    A
                @elseif ($totalRerataNilaiKeseluruhan >= 78)
                    AB
                @elseif ($totalRerataNilaiKeseluruhan > 69)
                    B
                @else
                    BC
                @endif
                </button>
            @else
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Tidak Lulus</button>
            @endif

        </div>

        <div class="items-left mx-auto relative rounded-lg overflow-hidden">
      <table class=" text-sm text-left text-gray-500 ">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3 w-1/3">Nama Dosen</th>
                <th scope="col" class="px-6 py-3">Status Penguji</th>
                <th scope="col" class="px-6 py-3">Nilai</th>
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
                    <td class="px-6 py-4">
                    {{ $totrat1 ?? 'Nilai belum diinput' }}
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
                    <td class="px-6 py-4">
                    {{ $totrat2 ?? 'Nilai belum diinput' }}
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
                    <td class="px-6 py-4">
                    {{ $totrat3 ?? 'Nilai belum diinput' }}
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
                    <td class="px-6 py-4">
                    {{ $totrat4 ?? 'Nilai belum diinput' }}
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
                    <td class="px-6 py-4">
                    {{ $totrat5 ?? 'Nilai belum diinput' }}
                    </td>
                </tr>
        </tbody>
        <tfoot>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Jumlah:</td>
                <td class="px-6 py-4">{{ $totalNilaiKeseluruhan }}</td>
            </tr>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Rata - rata:</td>
                <td class="px-6 py-4">{{ $totalRerataNilaiKeseluruhan }}</td>
            </tr>
        </tfoot>
    </table>
    
    @if (in_array(auth()->user()->role, ['admin']))
    <div class="py-3 px-3">
        <button class="bg-green-600 text-white px-10 py-2 rounded-lg">
        <!-- Tombol Unduh PDF -->
        <a href="{{ route('export.pdf', ['id' => $sempro->id]) }}" class="btn btn-primary text-end ">Unduh Pen05</a>
        </button>
    </div>
    
    @endif
    </div>

    @if (auth()->user()->id === $sempro->dospem2)
    <div class="flex justify-end mt-6 mx-auto">
        <!-- Tombol Kirim -->
        <form action="{{ route('sendDataToCoordinator') }}" method="POST">
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