@extends('layout.navbar')

@section('content')
    
  

  <h1 class="font-lato ">Rekapitulasi Penilaian Seminar</h1>
  <br>
  <div class="font-lato relative overflow-x-auto">
      <table class="text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class=" text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No.</th>
                <th scope="col" class="px-6 py-3">Nama Mahasiswa</th>
                <th scope="col" class="px-6 py-3">Jurusan</th>
                <th scope="col" class="px-6 py-3">Seminar</th>
                <th scope="col" class="px-6 py-3">Status Penilaian</th>
                <th scope="col" class="px-6 py-3">Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mahasiswaSempro as $index => $mahasiswa)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $index + 1 }}</th>
                    <td class="px-6 py-4">{{ $mahasiswa->nama }}</td>
                    <td class="px-6 py-4">{{ $mahasiswa->jurusan }}</td>
                    <td class="px-6 py-4">{{ $mahasiswa->seminar }}</td>
                    <td class="px-6 py-4">{{ $mahasiswa->status_nilai }}
                    
                    </td>
                    <td class="px-6 py-4">
                        @if ($mahasiswa->seminar === 'seminar proposal')
                        <a href="{{ route('rekapNilai', ['id' => $mahasiswa->id]) }}">detail</a>
                        @elseif ($mahasiswa->seminar === 'Seminar Hasil')
                            <a href="{{ route('rekapNilaiSemhas', ['id' => $mahasiswa->id]) }}">detail</a>
                        @elseif ($mahasiswa->seminar === 'Sidang Akhir')
                            <a href="{{ route('rekapNilaiSidang', ['id' => $mahasiswa->id]) }}">detail</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


@endsection