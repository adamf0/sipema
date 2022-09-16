@extends('layouts.app')

@section('page-title', 'Master Kelompok')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.kelompok.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelompoks as $kelompok)
                    <tr>
                        <th>{{ $kelompok->id }}</th>
                        <td>{{ $kelompok->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('master.kelompok.edit', ['kelompok' => $kelompok->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.kelompok.destroy', ['kelompok' => $kelompok->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $kelompoks->links() }}
    </div>
@endsection
