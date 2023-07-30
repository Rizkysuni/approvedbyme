<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Nilai Sempro</title>
</head>
<body>
    <h1>Rekapitulasi Nilai Seminar Proposal</h1>
    <h2>{{ $sempro->judul }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Dosen</th>
                <th>Status Penguji</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilaiDosen as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->dosen->nama }}</td>
                    <td>
                        @if($nilai->dosen->id === $sempro->dospem1)
                            Sekretaris
                        @elseif($nilai->dosen->id === $sempro->dospem2)
                            Ketua
                        @else
                            Anggota
                        @endif
                    </td>
                    <td>{{ $nilai->total_nilai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total Nilai Keseluruhan: {{ $totalNilaiKeseluruhan }}</p>
    <p>Total Rata-rata Nilai Keseluruhan: {{ $totalRerataNilaiKeseluruhan }}</p>
</body>
</html>
