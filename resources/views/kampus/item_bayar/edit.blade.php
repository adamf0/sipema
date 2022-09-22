@extends('layouts.app')

@section('page-title', 'Edit Rincian Biaya : '.$item_bayar->item->nama.' Tahun Akademik ' . $item_bayar->tahun_akademik)

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('kampus.item-bayar.update', ['item-bayar' => $item_bayar->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Rincian Biaya</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                
                <div class="mb-3">
                    <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" id="tahun_akademik" value="{{ $item_bayar->tahun_akademik }}" required/>
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
                    <label for="id_prodi" class="form-label">
                        Prodi
                    </label>
                    <select class="form-select" name="id_prodi" id="id_prodi" required>
                        <option value="">Pilih Prodi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ ($prodi->id==$item_bayar->id_prodi? "selected":"") }}>{{ ucwords($prodi->nama) }} ({{ ucwords($prodi->jenjang) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_kelas" class="form-label">
                        Kelas
                    </label>
                    <select class="form-select" name="id_kelas" id="id_kelas" required>
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelass as $kelas)
                            <option value="{{ $kelas->id }}" {{ ($kelas->id==$item_bayar->id_kelas? "selected":"") }}>{{ ucwords($kelas->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_metode_belajar" class="form-label">
                        Metode Belajar
                    </label>
                    <select class="form-select" name="id_metode_belajar" id="id_metode_belajar" required>
                        <option value="">Pilih Metode Belajar</option>
                        @foreach ($metode_belajars as $metode_belajar)
                            <option value="{{ $metode_belajar->id }}" {{ ($metode_belajar->id==$item_bayar->id_metode_belajar? "selected":"") }}>{{ ucwords($metode_belajar->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_item" class="form-label">
                        Komponen Biaya
                    </label>
                    <select class="form-select" name="id_item" id="id_item" required>
                        <option value="">Pilih Komponen Biaya</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ ($item->id==$item_bayar->id_item? "selected":"") }}>{{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="jenis" value="{{$item_bayar->jenis}}">

                <div class="mb-3">
                    <label for="nominal" class="form-label">Nominal</label>
                    <input type="text" name="nominal" class="form-control" id="nominal" value="{{ $item_bayar->nominal }}" min="1" required/>
                </div>

                @if ($item_bayar->type==1 && ($item_bayar->jenis=="bulanan" || $item_bayar->jenis=="angsuran"))
                    <div class="mb-3">
                        <table class="table table-responsive" id="table">
                            <thead>
                                <tr>
                                    <th>
                                        <label>Angsuran</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-primary">+</span>
                                            <input type="text" class="form-control" name="total_angsuran" value="{{ $item_bayar->jumlah_angsuran }}" required/>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                @elseif($item_bayar->type==0 && $item_bayar->jenis=="bulanan")
                    <div class="mb-3">
                        <table class="table table-responsive" id="table">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <label>Angsuran</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text btn-primary">+</span>
                                            <input type="text" class="form-control" name="total_angsuran" value="{{ $item_bayar->jumlah_angsuran }}" required/>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item_bayar->template_angsuran as $key => $template)
                                    <tr>
                                        <td>Cicilan ke-{{ $template->nama }}</td>
                                        <td>
                                            <input type="text" class="form-control" name="angsuran[{{$key}}]" value="{{ $template->nominal }}" required>
                                            <input type="hidden" name="key[{{$key}}]" value="{{ $template->nama }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer text-muted">
                @if ($item_bayar->type==1 && ($item_bayar->jenis=="bulanan" || $item_bayar->jenis=="angsuran"))
                    <button type="button" class="btn btn-success btn-gen">Cek Generate Biaya</button>
                @endif
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

    @if ($item_bayar->type==1)
    <script type="text/javascript">
        let nominal = {{ $item_bayar->nominal??0 }};
        $('#id_item').select2({
            theme: 'bootstrap-5'
        });
        const generate = () => {
            var isValid = true;
            var values = $("input[name='total_angsuran']").val();
            if (values === false || Number.isNaN(values) || values == undefined) {
                isValid = false;
            }
            console.log(values);

            if(nominal>0 && isValid){
                generate_table(values);
            }
            else if(nominal>0 && !isValid){
                alert('angsuran tidak boleh ada yg kosong   ');
            }
            else{
                alert('nominal harus tidak boleh 0');
            }
        }

        document.querySelector('.btn-gen').onclick = generate
        $('#nominal').on('change keyup', function() {
           nominal = $(this).val();
           
           if(nominal == 0){
            $('.btn-gen').prop('disabled', true);
           }
           else{
            $('.btn-gen').prop('disabled', false);
           }
        })

        function arrayMax(arr) {
            return arr.reduce(function (p, v) {
                return ( p > v ? p : v );
            });
        }
        function transpose(array) {
            return array.reduce((prev, next) => next.map((item, i) =>
                (prev[i] || []).concat(next[i])
            ), []);
        }
        function generate_table(angsuran) {
            var tbl = document.querySelector('#table');
            var tblBody = document.querySelector('#table tbody');
            $("#table tbody tr").remove()
            
            var x = new Array(Number.parseInt(angsuran));        
            
            for (var i = 0; i < x.length; i++) {
                if(i+1<=angsuran){
                    x[i] = nominal/angsuran;
                }	
                else{
                    x[i] = null;
                }
            }		

            for (var i = 0; i < x.length; i++) {
                var row = document.createElement("tr");
                var cell = document.createElement("td");
                    
                if(x[i] != null){
                    cell.innerHTML = "<input type='text' class='form-control' value='Rp. "+x[i]+"' disabled>"
                }
                row.appendChild(cell);
                tblBody.appendChild(row);
            }
            tbl.appendChild(tblBody);
            tbl.setAttribute("border", "2");
        }
    </script>
    @endif
@endpush