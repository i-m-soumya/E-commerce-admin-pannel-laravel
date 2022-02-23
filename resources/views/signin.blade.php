@extends('layouts.login')

@section('content')
<div class="container-fluid ">

    <div class=" no-pdding login-box">
        <div class="row no-margin w-100 bklmj">
            <div class="col-lg-6 col-md-6 log-det">

                <h2>Login</h2>

                <div class="text-box-cont mt-4">
                    <form>
                        <div class="form-group">
                            <label for="email" class="text-md-right">Email</label>
                            <input id="email" type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="form-group ">
                            <label for="password" class="text-md-right">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="row no-margin">
                            <a class="forget-p" href="#">Forget password?</a>
                        </div>
                        <div class="right-bkij mb-3">
                            <button class="btn btn-info" id="Login">Login</button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-lg-6 col-md-6 box-de">
                <div class="ditk-inf">
                    <h2 class="w-100">Welcome Back Admin</h2>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        login();
    });

</script>
@endsection
