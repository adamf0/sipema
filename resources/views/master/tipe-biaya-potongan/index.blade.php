@extends('layouts.app')

@section('page-title', 'Master Tipe Biaya Potongan')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.tipe-biaya-potongan.create') }}" class="btn btn-primary">Tambah</a>
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
                @forelse ($masterTipeBiayaPotongans as $masterTipeBiayaPotongan)
                    <tr>
                        <th>{{ $masterTipeBiayaPotongan->id }}</th>
                        <td>{{ $masterTipeBiayaPotongan->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('master.tipe-biaya-potongan.edit', ['master_tipe_biaya_potongan' => $masterTipeBiayaPotongan->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.tipe-biaya-potongan.destroy', ['master_tipe_biaya_potongan' => $masterTipeBiayaPotongan->id]) }}" method="post">
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
        {{ $masterTipeBiayaPotongans->links() }}
    </div>
@endsection
