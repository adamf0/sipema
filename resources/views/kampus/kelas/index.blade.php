@extends('layouts.app')

@section('page-title', 'Kelas')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.kelas.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusKelas as $kelas)
                    <tr>
                        <th>{{ $kelas->id }}</th>
                        <td>{{ $kelas->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.kelas.edit', ['kampus_kelas' => $kelas->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.kelas.destroy', ['kampus_kelas' => $kelas->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $kampusKelas->links() }}
    </div>
@endsection
