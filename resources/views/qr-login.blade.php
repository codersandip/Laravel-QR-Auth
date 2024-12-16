@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('QR Login') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{-- <form action="" method="post">
                            @csrf
                            <div class="row mb-3">
                                <label for="code"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Code') }}</label>    
                                <div class="col-md-6">
                                    <input id="code" type="text"
                                        class="form-control @error('code') is-invalid @enderror" name="code"
                                        value="{{ old('code') }}" required autocomplete="off" autofocus>

                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form> --}}

                        <div class="text-center">
                            <video src="" autoplay playsinline id="qr-video" style="width: 100%; max-width: 500px;"></video>
                            <br>
                            <button class="btn btn-primary mx-auto d-block px-5" id="show-qr">Capture</button>
                            <canvas id="snapshot" style="display: none; margin-top: 10px; border: 1px solid #ccc;" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{-- <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script type="module">
        $(document).ready(function() {
            const canvas = document.getElementById("snapshot");
            const context = canvas.getContext("2d");
            navigator.mediaDevices.getUserMedia({
                video: true
            }).then(function(stream) {
                $('#qr-video')[0].srcObject = stream;
                
            })
            
            // $('#show-qr').click(function() {
            //     scanQR();
            // })
            const img = new Image();
            scanQR();
            function scanQR() {
                context.drawImage($('#qr-video')[0], 0, 0, canvas.width, canvas.height);

                img.onload = () => {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    context.drawImage(img, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    
                    if (code) {
                        axios.post('{{ route('qr.login.verify') }}', {
                            code: code.data
                        }).then((response) => {
                            alert('Login Success...!');
                            window.location.href = '{{ route('home') }}';
                        })
                    } else {
                        scanQR();
                    }

                };
                img.src = canvas.toDataURL('image/png');
            }
        })
    </script>
@endpush
