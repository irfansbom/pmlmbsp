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
            <div id="divkec">
                <label for="keclist" class="">Kabupaten/kota</label>
                <input class="form-control" list="kecoption" id="keclist" placeholder="Type to search..." name="kec"
                    autocomplete="off">
                <datalist id="kecoption">
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
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    var request = {!! json_encode($request->toArray()) !!};
    // console.log( request.kec != '1600');

    if(request.kec != null || request.kec != '1600'){
        $('#keclist').val(request.kec);
    }

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($arraykab) !!},
        datasets: [{
            label: 'dokumen diserahkan',
            data: [12, 19, 3, 5, 2, 3, ],
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
            label: 'Dokumen diterima',
            data: [20, 25, 14, 25, 12, 13],
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



    // $('#filter').on('change', function() {
    //     if(this.value == 1 ){
    //         // alert( this.value );
    //         $('#divkec').show()
    //         $('#divnks').hide()
    //         $('#divpetugas').hide()
    //         $('#keclist').removeAttr('disabled');
    //         $('#petugaslist').attr('disabled', 'disabled');
    //         $('#nks').attr('disabled', 'disabled');
    //     }
    //     if(this.value == 2 ){
    //         $('#divnks').show()
    //         $('#divpetugas').hide()
    //         $('#divkec').hide()
    //         $('#nks').removeAttr('disabled');
    //         $('#petugaslist').attr('disabled', 'disabled');
    //         $('#keclist').attr('disabled', 'disabled');
    //     }
    //     if(this.value == 3 ){
    //         $('#divpetugas').show()
    //         $('#divnks').hide()
    //         $('#divkec').hide()
    //         $('#petugaslist').removeAttr('disabled');
    //         $('#keclist').attr('disabled', 'disabled');
    //         $('#nks').attr('disabled', 'disabled');
    //     }
    // });

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