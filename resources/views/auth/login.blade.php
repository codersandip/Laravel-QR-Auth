@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email', \App\Models\User::first()->email) }}" required
                                        autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        value="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="text-center">
                            <div class="d-none">
                                <h5 class="text-center"></h5>
                                <img class="d-block mx-auto" id="qr">
                                <p class="text-center"></p>
                            </div>
                            <button class="btn btn-primary mx-auto d-block px-5" id="show-qr" data-show="0">Show QR to
                                Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="module">
        $(document).ready(function() {
            var timer = new Timer();
            var code = '';
            let showQrTimer;

            function showQr() {
                timer.start({
                    countdown: true,
                    startValues: {
                        seconds: 120
                    }
                });
                showQRData();
                showQrTimer = setInterval(() => {
                    showQRData();
                }, 1000 * 120);
            }

            timer.addEventListener('secondsUpdated', function(e) {
                // console.log(timer.getTimeValues().toString());
                $('#qr').parent().find('h5').text(timer.getTimeValues().toString());
            });

            function showQRData() {
                axios.get('{{ route('qr.login') }}').then((response) => {
                    timer.reset();
                    $('#qr').attr('src', response.data.data.qr);
                    code = response.data.data.qr_login;
                    $('#qr').parent().find('p:last').text(response.data.data.qr_login);
                    $('#qr').removeClass('d-none');
                })
            }
            $('#show-qr').on('click', function() {
                if ($(this).data('show') == 0) {
                    $(this).data('show', 1);
                    $(this).parent().parent().find('div.text-center>div').toggleClass('d-none');
                    $(this).parent().parent().find('form').toggleClass('d-none');
                    $(this).parent().parent().find('hr').toggleClass('d-none');
                    $(this).text('Hide QR');
                    showQr();
                } else {
                    $(this).data('show', 0);
                    $(this).parent().parent().find('div.text-center>div').toggleClass('d-none');
                    $(this).parent().parent().find('form').toggleClass('d-none');
                    $(this).parent().parent().find('hr').toggleClass('d-none');
                    $(this).text('Show QR to Login');
                    $('#qr').addClass('d-none');
                    clearInterval(showQrTimer)
                    timer.stop();
                }
            });

            Echo.channel(`qr-login`)
                .listen('QrLogin', (e) => {
                    if (e.qr_login_data.code == code) {
                        axios.post('{{ route('qr.login') }}', {
                            user: e.qr_login_data.user
                        }).then((response) => {
                            if(response.data.status){
                                window.location.href = '{{ route('home') }}';

                            }

                        }).catch((error) => {
                            alert('Login Failed...!');
                        })
                    }
                });
        });
    </script>
@endpush
