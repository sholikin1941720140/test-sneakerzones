<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

class ManajerController extends Controller
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
                    ->where(function($query) use ($id) {
                        $query->whereNull('overtimes.approved_by')
                            ->orWhere('overtimes.approved_by', $id);
                    })
                    ->orderBy('overtimes.created_at', 'desc')
                    ->get();

        return view('dashboard.manajer.index', compact('data'));
    }

    public function edit($id)
    {
        $data = DB::table('overtimes')
                    ->join('users', 'overtimes.user_id', '=', 'users.id')
                    ->leftJoin('users as pic_user', 'overtimes.pic', '=', 'pic_user.id')
                    ->select(
                        'overtimes.*',
                        'users.name as user_name',
                        'users.email as user_email',
                        'pic_user.name as pic_name'
                    )
                    ->where('overtimes.id', $id)
                    ->first();

        if (!$data) {
            return redirect()->route('manajer-overtime.index')->with('error', 'Data tidak ditemukan.');
        }

        if ($data->status != 'pending') {
            if (!$data->approved_by || $data->approved_by != auth()->user()->id) {
                $statusText = $data->status == 'approved' ? 'disetujui' : 'ditolak';
                return redirect()->route('manajer-overtime.index')->with('error', "Pengajuan lembur sudah {$statusText} dan hanya dapat diubah oleh manajer yang mereview sebelumnya.");
            }

            $now = Carbon::now('Asia/Jakarta');
            $overtimeDateTime = Carbon::parse($data->date . ' ' . $data->start_time, 'Asia/Jakarta');
            $deadline = $overtimeDateTime->copy()->subMinute();

            if ($now->greaterThanOrEqualTo($deadline)) {
                $deadlineFormatted = $deadline->format('d M Y H:i');
                return redirect()->route('manajer-overtime.index')->with('error', "Batas waktu perubahan sudah terlewat (deadline: {$deadlineFormatted} WIB).");
            }
        }

        return view('dashboard.manajer.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $check = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'reason_reject' => 'required_if:status,rejected|nullable|string',
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'reason_reject.required_if' => 'Alasan penolakan harus diisi jika status ditolak',
        ]);

        if ($check->fails()) {
            return redirect()->back()->withErrors($check)->withInput()->with('error', 'Gagal mengupdate data. Periksa kembali form Anda.');
        }

        try {
            $updated_at = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $updateData = [
                'status' => $request->status,
                'approved_by' => auth()->user()->id,
                'approved_at' => $updated_at,
                'updated_at' => $updated_at,
            ];

            if ($request->status == 'rejected') {
                $updateData['reason_reject'] = $request->reason_reject;
            } else {
                $updateData['reason_reject'] = null;
            }

            DB::table('overtimes')
                ->where('id', $id)
                ->update($updateData);

            $statusText = $request->status == 'approved' ? 'disetujui' : 'ditolak';

            return redirect('/manajer-pengajuan')->with('success', "Pengajuan lembur berhasil {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}