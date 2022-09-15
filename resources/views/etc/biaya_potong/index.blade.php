@extends('layouts.app')

@section('page-title', 'Biaya Potong')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('biaya-potongan.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Potongan</th>
                    <th>Total Potongan</th>
                    <th>Item Bayar</th>
                    <th>Berlaku</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($biaya_potongs as $biaya_potong)
                    <tr>
                        <th>{{ $biaya_potong->id }}</th>
                        <td>{{ $biaya_potong->nama_beasiswa }}</td>
                        <td>{{ ($biaya_potong->persentase_potongan*100) }}%</td>
                        <td>{{ $biaya_potong->master_item->nama }}</td>
                        <td>
                            {{ Carbon\Carbon::parse($biaya_potong->berlaku)->format('d F Y') }}
                            s/d 
                            {{ Carbon\Carbon::parse($biaya_potong->berakhir)->format('d F Y') }}
                        </td>
                        <td>
                            @if ( strtotime($biaya_potong->berlaku) >= strtotime(date('Y-m-d')) && strtotime(date('Y-m-d') <= strtotime($biaya_potong->berakhir)) )
                                Masih Berlaku
                            @else
                                Sudah Tidak Berlaku
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('biaya-potongan.edit', ['biaya_potong' => $biaya_potong->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('biaya-potongan.destroy', ['biaya_potong' => $biaya_potong->id]) }}" method="post">
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
        {{ $biaya_potongs->links() }}
    </div>
@endsection
