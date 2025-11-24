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
                        <h1>Daftar Pengajuan Lembur</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Daftar Pengajuan Lembur</li>
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
                                <h3 class="card-title">Daftar Data Pengajuan Lembur</h3>
                            </div>
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Staff</th>
                                            <th>Email</th>
                                            <th>Detail</th>
                                            <th>PIC</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $key => $item)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->email}}</td>
                                            <td>
                                                <b>Tanggal: </b> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                                <br>
                                                <b>Jam: </b> {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                                <br>
                                                <b>Total: </b> {{number_format($item->total_hours, 2)}} Jam
                                                <br>
                                                <b>Alasan: </b> {{$item->reason}}
                                            </td>
                                            <td>
                                                @if($item->pic_name)
                                                    {{$item->pic_name}}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status == 'rejected')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle"></i> Rejected
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Ditolak oleh: <strong>{{$item->approved_by_name ?? 'Saya'}}</strong>
                                                    </small>
                                                    @if($item->reason_reject)
                                                    <br>
                                                    <button type="button" class="btn btn-link btn-sm p-0 text-danger" 
                                                            data-toggle="modal" data-target="#rejectModal{{$item->id}}">
                                                        <i class="fas fa-info-circle"></i> Lihat Alasan
                                                    </button>
                                                    @endif
                                                @elseif($item->status == 'approved')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Approved
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Oleh: <strong>{{$item->approved_by_name ?? 'Saya'}}</strong>
                                                    </small>
                                                    @if($item->approved_at)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($item->approved_at)->format('d M Y H:i') }}
                                                    </small>
                                                    @endif
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-hourglass-half"></i> Pending
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">Menunggu approval</small>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{url('/manajer-pengajuan/edit/'.$item->id)}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @foreach($data as $item)
        @if($item->status == 'rejected' && $item->reason_reject)
        <div class="modal fade" id="rejectModal{{$item->id}}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-times-circle"></i> Alasan Penolakan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <h6><strong>Pengajuan Lembur Ditolak</strong></h6>
                            <hr>
                            <p class="mb-1"><strong>Staff:</strong> {{$item->name}}</p>
                            <p class="mb-1"><strong>Email:</strong> {{$item->email}}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Jam:</strong> {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</p>
                            <p class="mb-1"><strong>Total:</strong> {{number_format($item->total_hours, 2)}} Jam</p>
                        </div>

                        <h6 class="text-danger"><strong>Alasan Penolakan:</strong></h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                <p class="mb-0">{{$item->reason_reject}}</p>
                            </div>
                        </div>

                        <small class="text-muted">
                            <i class="fas fa-user"></i> Ditolak oleh: <strong>{{$item->approved_by_name ?? 'Saya'}}</strong>
                            @if($item->updated_at)
                            <br>
                            <i class="fas fa-calendar"></i> Pada: {{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y H:i') }}
                            @endif
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
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
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[6, "desc"]]
            });
        });
    </script>
@endsection