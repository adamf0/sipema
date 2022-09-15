@extends('layouts.app')

@section('page-title', 'Edit Item Bayar : '.$item_bayar->item->nama.' Tahun Akademik ' . \Carbon\Carbon::parse($item_bayar->tahun_akademik)->format('Ym'))

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('kampus.item-bayar.update', ['item-bayar' => $item_bayar->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Item Bayar</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                
                <div class="mb-3">
                    <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                    <input type="date" name="tahun_akademik" class="form-control" id="tahun_akademik" value="{{ $item_bayar->tahun_akademik }}" required/>
                </div>

                <div class="mb-3">
                    <label for="id_gelombang" class="form-label">
                        Gelombang
                    </label>
                    <select class="form-select" name="id_gelombang" id="id_gelombang" required>
                        <option value="">Pilih Gelombang</option>
                        @foreach ($gelombangs as $gelombang)
                            <option value="{{ $gelombang->id }}" {{ ($gelombang->id==$item_bayar->id_data_gelombang? "selected":"") }}>{{ ucwords($gelombang->nama_gelombang) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_item" class="form-label">
                        Item
                    </label>
                    <select class="form-select" name="id_item" id="id_item" required>
                        <option value="">Pilih Item</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ ($item->id==$item_bayar->id_item? "selected":"") }}>{{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th>
                                    <label>Cicilan</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text btn-primary">+</span>
                                        <input type="text" class="form-control" value="{{ $item_bayar->jumlah_angsuran }}" disabled/>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item_bayar->template_angsuran as $index => $angsuran)
                            <tr>
                                <td>
                                    <input type="text" class='form-control money' name='anggaran[{{$index}}]' value="Rp {{ number_format($angsuran->nominal, 2, ',', '.') }}" required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</form>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script type="text/javascript">
        function initMaskMoney() {
            $('.money').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', affixesStay: true}); 
        }
        $('#id_item').select2({
            theme: 'bootstrap-5'
        });
        initMaskMoney();
</script>
@endpush