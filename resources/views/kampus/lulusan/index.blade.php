@extends('layouts.app')

@section('page-title', 'Lulusan')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.lulusan.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Lulusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusLulusan as $lulusan)
                    <tr>
                        <th>{{ $lulusan->id }}</th>
                        <td>{{ $lulusan->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.lulusan.edit', ['kampus_lulusan' => $lulusan->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.lulusan.destroy', ['kampus_lulusan' => $lulusan->id]) }}" method="post">
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
        {{ $kampusLulusan->links() }}
    </div>
@endsection
