<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    public function index()
    {
        $id = auth()->id();

        $data = DB::table('overtimes')
                    ->join('users', 'overtimes.user_id', '=', 'users.id')
                    ->leftJoin('users as pic_user', 'overtimes.pic', '=', 'pic_user.id')
                    ->leftJoin('users as approved_user', 'overtimes.approved_by', '=', 'approved_user.id')
                    ->select(
                        'overtimes.*',
                        'users.name',
                        'users.email',
                        'pic_user.name as pic_name',
                        'approved_user.name as approved_by_name'
                    )
                    ->where('overtimes.pic', $id)
                    ->orderBy('overtimes.created_at', 'desc')
                    ->get();

        return view('dashboard.spv.index', compact('data'));
    }

    public function create()
    {
        $data = DB::table('users')->where('role', 'staff')->get();

        return view('dashboard.spv.create', compact('data'));
    }

    public function store(Request $request)
    {
        $check = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($check->fails()) {
            return redirect()->back()->withErrors($check)->withInput();
        }

        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return redirect()->back()->withErrors(['end_time' => 'Waktu selesai harus lebih besar dari waktu mulai'])->withInput();
        }

        $totalHours = $startTime->diffInMinutes($endTime, true) / 60;

        $created_at = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

        DB::table('overtimes')->insert([
            'user_id' => $request->user_id,
            'pic' => auth()->user()->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_hours' => $totalHours,
            'reason' => $request->reason,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ]);

        return redirect('/spv-pengajuan')->with('success', 'Data Overtime berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $check = DB::table('overtimes')->where('id', $id)->first();
        if ($check->status == 'approved' || $check->status == 'rejected') {
            return redirect('/spv-pengajuan')->with('error', 'Tidak dapat mengedit pengajuan lembur yang sudah disetujui atau ditolak.');
        }

        $pic = auth()->user()->id;
        if ($check->pic != $pic) {
            return redirect('/spv-pengajuan')->with('error', 'Anda tidak memiliki izin untuk mengedit pengajuan lembur ini.');
        }

        $data = DB::table('overtimes')->where('id', $id)->first();
        $users = DB::table('users')->where('role', 'staff')->get();

        return view('dashboard.spv.edit', compact('data', 'users'));
    }

    public function update(Request $request, $id)
    {
        $check = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($check->fails()) {
            return redirect()->back()->withErrors($check)->withInput();
        }

        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return redirect()->back()->withErrors(['end_time' => 'Waktu selesai harus lebih besar dari waktu mulai'])->withInput();
        }

        $totalHours = $startTime->diffInMinutes($endTime, true) / 60;

        $updated_at = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

        DB::table('overtimes')->where('id', $id)->update([
            'user_id' => $request->user_id,
            'pic' => auth()->user()->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_hours' => $totalHours,
            'reason' => $request->reason,
            'updated_at' => $updated_at,
        ]);

        return redirect('/spv-pengajuan')->with('success', 'Data Overtime berhasil diupdate.');
    }

    public function delete($id)
    {
        $check = DB::table('overtimes')->where('id', $id)->first();
        if ($check->status == 'approved' || $check->status == 'rejected') {
            return redirect('/spv-pengajuan')->with('error', ' Tidak dapat menghapus pengajuan lembur yang sudah disetujui atau ditolak.');
        }

        DB::table('overtimes')->where('id', $id)->delete();

        return redirect('/spv-pengajuan')->with('success', 'Data Overtime berhasil dihapus.');
    }
}
