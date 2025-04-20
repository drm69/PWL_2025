@extends('layouts.template')
@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Profile Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user"></i> Profil Pengguna</h3>
                </div>
                <div class="card-body">
                    <form action="{{url ('profile/update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <img src="{{ $user->foto ? asset('storage/profiles/' . $user->foto) . '?' . $user->updated_at->timestamp : asset('images/default.png') }}" 
                                class="img-circle elevation-2" 
                                alt="User Image" 
                                width="120" height="120" 
                                style="object-fit: cover;">
                        </div>

                        <div class="mb-3 text-center">
                            <input type="file" name="foto" class="form-control w-50 d-inline-block" accept="image/*">
                            @error('foto')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Level</th>
                                <td>{{ $user->level->level_nama }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}">
                                    @error('nama')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>
                                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                                    @error('username')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th>Password Baru</th>
                                <td>
                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                    @error('password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th>Konfirmasi Password</th>
                                <td>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                                </td>
                            </tr>
                        </table>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2500
        });
    </script>
    @endif
@endpush

