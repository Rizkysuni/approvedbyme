@extends('layout.navbar')

@section('content')
    <div class="font-lato text-3xl">
    <p>History Seminar</p>
    </div>

    <br>
    <div class="font-lato relative overflow-x-auto">
    <table id="tabel-data" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                    @if ($sempro->seminar === 'Seminar Proposal')
                        <a href="{{ route('dosen.pen05', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Berita Acara</a>
                    </td>
                    @endif
                    @if ($sempro->seminar === 'Seminar Hasil')
                        <a href="{{ route('dosen.pen09', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Berita Acara</a>
                    </td>
                    @endif
                    @if ($sempro->seminar === 'Sidang Akhir')
                        <a href="{{ route('dosen.pen14', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Berita Acara</a>
                    </td>
                    @endif
            </tr>
            @endforeach 
        </tbody>
    </table>
</div>

<script>
        $(document).ready(function(){
            $('#tabel-data').DataTable({
            responsive: true
        });
            
        });
    </script>

@endsection