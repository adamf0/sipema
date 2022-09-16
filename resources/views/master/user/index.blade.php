@extends('layouts.app')

@section('page-title', 'Master User')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.user.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Penguna</th>
                    <th>Email</th>
                    <th>Hak Akses</th>
                    <th>Management Kampus</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <th>{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <ul>
                                @forelse ($user->roles as $role)
                                    <li>{{ $role->name }}</li>
                                @empty
                                    Tidak ada role
                                @endforelse
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @forelse ($user->user_kampus as $user_kampus)
                                    <li>{{ $user_kampus->kampus->nama_kampus }}</li>
                                @empty
                                    Tidak ada kampus yang dimanagement
                                @endforelse
                            </ul>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('master.user.edit', ['user' => $user->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.user.destroy', ['user' => $user->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
@endsection
