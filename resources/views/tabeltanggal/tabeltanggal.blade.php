@extends('layout/main')

@section('title', 'Tabel Tanggal')

@section('container')
<div class="container ">
    <div class="text-center p-3">
        <h2>Tabel NKS/Tanggal {{ $namakab[0]->nm_kab }}</h2>
    </div>

    <div class="container border rounded my-2 p-2">
        <div class="row">
            @if (session('kode_kab') == '00')
            <div class="col-8">
                <h3>Filter</h3>
                <form>
                    <fieldset>
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
                        <div class="d-flex justify-content-end m-1">
                            <button type="submit" class="btn btn-primary btn-lg  my-2">Filter</button>
                        </div>
                    </fieldset>

                </form>
            </div>
            @endif
            <div class='col-4'>
                Pilihan filter
                <div class="border rounded ">
                    <div class="form-check m-2">
                        <input class="form-check-input" type="radio" name="pilihanfilter" id="filter1" value="1"
                            checked>
                        <label class="form-check-label" for="filter1">
                            NKS Kabupaten/Tanggal
                        </label>
                    </div>
                    <div class="form-check m-2">
                        <input class="form-check-input" type="radio" name="pilihanfilter" value="2" id="filter2">
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
    <div class=" container border rounded">
        <div class="d-flex  justify-content-between">
            <h3 class="">Tabel</h3>
            <a class="btn btn-success btn-sm m-2" id="downloadbtn">Download</a>
        </div>
        <div class="d-flex justify-content-center">
            <table class="table table-striped table-sm text-center">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="3" class="align-middle">No</th>
                        <th rowspan="3" class="align-middle">NKS</th>
                        <th colspan={{ count($tanggal) * 2 }} class="text-center">Tanggal</th>
                    </tr>
                    <tr>
                        @foreach ($tanggal as $tgl)
                        <th colspan="2" class="text-center">{{ $tgl['tanggal'] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($tanggal as $tgl)
                        <td>Diterima</td>
                        <td>Diserahkan</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas2 as $key => $data2)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $data2['nks'] }}</td>
                        @foreach ($tanggal as $key => $tgl)
                        <td>{{ $data2['dok_diterima'][$key] }}</td>
                        <td>{{ $data2['dok_diserahkan'][$key] }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="border rounded my-3">
        <h3 class=" m-2 ">Dokumen Diterima</h3>
        <div class="d-flex justify-content-center m-2">
            <canvas id="myChart" height="20vh" width="60vw"></canvas>
        </div>
    </div>
    <div class="border rounded my-3">
        <h3 class=" m-2 ">Dokumen Diserahkan</h3>
        <div class="d-flex justify-content-center m-2">
            <canvas id="myChart2" height="20vh" width="60vw"></canvas>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
    $(".tanggal").addClass("active");

        var request = {!! json_encode($request->toArray()) !!};
        if (request.kab != null) {
            $('#kablist').val(request.kab);
        } else {
            $('#kablist').val('1601');
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: {!! json_encode($dataset1) !!},
            }
        });

        var ctx2 = document.getElementById('myChart2').getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: {!! json_encode($dataset2) !!},
            }
        });
        $("#pilihan").click(function() {
            console.log($("input[name='pilihanfilter']:checked").val())
            var val = $("input[name='pilihanfilter']:checked").val()
            if (val == 2) {
                window.location.href = " {{ url('/tabeltanggal/bytanggal') }}"
            } else if (val == 3) {
                window.location.href = " {{ url('/tabeltanggal/bypml') }}"
            }
        })

        $('#downloadbtn').click(function(){
           var request = {!! json_encode($request->toArray()) !!}
           console.log(request)
           var kd_kab = "";
           var nks = "";
           var petugas = "";
           if(request.kab != null && request.kab != '1600'){
            kd_kab = request.kab
           }else{
            kd_kab = 1601;
           }
           if(request.petugas != null && request.petugas != '0'){
            petugas = request.petugas
           }else{
            petugas = ""
           }
           if(request.nks != null && request.nks != '1600'){
            nks = request.nks
           }else{
            nks = ""
           }
        //    console.log(kd_kab)
           window.location.href = "downloadtabeltanggal?kab="+kd_kab+"&petugas="+ petugas+
           "&nks="+ nks
        })
</script>
@endsection
