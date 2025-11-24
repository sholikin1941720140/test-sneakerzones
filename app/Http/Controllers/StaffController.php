<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::id();

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

        $data = $query->orderBy('overtimes.date', 'desc')
                    ->orderBy('overtimes.created_at', 'desc')
                    ->get();

        $stats = [
            'total_lembur' => $data->sum('total_hours'),
            'total_approved' => $data->where('status', 'approved')->sum('total_hours'),
            'total_pending' => $data->where('status', 'pending')->sum('total_hours'),
            'total_rejected' => $data->where('status', 'rejected')->sum('total_hours'),
            'jumlah_pengajuan' => $data->count(),
            'jumlah_approved' => $data->where('status', 'approved')->count(),
            'jumlah_pending' => $data->where('status', 'pending')->count(),
            'jumlah_rejected' => $data->where('status', 'rejected')->count(),
        ];

        return view('dashboard.staff.index', compact('data', 'stats'));
    }
}