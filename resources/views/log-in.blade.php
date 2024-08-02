<!-- PAGE INDIVIDUAL STYLE  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
{{-- <link href="{{url('public/front_panel_assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />--}}
<style>
    html,
    body {
        width: 100%;
        height: 100%;
    }

    body {
        background: linear-gradient(-45deg, #1e3c72, #2a5298, #6b93d6, #a3c9f9);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
            <a class="nav-link text-dark" href="{{url('log-in')}}">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{url('sign-up')}}">Sign up</a>
        </li>
    </ul>
</nav>
<section class="sign_up padding-top">
    <div class="container auto-container">
        <div class="row no-gutters justify-content-center">
            <div class="col-4 my-4">
                @if (Session::has('success'))
                <div class="alert alert-success mb-0 text-center">
                    {{ Session::get('success') }}
                </div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-danger mb-0 text-center">
                    {{ Session::get('error') }}
                </div>
                @endif
                <div class="js-alerts"></div>
            </div>
        </div>
        <div class="row no-gutters justify-content-center">
            <div class="col-4">
                <form class="label-form-style myform" action="{{ url('manual-login') }}" method="post">
                    @csrf
                    <div class="row no-gutters justify-content-center">
                        <div class="col-12">
                            <h5 class="pb-2 text-white">Log In to Your Account</h5>
                            <div class="form-group">
                                <label class="text-white">Email Address<span class="red-color">*</span></label>
                                <input type="email" placeholder=" " class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                                <span class="sign_up_form_icon"><i class="las la-envelope"></i></span>
                                @error('email')
                                <strong><small class="text-danger font-12">{{ $message }}</small></strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-white">Password<span class="red-color">*</span></label>
                                <input type="password" placeholder=" " class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                                <span class="sign_up_form_icon"><i onclick="myFunction(this)" class="lar la-eye-slash"></i></span>
                                @error('password')
                                <strong><small class="text-danger font-12">{{ $message }}</small></strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger">Log In</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000); // 5000 milliseconds = 5 seconds
</script>

<!-- PAGE INDIVIDUAL SCRIPT  -->
<script type="text/javascript" src="{{url('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script> 
 