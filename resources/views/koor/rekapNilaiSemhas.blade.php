@extends('layout.navbar')

@section('content')

<div class=" font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
    <p class=" text-2xl md:text-4xl text-center mb-10">Detail Rekapitulasi Penilaian Seminar Hasil</p>
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
                @if ($totalRerataNilaiKeseluruhan > 88)
                    A
                @elseif ($totalRerataNilaiKeseluruhan > 77)
                    AB
                @elseif ($totalRerataNilaiKeseluruhan > 68)
                    B
                @else
                    BC
                @endif
                </button>
            @else
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Tidak Lulus</button>
            @endif

        </div>

        <div class="flex items-left  mx-auto relative   rounded-lg overflow-hidden">
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
            @foreach ($nilaiDosen as $index => $nilai)
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 w-1/3">
                        @if ($nilai->dosen)
                            {{ $nilai->dosen->name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">
                    @if ($nilai->dosen->id == $dospem1Id)
                    Sekretaris
                @elseif ($nilai->dosen->id == $dospem2Id)
                    Ketua
                @elseif ($nilai->dosen->id == $sempro->penguji1 || $nilai->dosen->id == $sempro->penguji2 || $nilai->dosen->id == $sempro->penguji3)
                    Anggota
                @endif  
                    </td>
                    <td class="px-6 py-4">{{ $nilai->total_nilai }}</td>
                    
                </tr>
            @endforeach
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
    </div>


    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
</div>

  <!-- Tombol Unduh PDF -->
  <a href="{{ route('export.pdfSemhas', ['id' => $sempro->id]) }}" class="btn btn-primary">Unduh PDF</a>

  
 
@endsection