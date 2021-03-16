@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-6">
            <div class="card py-3">
                <div class="card-header bg-white text-center border-white">
                    <h1 class="mt-2 mb-0 font-weight-bold">
                        Bem-vindo!
                    </h1>
                    <p class="mb-0">Essa é a board mais linda de todos os tempos da última semana!</p>
                </div>

                <div class="card-body px-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">
                                E-mail
                            </label>

                            <div class="mt-0">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">Senha</label>

                            <div class="mt-0">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        Manter logado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    Entrar
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link mt-2" href="{{ route('password.request') }}">
                                        Esqueceu a senha?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6">
            <lottie-player
                src="https://assets9.lottiefiles.com/packages/lf20_pyncsi2m.json"
                background="white"
                speed="1"
                loop="true"
                style="width: 100%; height: auto;margin: 0 auto; background: white"
                autoplay
            >
            </lottie-player>
        </div>
    </div>
</div>
@endsection
