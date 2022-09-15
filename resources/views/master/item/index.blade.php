@extends('layouts.app')

@section('page-title', 'Master Item')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.item.create') }}" class="btn btn-primary">Tambah</a>
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
                @forelse ($masterItems as $masterItem)
                    <tr>
                        <th>{{ $masterItem->id }}</th>
                        <td>{{ $masterItem->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('master.item.edit', ['master_item' => $masterItem->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.item.destroy', ['master_item' => $masterItem->id]) }}" method="post">
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
        {{ $masterItems->links() }}
    </div>
@endsection
