@extends('layouts.app')

@section('page-title', 'Kampus Item Bayar')

@push('js')
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://www.jqueryscript.net/demo/Merge-Cells-HTML-Table/jquery.table.marge.js"></script>
    <script>
        $('#textTable').margetable({
            type: 2,
            colindex: [0,1,2]
        });
    </script>
@endpush

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.item-bayar.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered text-center align-middle" id="textTable">
            <thead class="table-light">
                <tr>
                    <th>Tahun Akademik</th>
                    <th>Gelombang</th>
                    <th>Item</th>
                    <th>Total Bayar</th>
                    <th>Angsuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item_bayars as $index => $item_bayar)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item_bayar->tahun_akademik)->format('Ym') }}</td>
                        <td>{{ $item_bayar->gelombang->nama_gelombang }}</td>
                        <td>{{ $item_bayar->item->nama }}</td>
                        <td>Rp {{ number_format($item_bayar->total_bayar, 2, ',', '.') }}</td>
                        <td>x{{ $item_bayar->jumlah_angsuran }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.item-bayar.edit', ['item-bayar' => $item_bayar->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.item-bayar.destroy', ['item-bayar' => $item_bayar->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">

    </div>
@endsection
