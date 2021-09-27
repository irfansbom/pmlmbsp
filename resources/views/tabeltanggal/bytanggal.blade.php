@extends('layout/main')

@section('title', 'Tabel Tanggal')

@section('container')
<div class="container ">
    <div class="text-center p-3">
        <h2>Tabel NKS Tanggal {{ $tgls }} </h2>
    </div>

    <div class="container border rounded my-2 p-2">
        <div class="row">

            <div class="col-8">
                <h3>Filter</h3>
                <form>
                    <fieldset>
                        @if (session('kode_kab') == '00')
                        <div id="divkab">
                            <label for="kablist" class="">Kabupaten/kota</label>
                            <input class="
                                        form-control" list="kaboption" id="kablist" placeholder="Type to search..."
                                name="kab" autocomplete="off" onClick="this.setSelectionRange(0, this.value.length)">
                            <datalist id="kaboption">
                                {{-- <option value="1600" selected>Semua</option> --}}
                                @foreach ($kabkotlist as $kabkot)
                                <option value={{ $kabkot->id_kab }} selected>{{ $kabkot->nm_kab }}
                                </option>
                                @endforeach
                            </datalist>
                        </div>
                        @endif
                        <div id="divtgl">
                            <label for="tgllist" class="">Tanggal</label>
                            <input class="
                                        form-control" list="tgloption" id="tgllist" placeholder="Type to search..."
                                name="tgl" autocomplete="off" onClick="this.setSelectionRange(0, this.value.length)">
                            <datalist id="tgloption">
                                {{-- <option value="1600" selected>Semua</option> --}}
                                @foreach ($tanggallist as $tgl)
                                <option value={{ $tgl['tanggal'] }} selected>
                                </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="d-flex justify-content-end m-1">
                            <button type="submit" class="btn btn-primary btn-lg  my-2">Filter</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class='col-4'>
                Pilihan filter
                <div class="border rounded ">
                    <div class="form-check m-2">
                        <input class="form-check-input" type="radio" name="pilihanfilter" id="filter1" value="1">
                        <label class="form-check-label" for="filter1">
                            NKS Kabupaten/Tanggal
                        </label>
                    </div>
                    <div class="form-check m-2">
                        <input class="form-check-input" type="radio" name="pilihanfilter" value="2" id="filter2"
                            checked>
                        <label class="form-check-label" for="filter2">
                            Pilih Tanggal
                        </label>
                    </div>
                    <div class="form-check m-2">
                        <input class="form-check-input" type="radio" name="pilihanfilter" value="3" id="filter3">
                        <label class="form-check-label" for="filter3">
                            Pilih PML
                        </label>
                    </div>
                    <div class="d-flex justify-content-end m-1">
                        <button class="btn btn-primary my-2" id="pilihan">Pilih</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container border rounded p-2">
        <div class="d-flex  justify-content-between">
            <h3 class="">Tabel</h3>
            <a class="btn btn-success btn-sm  m-2" id="downloadbtn">Download</a>
        </div>
        <div class="justify-content-center ">
            <table class="table table-striped table-sm text-center">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="3" class="align-middle">No</th>
                        <th rowspan="3" class="align-middle">NKS</th>
                        <th rowspan="3" class="align-middle">Diterima</th>
                        <th rowspan="3" class="align-middle">Diserahkan</th>
                        <th rowspan="3" class="align-middle">Deskripsi</th>
                        <th rowspan="3" class="align-middle">Terakhir diupdate</th>
                </thead>
                <tbody>
                    @foreach ($datas2 as $key => $data2)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $data2['nks'] }}</td>
                        {{-- @foreach ($tanggal as $key => $tgl) --}}
                        <td>{{ $data2['dok_diterima'] }}</td>
                        <td>{{ $data2['dok_diserahkan'] }}</td>
                        <td>{{ $data2['deskripsi'] }}</td>
                        <td>{{ $data2['terakhir_diupdate'] }}</td>
                        {{-- @endforeach --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="border rounded my-3">
        <h3 class=" m-2 ">Grafik Batang</h3>
        <div class="d-flex justify-content-center m-2">
            <canvas id="myChart" height="20vh" width="60vw"></canvas>
        </div>
    </div>

</div>

@endsection
@section('script')
<script>
    $(".tanggal").addClass("active");
    var request = {!! json_encode($request->toArray()) !!};
        if (request.tgl != null) {
            $('#tgllist').val(request.tgl);
        } else {
            $('#tgllist').val("2021-09-23");
        }



        var request = {!! json_encode($request->toArray()) !!};
        if (request.kab != null) {
            $('#kablist').val(request.kab);
        } else {
            $('#kablist').val("1601");
        }
        var ctx = document.getElementById('myChart').getContext('2d');
        console.log({!! json_encode($dataset1) !!})
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {!! json_encode($dataset1[0]) !!},
                    {!! json_encode($dataset2[0]) !!}
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        stacked: true
                    }
                }
            }
        });

        $("#pilihan").click(function() {
            console.log($("input[name='pilihanfilter']:checked").val())
            var val = $("input[name='pilihanfilter']:checked").val()
            if (val == 1) {
                window.location.href = " {{ url('/tabeltanggal') }}"
            } else if (val == 3) {
                window.location.href = " {{ url('/tabeltanggal/bypml') }}"
            }
        })

        $('#downloadbtn').click(function(){
           var request = {!! json_encode($request->toArray()) !!}
           console.log(request)
           var kd_kab = "";
           var tgl = "";
           var petugas = "";
           if(request.kab != null && request.kab != '1600'){
            kd_kab = request.kab
           }else{
            kd_kab = 1601;
           }
        //    if(request.petugas != null && request.petugas != '0'){
        //     petugas = request.petugas
        //    }else{
        //     petugas = ""
        //    }
           if(request.tgl != null ){
            tgl = request.nks
           }else{
            tgl = "2021-09-23"
           }
        //    console.log(kd_kab)
           window.location.href = "downloadtabeltanggalbytanggal?kab="+kd_kab+
           "&tgl="+ tgl
        })
</script>
@endsection
