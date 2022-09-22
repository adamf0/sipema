@extends('layouts.app')

@section('page-title', 'Rincian Biaya')

@push('js')
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://www.jqueryscript.net/demo/Merge-Cells-HTML-Table/jquery.table.marge.js"></script>
    <script>
        $('#textTable').margetable({
            type: 2,
            colindex: [0,1,2,3,4,5,6]
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
                    <th>Prodi</th>
                    <th>Jenjang</th>
                    <th>Kelas</th>
                    <th>Metode Belajar</th>
                    <th>Komponen Biaya</th>
                    <th>Total Bayar</th>
                    <th>Angsuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item_bayars as $index => $item_bayar)
                    <tr>
                        <td>{{ $item_bayar->tahun_akademik }}</td>
                        <td>{{ $item_bayar->gelombang->nama_gelombang }}</td>
                        <td>{{ $item_bayar->prodi->nama }}</td>
                        <td>{{ $item_bayar->prodi->jenjang }}</td>
                        <td>{{ $item_bayar->kelas->nama }}</td>
                        <td>{{ $item_bayar->metode_belajar->nama }}</td>
                        <td>
                            {{ $item_bayar->item->nama }}<br>
                            <small>({{ $item_bayar->jenis }})</small>
                        </td>
                        <td>
                            @if ($item_bayar->jenis != "open")
                                Rp {{ number_format($item_bayar->total_bayar, 2, ',', '.') }}<br>
                                @if ($item_bayar->total_bayar==$item_bayar->nominal)
                                    <label class="badge bg-success">Valid</label>
                                @else
                                    <label class="badge bg-danger">Tidak Valid ({{$item_bayar->nominal-$item_bayar->total_bayar}})</label>
                                @endif

                                @if ($item_bayar->type==1)
                                    <label class="badge bg-primary">Auto Generate</label>
                                @else
                                    <label class="badge bg-secondary">Custom</label>
                                @endif
                            @else
                                Rp {{ number_format($item_bayar->nominal, 2, ',', '.') }}<br>
                                <label class="badge bg-success">Valid</label>
                            @endif
                        </td>
                        <td>
                            @if ($item_bayar->jenis=="open")
                                &infin;    
                            @else
                                x{{ $item_bayar->jumlah_angsuran }}</td>
                            @endif
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
