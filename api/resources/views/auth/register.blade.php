@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center" style="width: 33%;">
    <div class="items-center mb-10 pb-6 aos-init aos-animate border-b border-blue-700" data-aos="fade-up">
        <h4 class="text-3xl font-semibold mb-4 md:mb-0 text-white">Register</h4>
    </div>

    <div class="card-body" data-aos="fade-up">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row mb-3">
                <label for="name" class="block font-medium text-gray-500 mb-1">{{ __('Name') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="background: #1a2238;">

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-red-500">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="block font-medium text-gray-500 mb-1">{{ __('Email Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background: #1a2238;">

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
                    <input id="password" type="password" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="background: #1a2238;">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-red-500">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-8">
                <label for="password-confirm" class="block font-medium text-gray-500 mb-1">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="w-full border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3 text-white text-center" name="password_confirmation" required autocomplete="new-password" style="background: #1a2238;">
                </div>
            </div>

            <div class="row mb-0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-lg px-4 py-2 cursor-pointer">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
