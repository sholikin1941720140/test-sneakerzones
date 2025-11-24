@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detail Lembur: {{$user->name}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin-laporan">Laporan</a></li>
                            <li class="breadcrumb-item active">Detail Staff</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Lembur</span>
                                <span class="info-box-number">{{number_format($stats['total_lembur'], 2)}} Jam</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <div class="info-box-content">
                                <span class="info-box-text">Approved</span>
                                <span class="info-box-number">{{number_format($stats['total_approved'], 2)}} Jam</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">{{number_format($stats['total_pending'], 2)}} Jam</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-danger">
                            <div class="info-box-content">
                                <span class="info-box-text">Rejected</span>
                                <span class="info-box-number">{{number_format($stats['total_rejected'], 2)}} Jam</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Lembur</h3>
                        <div class="card-tools">
                            <a href="/admin" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Total</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>PIC</th>
                                    <th>Approved/Rejected By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overtimes as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{date('d M Y', strtotime($item->date))}}</td>
                                    <td>{{date('H:i', strtotime($item->start_time))}} - {{date('H:i', strtotime($item->end_time))}}</td>
                                    <td>{{number_format($item->total_hours, 2)}} Jam</td>
                                    <td>{{$item->reason}}</td>
                                    <td>
                                        @if($item->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                            @if($item->reason_reject)
                                            <br><button class="btn btn-link btn-sm p-0" data-toggle="modal" data-target="#modal{{$item->id}}">
                                                <i class="fas fa-info-circle"></i> Alasan
                                            </button>
                                            @endif
                                        @elseif($item->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{$item->pic_name ?? '-'}}</td>
                                    <td>{{$item->approved_by_name ?? '-'}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @foreach($overtimes as $item)
        @if($item->status == 'rejected' && $item->reason_reject)
            <div class="modal fade" id="modal{{$item->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white">Alasan Penolakan</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Tanggal:</strong> {{date('d M Y', strtotime($item->date))}}</p>
                            <p><strong>Alasan Penolakan:</strong></p>
                            <div class="alert alert-danger">{{$item->reason_reject}}</div>
                            <small class="text-muted">Ditolak oleh: {{$item->approved_by_name ?? '-'}}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection