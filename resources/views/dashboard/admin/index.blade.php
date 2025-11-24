@extends('layouts.app')
@section('custom-css')
<link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Total Lembur Staff</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Total Lembur Staff</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cari Nama Staff</label>
                                            <input type="text" name="search" class="form-control" placeholder="Nama staff..." value="{{request('search')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tanggal Selesai</label>
                                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="approved" {{request('status') == 'approved' ? 'selected' : ''}}>Approved</option>
                                                <option value="pending" {{request('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{url()->current()}}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-redo"></i> Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Total Lembur Per Staff</h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Staff</th>
                                        <th>Email</th>
                                        <th>Total Lembur</th>
                                        <th>Approved</th>
                                        <th>Pending</th>
                                        <th>Jumlah Pengajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $key => $item)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{number_format($item->total_lembur, 2)}} Jam
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{number_format($item->total_lembur_approved, 2)}} Jam
                                                <small>({{$item->jumlah_approved}} kali)</small>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">
                                                {{number_format($item->total_lembur_pending, 2)}} Jam
                                                <small>({{$item->jumlah_pending}} kali)</small>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{$item->jumlah_pengajuan}} kali
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total Keseluruhan:</th>
                                        <th>
                                            <span class="badge badge-primary">
                                                {{number_format($data->sum('total_lembur'), 2)}} Jam
                                            </span>
                                        </th>
                                        <th>
                                            <span class="badge badge-success">
                                                {{number_format($data->sum('total_lembur_approved'), 2)}} Jam
                                            </span>
                                        </th>
                                        <th>
                                            <span class="badge badge-warning">
                                                {{number_format($data->sum('total_lembur_pending'), 2)}} Jam
                                            </span>
                                        </th>
                                        <th>
                                            {{$data->sum('jumlah_pengajuan')}} kali
                                        </th>
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
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[5, "desc"]]
    });
});
</script>
@endsection