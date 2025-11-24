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
                        DB::raw('SUM(CASE WHEN overtimes.approved_by IS NOT NULL THEN overtimes.total_hours ELSE 0 END) as total_lembur_approved'),
                        DB::raw('SUM(CASE WHEN overtimes.approved_by IS NULL THEN overtimes.total_hours ELSE 0 END) as total_lembur_pending'),
                        DB::raw('COUNT(overtimes.id) as jumlah_pengajuan'),
                        DB::raw('COUNT(CASE WHEN overtimes.approved_by IS NOT NULL THEN 1 END) as jumlah_approved'),
                        DB::raw('COUNT(CASE WHEN overtimes.approved_by IS NULL THEN 1 END) as jumlah_pending')
                    )
                    ->groupBy('users.id', 'users.name', 'users.email');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('overtimes.date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->whereNotNull('overtimes.approved_by')
                    ->where('overtimes.approved_by', '>', 0);
            } elseif ($request->status == 'pending') {
                $query->whereNull('overtimes.approved_by');
            }
        }

        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('total_lembur', 'desc')->get();

        return view('dashboard.admin.index', compact('data'));
    }
}
