@extends('layouts.app')

@section('page-title', 'Tambah Rencana Pembayaran')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
        <div class="card">
            <div class="card-header">Form Rencana Pembayaran</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select name="mahasiswa" id="mahasiswa" class="form-select">
                        <option value="">Pilih Mahasiswa</option>
                        @foreach ($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}">{{ ucwords($mahasiswa->nama_lengkap) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Komponen Biaya</label>
                    <select name="item" id="item-bayars" class="form-select">
                        <option value="">Pilih Komponen Biaya</option>
                    </select>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary" id="submit">Simpan</button>
            </div>
        </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/jquery.redirect.js') }}"></script>

    <script>
        $(document).ready(function() {
            var mahasiswa = null;
            var item_bayars = null;
            var kampus = {{ Session::has('id_kampus')? Session::get('id_kampus'):null }};

            function init_dropdown(element,isfirst){
                $(element).select2({
                    theme: 'bootstrap-5'
                });
                if(isfirst==0){
                    $(element+' + span').addClass("w-100");
                }
            }
            function normalizeValue(val){
                return val=="" || val=="null" || val==null || val==undefined? null:val
            }
            function callDataItems() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                    url: "{{ route('getDataItems') }}",
                    type: 'POST',
                    // contentType: "application/json",
                    dataType: "json",
                    data: {
                        id_kampus:kampus,
                        id_mahasiswa:mahasiswa
                    },
                    success: function (response) {
                        if(response.status==200){
                            resolve(response.data)
                        }
                        else{
                            reject(response.error)    
                        }
                    },
                    error: function (xhr, status, error) {
                        if(status=="error"){
                            reject('Something wrong in the system')
                        }
                        else if(status=="timeout"){
                            reject('Connection seems dead!')    
                        }
                        else{
                            reject(JSON.stringify(error));    
                        }
                    },
                    })
                })
            }
            function getDataItems(){
                if(mahasiswa != null){
                    callDataItems().then((data) => {
                        console.log(data)
                        if(data.length>0){
                            $("#item-bayars").empty();
                            $("#item-bayars").append("<option>-- Pilih Komponen Biaya --</option>");
                            $("#item-bayars").select2({
                                data: data
                            });
                            init_dropdown("#item-bayars",1);
                        }
                        else{
                            $("#item-bayars").empty();
                            $("#item-bayars").append("<option>-- Pilih Komponen Biaya --</option>");
                            init_dropdown("#item-bayars",1);
                        }
                    }).catch((error) => {
                        alert(error);
                        $("#item-bayars").empty();
                        $("#item-bayars").append("<option>-- Pilih Komponen Biaya --</option>");
                        init_dropdown("#item-bayars",1);
                    })
                }
                else{
                    $("#item-bayars").empty();
                    $("#item-bayars").append("<option>-- Pilih Komponen Biaya --</option>");
                    init_dropdown("#item-bayars",1);
                }
            }

            init_dropdown("#mahasiswa",0);
            init_dropdown("#item-bayars",0);
            $('#mahasiswa').on('change',function(){
                mahasiswa = normalizeValue($(this).val());
                getDataItems();
            });
            $('#item-bayars').on('change',function(){
                item_bayars = normalizeValue($(this).val());
            });
            $('#submit').click(function(){
                $.redirect("{{ route('kampus.mahasiswa.rencana.store') }}", {'item_bayars':item_bayars,'id_mahasiswa':mahasiswa}, 'POST');
            });            
        });
    </script>
@endpush