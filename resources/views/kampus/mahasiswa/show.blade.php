@extends('layouts.app')

@section('page-title', 'Rencana Pembayaran Mahasiswa : ' . $mahasiswa->nama_lengkap)

@section('content')
    <div class="card">
        <div class="card-header">Rencana Pembayaran Mahasiswa</div>
        <div class="card-body">
            <table class="table table-responsive table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembayaran</th>
                        <th>Biaya</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rencanas as $key_group => $item_group)
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
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">

        </div>
    </div>
@endsection