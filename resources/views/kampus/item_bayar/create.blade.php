@extends('layouts.app')

@section('page-title', 'Tambah Item Bayar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .select+.select2-container{
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('kampus.item-bayar.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Item Bayar</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" id="tahun_akademik" required/>
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
                    <label for="id_prodi" class="form-label">
                        Prodi
                    </label>
                    <select class="form-select" name="id_prodi" id="id_prodi" required>
                        <option value="">Pilih Prodi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ ucwords($prodi->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_kelompok" class="form-label">
                        Kelompok
                    </label>
                    <select class="form-select" name="id_kelompok" id="id_kelompok" required>
                        <option value="">Pilih Kelompok</option>
                        @foreach ($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}">{{ ucwords($kelompok->nama) }}</option>
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
                    <input type="text" name="nominal" class="form-control" id="nominal" min="1" required/>
                </div>

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link generate active" id="nav-generate-tab" data-bs-toggle="tab" data-bs-target="#nav-generate" type="button" role="tab" aria-controls="nav-generate" aria-selected="true">Auto Generate</button>
                        <button class="nav-link custom" id="nav-custom-tab" data-bs-toggle="tab" data-bs-target="#nav-custom" type="button" role="tab" aria-controls="nav-custom" aria-selected="false">Custom Input</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-generate" role="tabpanel" aria-labelledby="nav-generate-tab">
                        <div class="mb-3">
                            <table class="table table-responsive" id="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <label>Angsuran</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text btn btn-primary btn-add">+</span>
                                                <input type="text" class="form-control" name="total_angsuran[]"/>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-custom" role="tabpanel" aria-labelledby="nav-custom-tab">
                        <div class="container" id="panels">
                            <div style="position: relative" class="card">
                                <div class="card-body" id="body-0">
                                    <div class="row">
                                        <div class="col-11 mb-2">
                                            <label for="" class="form-label">Angsuran</label>
                                            <input type="text" class="form-control" name="angsuranC[0]" id="" />
                                        </div>
                                        <div id="" class="row">
                                            <div class="col-5">
                                                <label for="" class="form-label">Cicilan</label>
                                                <input type="text" class="form-control" name="cicilanC[0][]" id="" />
                                            </div>
                        
                                            <div class="col-6">
                                                <label for="" class="form-label">Nominal</label>
                                                <input type="text" class="form-control" name="nominalC[0][]" id="" />
                                            </div>
                            
                                            <div style="margin-top: 32px" class="col-1 mx-auto">
                                                <a href="#body-0" class="btn btn-primary w-75" onclick="addinput(0);">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="ms-auto inline-block">
                                        <a href="#body-0" class="btn btn-primary my-2 ms-auto float-end" onclick="addpanel();">
                                            Tambah Angsuran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <input type="hidden" name="type_input" value="generate" id="type_input"> -->
            </div>
            <div class="card-footer text-muted">
                <button type="button" class="btn btn-success btn-gen" disabled>Cek Generate Biaya</button>
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

        // function initMaskMoney() {
        //     $('.money').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', affixesStay: true}); 
        // }
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
        $('.generate').click(function() {
            // $('#type_input').val('generate');
            $('.btn-gen').show();
        })
        $('.custom').click(function() {
            // $('#type_input').val('custom');
            $('.btn-gen').hide();
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
                    
                    if(x[i][j] != null){
                        cell.innerHTML = "<input type='text' class='form-control' name='anggaran["+angsuran[j]+"]["+i+"]' value='Rp. "+x[i][j]+"' disabled>"
                    }
                    row.appendChild(cell);
                    // initMaskMoney();
                }
                tblBody.appendChild(row);
            }
            tbl.appendChild(tblBody);
            // initMaskMoney();
            tbl.setAttribute("border", "2");
        }
    </script>
    <script type="text/javascript">
        var panelId = 0;
        var inputId = 0;
        
        function addElement(parentId, elementTag, elementId, html){
            var id = document.getElementById(parentId);
            console.log(parentId);

            var newElement = document.createElement(elementTag);
            newElement.setAttribute('id', elementId);
            newElement.innerHTML = html;
            console.log(newElement);

            id.appendChild(newElement); 
        }
        
        function removepanel(elementId){
            var panelId = "panel-"+elementId;
            var element = document.getElementById(panelId);
            element.parentNode.removeChild(element);
        }

        function removeinput(elementId){
            var inputId = "input-"+elementId;
            var element = document.getElementById(inputId);
            element.parentNode.removeChild(element);
        }
        
        function addpanel(){
            panelId++;
            var html = '';
                html +=	'<div id="" style="position: relative" class="card mt-4">';
                html +=	'<div class="card-body" id="body-'+panelId+'">';
                html +=	'	  <div class="row">';
                html +=	'		<div class="col-11 mb-2">';
                html +=	'		  <label for="" class="form-label">Angsuran</label>';
                html +=	'		  <input type="text" class="form-control" name="angsuranC['+panelId+']" id="" />';
                html +=	'		</div>';
        
                html +=	'		<div id="" class="row">';
                html +=	'		  <div class="col-5">';
                html +=	'			<label for="" class="form-label">Cicilan</label>';
                html +=	'			<input type="text" class="form-control" id="" name="cicilanC['+panelId+'][]"/>';
                html +=	'		  </div>';
        
                html +=	'		  <div class="col-6">';
                html +=	'			<label for="" class="form-label">Nominal</label>';
                html +=	'			<input type="text" class="form-control" id="" name="nominalC['+panelId+'][]"/>';
                html +=	'		  </div>';
        
                html +=	'		  <div style="margin-top: 32px" class="col-1 mx-auto">';
                html +=	'			<a href="#body-'+panelId+'" class="btn btn-primary w-75" onclick="addinput('+panelId+');">+</a>';
                html +=	'		  </div>';
                html +=	'		</div>';
                html +=	'	  </div>';
                html +=	'  </div>';

                html +=	'  <a href="#body-'+panelId+'" style="position: absolute; right: 0" class="btn btn-danger" onclick="removepanel('+panelId+');">';
                html +=	'	Hapus Angsuran';
                html +=	'  </a>';
                html +=	'</div>';
            addElement('panels', 'div', 'panel-'+ panelId, html);
        }
        function addinput(panelId){
            inputId++;
            var html = 	'';
                html +=	'		<div id="" class="row">';
                html +=	'		  <div class="col-5">';
                html +=	'			<label for="" class="form-label">Cicilan</label>';
                html +=	'			<input type="text" class="form-control" id="" name="cicilanC['+panelId+'][]"/>';
                html +=	'		  </div>';
        
                html +=	'		  <div class="col-6">';
                html +=	'			<label for="" class="form-label">Nominal</label>';
                html +=	'			<input type="text" class="form-control" id="" name="nominalC['+panelId+'][]"/>';
                html +=	'		  </div>';
        
                html +=	'		  <div style="margin-top: 32px" class="col-1 mx-auto">';
                html +=	'			<a href="#body-'+panelId+'" class="btn btn-danger w-75" onclick="removeinput('+inputId+');">-</a>';
                html +=	'		  </div>';
                html +=	'		</div>';

            addElement('body-'+panelId, 'div', 'input-'+ inputId, html);
        }
    </script>
@endpush