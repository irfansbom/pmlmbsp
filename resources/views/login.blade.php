@extends('layout/main')

@section('title', 'Login Admin')

@section('container')

<body class="text-center">
    <div style="height: 80%; width: 100%;">
        <form class="form-signin" method="POST" action="login">
            @csrf
            <img class="mb-4" src="{{asset('/assets/images/MSBP.png')}}" alt="" width="200" height="200">
            <h1 class="h3 mb-3 font-weight-normal">Login Admin</h1>
            <input type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus
                name="username">
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required
                name="password">
            <button class="btn btn-lg btn-info btn-block" type="submit">Login</button>
            <p class="mt-5 mb-3 text-muted">&copy; MSBP 2021</p>
        </form>
    </div>
</body>
<input id="alert" value="{{Request::get('alert')}}" hidden />
{{-- @section('script') --}}
<script type="text/javascript">
    var alert = $("#alert").val()
    console.log(alert)
    if(alert=="yes"){
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Username / Password Salah ',
        background: 'white'
      })
    }
</script>
{{-- @endsection --}}
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    html,
    body {
        height: 100%;
    }

    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }

    .form-signin .form-control:focus {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
@endsection