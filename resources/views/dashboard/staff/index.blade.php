@extends('layouts.app')
@section('custom-css')
<link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Riwayat Lembur Saya</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Riwayat Lembur</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{number_format($stats['total_lembur'], 2)}}</h3>
                            <p>Total Jam Lembur</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{number_format($stats['total_approved'], 2)}}</h3>
                            <p>Jam Approved</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{number_format($stats['total_pending'], 2)}}</h3>
                            <p>Jam Pending</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{$stats['jumlah_pengajuan']}}</h3>
                            <p>Total Pengajuan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Selesai</label>
                                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="approved" {{request('status') == 'approved' ? 'selected' : ''}}>Approved</option>
                                                <option value="pending" {{request('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{url()->current()}}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Riwayat Lembur</h3>
                        </div>
                        <div class="card-body">
                            <table id="overtimeTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Total Jam</th>
                                        <th>PIC</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Approved By</th>
                                        <th>Tanggal Pengajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $key => $item)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                        <td>{{date('H:i', strtotime($item->start_time))}}</td>
                                        <td>{{date('H:i', strtotime($item->end_time))}}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{number_format($item->total_hours, 2)}} Jam
                                            </span>
                                        </td>
                                        <td>{{$item->pic_name ?? '-'}}</td>
                                        <td>{{$item->reason ?? '-'}}</td>
                                        <td>
                                            @if($item->approved_by)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Approved
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-hourglass-half"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{$item->approved_by_name ?? '-'}}</td>
                                        <td>{{date('d/m/Y H:i', strtotime($item->created_at))}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <i class="fas fa-info-circle"></i> Belum ada data lembur
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th>
                                            <span class="badge badge-primary">
                                                {{number_format($data->sum('total_hours'), 2)}} Jam
                                            </span>
                                        </th>
                                        <th colspan="5"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('custom-js')
<script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
$(function () {
    $('#overtimeTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[1, "desc"]],
        "pageLength": 10
    });
});
</script>
@endsection