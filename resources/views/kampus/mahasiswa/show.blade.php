@extends('layouts.app')

@section('page-title', 'Rencana Pembayaran Mahasiswa : ' . $mahasiswa->nama_lengkap)

@push('js')
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://www.jqueryscript.net/demo/Merge-Cells-HTML-Table/jquery.table.marge.js"></script>
    <script>
        $('#bulanan').margetable({
            type: 2,
            colindex: [1,4]
        });
        $('#nonbulanan').margetable({
            type: 2,
            colindex: [0,1]
        });
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pembayaran Bulanan</div>
            <div class="card-body">
                <table class="table table-responsive table-bordered text-center align-middle" id="bulanan">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pembayaran</th>
                            <th>Semester</th>
                            <th>Biaya</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($bulanans as $key_group => $item_group)
                            @foreach ($item_group as $index => $rencana)
                            @php
                                $date1 = strtotime($key_group);
                                $date2 = strtotime(date('Y-m-d'));
                                $diff = $date1-$date2;
                            @endphp
                            <tr>
                                @if (count($item_group)>=1 && $index==0)
                                    <td rowspan="{{ count($item_group) }}">{{ \Carbon\Carbon::parse($rencana->tanggal_bayar)->format('j F Y') }}</td>
                                @endif                            
                                <td>{{ $rencana->item_bayar->item->nama }}</td>
                                <td>{{ "Rp ".number_format($rencana->biaya, 0, ",", ".") }}</td>
                                <td>{{ $rencana->nama }}</td>
                                <td>Semester {{ floor($i/$max_cicilan)+1 }}</td>
                                <td>
                                    @if ($rencana->status==0 && $diff==0)
                                    <label class="badge bg-warning">Menunggu Pembayaran</label>
                                    @elseif ($rencana->status==0 && $diff < 0)
                                    <label class="badge bg-danger">Belum Bayar</label>
                                    @elseif ($rencana->status==0 && $diff > 0)
                                    <label class="badge bg-secondary">Belum Ada Tagihan</label>
                                    @elseif ($rencana->status==1)
                                    <label class="badge bg-success">Sudah Bayar</label>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @php $i++ @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pembayaran Non-Bulanan</div>
            <div class="card-body">
                <table class="table table-responsive table-bordered text-center align-middle" id="nonbulanan">
                    <thead>
                        <tr>
                            <th>Pembayaran</th>
                            <th>Biaya</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($non_bulanans as $index => $item)
                            <tr>
                                <td>{{ $item->item_bayar->item->nama }}</td>
                                <td>{{ "Rp ".number_format($item->biaya, 0, ",", ".") }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    @if ($item->status==0)
                                    <label class="badge bg-danger">Belum Bayar</label>
                                    @else
                                    <label class="badge bg-success">Sudah Bayar</label>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection