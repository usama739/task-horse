@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center" style="width: 33%;">
    <div class="items-center mb-10 pb-6 aos-init aos-animate border-b border-blue-700" data-aos="fade-up">
        <h4 class="text-3xl font-semibold mb-4 md:mb-0 text-white">Login</h4>
    </div>

    <div class="card-body" data-aos="fade-up">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="row mb-3">
                <label for="email" class="block font-medium text-gray-500 mb-1">{{ __('Email Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="background: #1a2238;">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-red-500">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="block font-medium text-gray-500 mb-1">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="background: #1a2238;">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-red-500">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-8">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="background: #1a2238;">

                        <label class="font-medium text-gray-500" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-6 justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg px-4 py-2" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
