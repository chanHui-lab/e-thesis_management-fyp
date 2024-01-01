@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-7">
            <div class="navbar-brand loginbrand-logo" href="#">
                <img class="logo-login" src='{{ asset('admindash/img/logoethesis.png') }}' alt="logo"/>
            </div>
            <div  style="display: flex;justify-content: center;">
                <div class="col-2">
                    <!-- Calendar Icon -->
                    <img class="logo-upm" src='{{ asset('admindash/img/UPMLOGO.png') }}' alt="logo"/>                </div>
                <div class="col-7  row align-items-center">
                    <p class = "plogo">Powered by University Putra Malaysia</p>
                </div>
            </div>
            {{-- <div style="display: flex; align-items: center; justify-content: center;">
                <img class="logo-upm" src='{{ asset('admindash/img/UPMLOGO.png') }}' alt="logo"/>
                <p class = "plogo">Powered by University Putra Malaysia</p>
            </div> --}}
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom:16px;margin-top:5px;">
                <hr style="flex-grow: 1; margin-right: 10px;">
                <h2 data-id="page-title">{{ __('Login') }}</h2>
                <hr style="flex-grow: 1; margin-left: 10px;">
            </div>
            <div class="card" style="margin-bottom: 16px;">
                {{-- <div class="card-header">{{ __('Login') }}</div> --}}

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        {{-- @if (session('status'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif --}}

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <div class="input-group-prepend">
                                    <span style="color: #FACD3F" class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                                {{-- <div>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <div class="input-group-prepend">
                                    <span style="color: #FACD3F" class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>

                                @error('password')
                                    <span style="display:flex" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role_as" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select id="role_as" class="form-control @error('role_as') is-invalid @enderror" name="role_as" required>
                                    <option value="0">Admin</option>
                                    <option value="1">Supervisor</option>
                                    <option value="2">Student</option>
                                    <!-- Add more role options as needed -->
                                </select>

                                @error('role_as')
                                    <span style="display:flex" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary loginBtn">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #FACD3F">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div> --}}
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <div>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <button type="submit" class="btn btn-primary loginBtn">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                    <div style="text-align: left;">
                                        <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #FACD3F; margin-top: 10px;">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
