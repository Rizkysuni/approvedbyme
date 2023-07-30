@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambahkan Tanda Tangan</h1>
        <form action="{{ route('saveSignature') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="signature">Tanda Tangan (Image)</label>
                <input type="file" class="form-control" id="signature" name="signature" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection