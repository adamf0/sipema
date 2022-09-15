@extends('layouts.app')

@section('page-title', 'Tambah Item Bayar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('kampus.item-bayar.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Item Bayar</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                    <input type="date" name="tahun_akademik" class="form-control" id="tahun_akademik" required/>
                </div>

                <div class="mb-3">
                    <label for="id_gelombang" class="form-label">
                        Gelombang
                    </label>
                    <select class="form-select" name="id_gelombang" id="id_gelombang" required>
                        <option value="">Pilih Gelombang</option>
                        @foreach ($gelombangs as $gelombang)
                            <option value="{{ $gelombang->id }}">{{ ucwords($gelombang->nama_gelombang) }}</option>
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
                            <option value="{{ $item->id }}">{{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nominal" class="form-label">Nominal</label>
                    <input type="text" name="nominal" class="form-control" id="nominal" value="0" required/>
                </div>

                <div class="mb-3">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th>
                                    <label>Angsuran</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text btn btn-primary btn-add">+</span>
                                        <input type="text" class="form-control" name="total_angsuran[]" required/>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="button" class="btn btn-success btn-gen" disabled>Generate Biaya</button>
                <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
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
        let nominal = 0;

        function initMaskMoney() {
            $('.money').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', affixesStay: true}); 
        }
        $('#id_item').select2({
            theme: 'bootstrap-5'
        });

        const addColumn = () => {
            for (const [i, row] of [...document.querySelectorAll('#table thead tr')].entries()) {
                const label = document.createElement("label")
                label.innerHTML = "Cicilan"

                const div = document.createElement("div")
                div.setAttribute('class', 'input-group mb-3')

                const input = document.createElement("input")
                input.setAttribute('type', 'text')
                input.setAttribute('name', 'total_angsuran[]')
                input.setAttribute('class', 'form-control')
                const cell = document.createElement(i ? "td" : "th")
                
                cell.appendChild(label);
                cell.appendChild(div);
                div.appendChild(input);
                row.appendChild(cell);
            };
        }
        const generate = () => {
            var isValid = true;
            var values = $("input[name='total_angsuran[]']").map(function(){return parseInt($(this).val()); }).get();
            for (i=0; i < values.length; i++){
                if (values[i] === false || Number.isNaN(values[i]) || values[i] == undefined) {
                    isValid = false;
                }
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
        document.querySelector('.btn-add').onclick = addColumn
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
            
            // var angsuran = [12,24,8,36];
            var x = new Array(angsuran.length);
        
            for (var i = 0; i < x.length; i++) {
                x[i] = new Array(arrayMax(angsuran));
            }
            for (var i = 0; i < x.length; i++) {
                for (var j = 0; j < x[i].length; j++) {
                    if(j+1<=angsuran[i]){
                        x[i][j] = nominal/angsuran[i];
                    }	
                    else{
                        x[i][j] = null;
                    }
                }	
            }		
            console.log(x);
            x = transpose(x);

            for (var i = 0; i < x.length; i++) {
                var row = document.createElement("tr");
                for (var j = 0; j < x[i].length; j++) {
                    var cell = document.createElement("td");
                    var cellText = document.createTextNode('asa');
                    
                    if(x[i][j] != null){
                        cell.innerHTML = "<input type='text' class='form-control' name='anggaran["+angsuran[j]+"]["+i+"]' value='Rp. "+x[i][j]+"' disabled>"
                    }
                    row.appendChild(cell);
                    initMaskMoney();
                }
                tblBody.appendChild(row);
            }
            tbl.appendChild(tblBody);
            initMaskMoney();
            tbl.setAttribute("border", "2");
        }

</script>
@endpush