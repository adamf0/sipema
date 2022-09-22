@extends('layouts.app')

@section('page-title', 'Metode Belajar')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.metode_belajar.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Metode Belajar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusMetodes as $kampusMetode)
                    <tr>
                        <th>{{ $kampusMetode->id }}</th>
                        <td>{{ $kampusMetode->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.metode_belajar.edit', ['metode_belajar' => $kampusMetode->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.metode_belajar.destroy', ['metode_belajar' => $kampusMetode->id]) }}" method="post">
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
        {{ $kampusMetodes->links() }}
    </div>
@endsection
