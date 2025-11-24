<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->role;

        $data = [];

        if ($role == 'admin') {
            $data['total_users'] = DB::table('users')->count();
            $data['total_staff'] = DB::table('users')->where('role', 'staff')->count();
            $data['total_supervisor'] = DB::table('users')->where('role', 'spv')->count();
            $data['total_manager'] = DB::table('users')->where('role', 'manager')->count();
            $data['total_pengajuan'] = DB::table('overtimes')->count();
            $data['total_approved'] = DB::table('overtimes')->whereNotNull('approved_by')->count();
            $data['total_pending'] = DB::table('overtimes')->whereNull('approved_by')->count();
            $data['total_jam_lembur'] = DB::table('overtimes')->sum('total_hours');
        } elseif ($role == 'manajer') {
            $data['total_pengajuan'] = DB::table('overtimes')->count();
            $data['total_approved'] = DB::table('overtimes')->whereNotNull('approved_by')->count();
            $data['total_pending'] = DB::table('overtimes')->whereNull('approved_by')->count();
            $data['total_jam_lembur'] = DB::table('overtimes')->sum('total_hours');
            $data['total_staff'] = DB::table('users')->where('role', 'staff')->count();
        } elseif ($role == 'spv') {
            $data['total_pengajuan_saya'] = DB::table('overtimes')
                ->where('pic', Auth::id())
                ->count();
            $data['total_approved_saya'] = DB::table('overtimes')
                ->where('pic', Auth::id())
                ->whereNotNull('approved_by')
                ->count();
            $data['total_pending_saya'] = DB::table('overtimes')
                ->where('pic', Auth::id())
                ->whereNull('approved_by')
                ->count();
            $data['total_jam_pengajuan'] = DB::table('overtimes')
                ->where('pic', Auth::id())
                ->sum('total_hours');
            $data['total_staff_saya'] = DB::table('overtimes')
                ->where('pic', Auth::id())
                ->distinct('user_id')
                ->count('user_id');
        } elseif ($role == 'staff') {
            $data['total_lembur_saya'] = DB::table('overtimes')
                ->where('user_id', Auth::id())
                ->count();
            $data['total_approved'] = DB::table('overtimes')
                ->where('user_id', Auth::id())
                ->whereNotNull('approved_by')
                ->count();
            $data['total_pending'] = DB::table('overtimes')
                ->where('user_id', Auth::id())
                ->whereNull('approved_by')
                ->count();
            $data['total_jam_lembur'] = DB::table('overtimes')
                ->where('user_id', Auth::id())
                ->sum('total_hours');
        }

        return view('dashboard.dashboard', compact('data', 'role'));
    }

    public function loginUser(Request $request)
    {
        // return response()->json($request->all());
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();

            return redirect('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Cek kembali data login anda!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil logout!');
    }
}
