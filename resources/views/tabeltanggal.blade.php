@extends('layout/main')

@section('title', 'Report Data')

@section('container')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"
        integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="container ">
        <div class="text-center p-3">
            <h2>Tabel NKS/Tanggal {{ $namakab[0]->nm_kab }}</h2>
        </div>
        @if (session('kode_kab') == '00')
            <div>
                <form>
                    <div id="divkab">
                        <label for="kablist" class="">Kabupaten/kota</label>
                    <input class=" form-control"
                            list="kaboption" id="kablist" placeholder="Type to search..." name="kab" autocomplete="off">
                            <datalist id="kaboption">
                                {{-- <option value="1600" selected>Semua</option> --}}
                                @foreach ($kabkotlist as $kabkot)
                                    <option value={{ $kabkot->id_kab }} selected>{{ $kabkot->nm_kab }}</option>
                                @endforeach
                            </datalist>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
            </div>
        @endif

        <br>
        <div class="border rounded">
            <h3 class=" m-2">Tabel</h3>
            <div class="d-flex justify-content-center m-2">
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
    </script>
@endsection
