<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MPUAT Guest House Management| Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">

      
        <div class="login-logo">
            <img src="{{'/img/ctaeLogo.jpeg'}}"  class="img-circle elevation-2"/>
            <br>
            <b>MPUAT GUEST HOUSE</b>
        </div>
        <!-- /.login-logo -->

        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in </p>

            <form action="{{ url('/login') }}" method="post">
                @csrf
                <div class="input-group">
                    <input type="email" class="form-control @error('email_address') is-invalid @enderror" name="email_address" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mt-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>


            <p class="mb-0">
                <a href="/register" class="text-center">Register a new membership</a>
            </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script src="{{ asset('js/app.js') }}"></script>
    @if (session('status'))
    <script>
        const message = @json(session('status'));
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });

        Toast.fire({
            icon: 'success',
            title: message,
        });
    </script>
    @endif
    @if (session('error'))
    <script>
        const message = @json(session('error'));
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });

        Toast.fire({
            icon: 'error',
            title: message,
        });
    </script>
    @endif
</body>
</html>
