<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\QrLogin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function qrLogin(Request $request)
    {
        if ($request->ajax()) {
            $qr_login = Str::uuid()->toString();
            $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)
                ->margin(2)
                ->backgroundColor(248, 250, 252)
                ->gradient(255, 0, 0, 0, 0, 255, 'diagonal')
                ->generate($qr_login)
                ->toHtml();

            return response()->json([
                'status' => true,
                'data' => [
                    'qr' => 'data:image/svg+xml;base64,' . base64_encode($qr),
                    'qr_login' => $qr_login
                ]
            ])->withCookie('qr_login', $qr_login);
        }
    }

    public function qrLoginCheck(Request $request) {
        $user = User::where('email', decrypt($request->user))->first();
        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => 'Login success'
        ]);
    }

    public function qrLoiginShow(Request $request)
    {
        return view('qr-login');
    }

    public function qrLoiginVerify(Request $request) {
        \App\Events\QrLogin::dispatch([ 'code' => $request->code, 'user' => encrypt($request->user()->email)]);
        if(request()->ajax()){
            return response()->json([
                'status' => true
            ]);
        } 
        return redirect()->back();
    }
}
