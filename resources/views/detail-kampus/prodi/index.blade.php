@extends('layouts.kampus')

@section('nama-kampus', $kampus->nama_kampus)

@section('page-title', 'Prodi')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('detail-kampus.prodi.create', ['kampus' => $kampus->id]) }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kode Prodi</th>
                    <th>Nama Prodi</th>
                    <th>Jenjang</th>
                    <th>Masa Studi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prodis as $prodi)
                    <tr>
                        <th>{{ $prodi->id }}</th>
                        <td>{{ $prodi->kode_prodi }}</td>
                        <td>{{ $prodi->nama }}</td>
                        <td>{{ $prodi->jenjang }}</td>
                        <td>{{ $prodi->masa_studi }} Tahun</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('detail-kampus.prodi.edit', ['kampus' => $kampus->id, 'prodi' => $prodi->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('detail-kampus.prodi.destroy', ['kampus' => $kampus->id, 'prodi' => $prodi->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $prodis->links() }}
    </div>
@endsection
