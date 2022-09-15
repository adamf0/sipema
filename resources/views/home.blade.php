@extends('layouts.app')

@section('page-title', 'Home')

@push('css')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<style>
    .order-card {
        color: #fff;
    }

    .bg-c-secondary {
        background: #e2e2e2;
        color: black;
    }

    .bg-c-blue {
        background: linear-gradient(45deg,#4099ff,#73b4ff);
    }

    .bg-c-green {
        background: linear-gradient(45deg,#2ed8b6,#59e0c5);
    }

    .bg-c-yellow {
        background: linear-gradient(45deg,#FFB64D,#ffcb80);
    }

    .bg-c-pink {
        background: linear-gradient(45deg,#FF5370,#ff869a);
    }


    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
        border: none;
        margin-bottom: 30px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .card .card-block {
        padding: 25px;
    }

    .order-card i {
        font-size: 26px;
    }

    .f-left {
        float: left;
    }

    .f-right {
        float: right;
    }
</style>
@endpush

@section('content')
    @role('Admin')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else 
    <div class="container">
        <div class="row">            
            <div class="col-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-c-secondary order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Penagihan</h6>
                                <h2 class="text-end">
                                    <span>{{ ($tagihan->menunggu->count()+$tagihan->selesai->count()+$tagihan->jadwal_ulang->count()) }}</span>
                                </h2>
                                <p class="m-b-0">Menunggu Pembayaran<span class="f-right">{{ $tagihan->menunggu->count() }}</span></p>
                                <p class="m-b-0">Selesai Pembayaran<span class="f-right">{{ $tagihan->selesai->count() }}</span></p>
                                <p class="m-b-0">Jadwal Ulang Pembayaran<span class="f-right">{{ $tagihan->jadwal_ulang->count() }}</span></p>
                                <p class="m-b-0">Belum Selesai Pembayaran<span class="f-right">{{ $tagihan->belum_selesai->count() }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card bg-c-secondary order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Penagihan</h6>
                                <canvas id="chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <div class="card bg-c-green order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Prodi</h6>
                                <h2 class="text-end">
                                    <!-- <i class="fa fa-rocket f-left"></i> -->
                                    <span>{{ $prodi->count() }}</span>
                                </h2>
                                <!-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="card bg-c-yellow order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Gelombang</h6>
                                <h2 class="text-end">
                                    <!-- <i class="fa fa-refresh f-left"></i> -->
                                    <span>{{ $gelombang->count() }}</span>
                                </h2>
                                <!-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card bg-c-pink order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Item Bayar</h6>
                                <h2 class="text-end">
                                    <!-- <i class="fa fa-credit-card f-left"></i> -->
                                    <span>{{ $item_bayar->count() }}</span>
                                </h2>
                                <!-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Mahasiswa</h6>
                                <h2 class="text-end">
                                    <!-- <i class="fa fa-cart-plus f-left"></i> -->
                                    <span>{{ $mahasiswa->count() }}</span>
                                </h2>
                                <!-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
@endsection

@role('User')
    @push('js')
    <script>
    const ctx = document.getElementById('chart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Selesai', 'Jadwal Ulang', 'Menunggu Pembayaran', 'Selesai'],
            datasets: [{
                data: [
                    {{ array_sum($tagihan->belum_selesai->pluck('total')->toArray()) }},
                    {{ array_sum($tagihan->jadwal_ulang->pluck('total')->toArray()) }},
                    {{ array_sum($tagihan->menunggu->pluck('total')->toArray()) }},   
                    {{ array_sum($tagihan->selesai->pluck('total')->toArray()) }},
                ],
                backgroundColor: [
                    '#971616',
                    '#2e3767',
                    '#c27400',
                    '#228526'
                ],
                hoverOffset: 4
            }]
        }
    });
    </script>
    @endpush
@endrole
