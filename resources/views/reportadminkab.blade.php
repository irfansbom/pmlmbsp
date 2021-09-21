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
                <div id="divpetugas">
                    <label for="petugaslist" class="">Petugas</label>
                <input class=" form-control"
                        list="petugasoption" id="petugaslist" placeholder="Type to search..." name="petugas"
                        autocomplete="off">
                        <datalist id="petugasoption">
                            <option value="0" selected>Semua</option>
                            @foreach ($petugass as $petugas)
                                <option value={{ $petugas->kode }} selected>{{ $petugas->nm_petugas }}</option>
                            @endforeach
                        </datalist>
                </div>

                <div id="divnks">
                    <label for="nkslist" class="">NKS</label>
                <input class=" form-control" list="nksoption"
                        id="nkslist" placeholder="Cari NKS.." name="nks" autocomplete="off">
                        <datalist id="nksoption">
                            <option value="0" selected>Semua</option>
                            @foreach ($nkss as $nks)
                                <option value={{ $nks->nks }} selected></option>
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
                        @if ($request->petugas == null || $request->petugas == '0')
                            @if ($request->nks == null || $request->nks == '0')
                                <th scope="col">NKS</th>
                            @else
                                <th scope="col">Waktu</th>
                            @endif
                        @else
                            @if ($request->nks == null || $request->nks == '0')
                                <th scope="col">NKS</th>
                            @else
                                <th scope="col">Waktu</th>
                            @endif
                        @endif
                        <th scope="col">Dokumen Diterima</th>
                        <th scope="col">Dokumen Diserahkan</th>
                        @if ($request->petugas == null || $request->petugas == '0')
                            @if ($request->nks == null || $request->nks == '0')
                                <th scope="col">Deskripsi</th>
                                <th scope="col">PML</th>
                                <th scope="col">Terakhir Update</th>
                            @else
                                <th scope="col">Deskripsi</th>
                            @endif
                        @else
                            @if ($request->nks == null || $request->nks == '0')
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Terakhir Update</th>
                            @else
                                <th scope="col">Deskripsi</th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->dok_diterima }}</td>
                            <td>{{ $data->dok_diserahkan }}</td>
                            @if ($request->petugas == null || $request->petugas == '0')
                                @if ($request->nks == null || $request->nks == '0')
                                    <td>{{ $data->deskripsi }}</td>
                                    <td>{{ $data->pml }}</td>
                                    <td>{{ $data->updated_at }}</td>
                                @else
                                    <td>{{ $data->deskripsi }}</td>
                                @endif
                            @else
                                @if ($request->nks == null || $request->nks == '0')
                                    <td>{{ $data->deskripsi }}</td>
                                    <td>{{ $data->updated_at }}</td>
                                @else
                                    <td>{{ $data->deskripsi }}</td>
                                @endif

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
        if (request.kab != null || request.kab != '1600') {
            $('#kablist').val(request.kab);
        }
        if (request.petugas != null || request.petugas != '0') {
            $('#petugaslist').val(request.petugas);
        }
        if (request.nks != null || request.nks != '0') {
            $('#nkslist').val(request.nks);
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        if (request.nks != null && request.nks != '0') {
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_reverse($arraykab)) !!},
                    datasets: [{
                        label: 'dokumen diterima',
                        data: {!! json_encode(array_reverse($arraydok_terima)) !!},
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
                    }, {
                        label: 'Dokumen diserahkan',
                        data: {!! json_encode(array_reverse($arraydok_serah)) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
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
                    }, ]
                }
            });
        } else {
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($arraykab) !!},
                    datasets: [{
                            label: 'dokumen diterima',
                            data: {!! json_encode($arraydok_terima) !!},
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
                        {
                            label: 'Dokumen diserahkan',
                            data: {!! json_encode($arraydok_serah) !!},
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
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
        }
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
