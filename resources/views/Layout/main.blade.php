<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- boostrap --}}
    <!-- <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{-- datatables --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    {{-- datatables --}}
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js">
    </script>
    {{-- jquery validate --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"
        integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous">
    </script>
    {{-- sweetalert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <title>@yield('title')</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: turquoise">
        <a class="navbar-brand"><img alt="MBSP" src="{{asset('/pictures/SiKat1.png')}}" width="100" height="30"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item katalog">
                    <a class="nav-link" href="{{url('katalog')}}">Entry</a>
                </li>
                <li class="nav-item beranda">
                    <a class="nav-link" href="{{url('tentang')}}">Report </a>
                </li>
                @if(session('username'))
                <li class="nav-item admin">
                    <a class="nav-link" href="{{url('admin')}}">List Publikasi</a>
                </li>
                <li class="nav-item qrcode">
                    <a class="nav-link" href="{{url('qrcode')}}">Cetak QR Code </a>
                </li>
                <li class="nav-item scanner">
                    <a class="nav-link" href="{{url('scanner')}}">Scan QR Code </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
    @yield('container')
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>BPS Provinsi Sumatera Selatan</span></strong>.
            </div>
            <div class="credits">
                Designed by <a href="" style=" color: black;">SUMSEL ART</a>
            </div>
        </div>
    </footer>
</body>

</html>
@yield('style')
@yield('script')
<style>
    /*--------------------------------------------------------------
# Footer
--------------------------------------------------------------*/
    #footer {
        background: turquoise;
        padding: 30px 0;
        color: black;
        font-size: 14px;
        /* position: fixed;
        bottom: 0; */
        /* width: -webkit-fill-available; */
    }

    #footer .copyright {
        text-align: center;
    }

    #footer .credits {
        padding-top: 10px;
        text-align: center;
        font-size: 13px;
        color: black;
    }
</style>