<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('overtimes')
                    ->join('users', 'overtimes.user_id', '=', 'users.id')
                    ->leftJoin('users as approved_user', 'overtimes.approved_by', '=', 'approved_user.id')
                    ->select(
                        'users.id as user_id',
                        'users.name',
                        'users.email',
                        DB::raw('SUM(overtimes.total_hours) as total_lembur'),
                        DB::raw("SUM(CASE WHEN overtimes.status = 'approved' THEN overtimes.total_hours ELSE 0 END) as total_lembur_approved"),
                        DB::raw("SUM(CASE WHEN overtimes.status = 'pending' THEN overtimes.total_hours ELSE 0 END) as total_lembur_pending"),
                        DB::raw("SUM(CASE WHEN overtimes.status = 'rejected' THEN overtimes.total_hours ELSE 0 END) as total_lembur_rejected"),
                        DB::raw('COUNT(overtimes.id) as jumlah_pengajuan'),
                        DB::raw("COUNT(CASE WHEN overtimes.status = 'approved' THEN 1 END) as jumlah_approved"),
                        DB::raw("COUNT(CASE WHEN overtimes.status = 'pending' THEN 1 END) as jumlah_pending"),
                        DB::raw("COUNT(CASE WHEN overtimes.status = 'rejected' THEN 1 END) as jumlah_rejected")
                    )
                    ->groupBy('users.id', 'users.name', 'users.email');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('overtimes.date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('overtimes.status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('total_lembur', 'desc')->get();

        return view('dashboard.admin.index', compact('data'));
    }

    public function detail(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $query = DB::table('overtimes')
                    ->leftJoin('users as pic_user', 'overtimes.pic', '=', 'pic_user.id')
                    ->leftJoin('users as approved_user', 'overtimes.approved_by', '=', 'approved_user.id')
                    ->select(
                        'overtimes.*',
                        'pic_user.name as pic_name',
                        'approved_user.name as approved_by_name'
                    )
                    ->where('overtimes.user_id', $id);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('overtimes.date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('overtimes.status', $request->status);
        }

        $overtimes = $query->orderBy('overtimes.date', 'desc')->get();

        $stats = [
            'total_lembur' => $overtimes->sum('total_hours'),
            'total_approved' => $overtimes->where('status', 'approved')->sum('total_hours'),
            'total_pending' => $overtimes->where('status', 'pending')->sum('total_hours'),
            'total_rejected' => $overtimes->where('status', 'rejected')->sum('total_hours'),
        ];

        return view('dashboard.admin.detail', compact('user', 'overtimes', 'stats'));
    }
}
