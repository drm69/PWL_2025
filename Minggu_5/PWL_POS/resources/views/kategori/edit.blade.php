@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Kategori')
@section('content_header_title', 'Kategori')
@section('content_header_subtitle', 'Edit')

{{-- Content body: main page content --}}
@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit kategori</h3>
        </div>

        {{-- Form Edit Kategori --}}
        <form method="post" action="{{route('saveKategori', $data->kategori_id)}}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="card-body">
                {{-- Kode Kategori --}}
                <div class="form-group">
                    <label for="kodeKategori">Kode Kategori</label>
                    <input type="text" class="form-control" id="kodeKategori" name="kodeKategori" 
                        placeholder="Masukkan kode kategori baru" required>
                </div>

                {{-- Nama Kategori --}}
                <div class="form-group">
                    <label for="namaKategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="namaKategori" name="namaKategori" 
                        placeholder="Masukkan nama kategori baru" required>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection