@extends('layout/main')

@section('title', 'Report Data')

@section('container')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"
    integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<div class="container">
    <div class="col-8">
        <h3>Filter</h3>
        <form action="">
            {{-- pilih filter --}}
            {{-- <select class="form-select" aria-label="Default select example" name="filter" id="filter">
                <option selected value="0">Tapilkan semua</option>
                <option value="1">Kecamatan per Kabupaten/Kota</option>
                <option value="2">NKS</option>
                <option value="3">Pengguna</option>
            </select> --}}
            {{-- <label for="kabkotlist" class="">Kabupaten / Kota</label>
            <input class="form-control" list="kabkotoption" id="kabkotlist" placeholder="Type to search...">
            <datalist id="kabkotoption">
                <option value="semua">Semua</option>
            </datalist> --}}
            <div id="divkab">
                <label for="kablist" class="">Kabupaten/kota</label>
                <input class="form-control" list="kaboption" id="kablist" placeholder="Type to search..." name="kab"
                    autocomplete="off">
                <datalist id="kaboption">
                    <option value="1600" selected>Semua</option>
                    @foreach ($kabkotlist as $kabkot)
                    <option value={{$kabkot->id_kab}} selected>{{$kabkot->nm_kab}}</option>
                    @endforeach
                </datalist>
            </div>
            {{-- <div id="divnks">
                <label for="nks" class="">NKS</label>
                <input class="form-control" id="nks" placeholder="jumlah nks" name="nks">
            </div> --}}

            <div id="divpetugas">
                <label for="petugaslist" class="">Petugas</label>
                <input class="form-control" list="petugasoption" id="petugaslist" placeholder="Type to search..."
                    name="petugas">
                <datalist id="petugasoption">
                    <option value="0" selected>Semua</option>
                    @foreach ($petugass as $petugas)
                    <option value={{$petugas->id}} selected>{{$petugas->name}}</option>
                    @endforeach
                </datalist>
            </div>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>


    </div>

    <div>
        <canvas id="myChart" height="20vh" width="60vw"></canvas>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    @if ($request->kab != null || $request->kab != '1600' )
                    <th scope="col">NKS</th>
                    @else
                    <th scope="col">Kabupaten/Kota</th>
                    @endif
                    <th scope="col">Dokumen Diterima</th>
                    <th scope="col">Dokumen Diserahkan</th>
                    @if ($request->kab != null && $request->kab != '1600' )
                    <th scope="col">PML</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ( $datas as $key => $data)
                <tr>
                    <th scope="row">{{$key}}</th>
                    <td>{{$data->nama}}</td>
                    <td>{{$data->dok_diterima}}</td>
                    <td>{{$data->dok_diserahkan}}</td>
                    @if ($request->kab != null && $request->kab != '1600' )
                    <td>{{$data->pml}}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(".report").addClass("active");
    var request = {!! json_encode($request->toArray()) !!};
    if(request.kab != null || request.kab != '1600'){
        $('#kablist').val(request.kab);
    }
    if(request.petugas != null || request.petugas != '0'){
        $('#petugaslist').val(request.petugas);
    }

    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($arraykab) !!},
            datasets: [{
                label: 'dokumen diterima',
                data: {!! json_encode($arraydok_terima) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            },
            {
                label: 'Dokumen diserahkan',
                data: {!! json_encode($arraydok_serah) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.3)',
                    'rgba(54, 162, 235, 0.3)',
                    'rgba(255, 206, 86, 0.3)',
                    'rgba(75, 192, 192, 0.3)',
                    'rgba(153, 102, 255, 0.3)',
                    'rgba(255, 159, 64, 0.3)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            },
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


    /*
    $('#filter').on('change', function() {
        if(this.value == 1 ){
            // alert( this.value );
            $('#divkab').show()
            $('#divnks').hide()
            $('#divpetugas').hide()
            $('#keclist').removeAttr('disabled');
            $('#petugaslist').attr('disabled', 'disabled');
            $('#nks').attr('disabled', 'disabled');
        }
        if(this.value == 2 ){
            $('#divnks').show()
            $('#divpetugas').hide()
            $('#divkab').hide()
            $('#nks').removeAttr('disabled');
            $('#petugaslist').attr('disabled', 'disabled');
            $('#keclist').attr('disabled', 'disabled');
        }
        if(this.value == 3 ){
            $('#divpetugas').show()
            $('#divnks').hide()
            $('#divkab').hide()
            $('#petugaslist').removeAttr('disabled');
            $('#keclist').attr('disabled', 'disabled');
            $('#nks').attr('disabled', 'disabled');
        }
    });
    */

    
</script>
@endsection

@section('style')
<style>
    select.form-control {
        display: inline-block
    }

    option {
        /* width: 100px; */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection