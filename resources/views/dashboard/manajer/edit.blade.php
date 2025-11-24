@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{url('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{url('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <style>
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
        .readonly-field {
            background-color: #f4f6f9;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/manajer-pengajuan') }}">Daftar Pengajuan Lembur</a></li>
                            <li class="breadcrumb-item active">Review Pengajuan Lembur</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Review Pengajuan Lembur</h3>
                            </div>

                            <form action="{{ url('/manajer-pengajuan/update/'.$data->id) }}" method="POST" id="approvalForm">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card bg-light mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-info-circle"></i> Informasi Pengajuan Lembur
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="info-item">
                                                                <i class="fas fa-user text-primary"></i>
                                                                <strong>Diajukan Untuk:</strong><br>
                                                                <span class="text-muted">{{ $data->user_name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="info-item">
                                                                <i class="fas fa-user-tie text-success"></i>
                                                                <strong>PIC (Supervisor):</strong><br>
                                                                <span class="text-muted">{{ $data->pic_name ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="info-item">
                                                                <i class="fas fa-clock text-info"></i>
                                                                <strong>Tanggal Pengajuan:</strong><br>
                                                                <span class="text-muted">{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Staff</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control readonly-field" value="{{ $data->user_name }} - {{ $data->user_email }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tanggal Lembur</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control readonly-field" value="{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Jam Mulai s/d Selesai</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control readonly-field" 
                                                value="{{ \Carbon\Carbon::parse($data->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($data->end_time)->format('H:i') }}" 
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Alasan Lembur</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control readonly-field" rows="4" readonly>{{ $data->reason }}</textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group row">
                                        <label for="status" class="col-sm-2 col-form-label">Status Approval <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="approved" {{ old('status', $data->status) == 'approved' ? 'selected' : '' }}>
                                                    Setujui
                                                </option>
                                                <option value="rejected" {{ old('status', $data->status) == 'rejected' ? 'selected' : '' }}>
                                                    Tolak
                                                </option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row" id="reason-reject-group" style="display: none;">
                                        <label for="reason_reject" class="col-sm-2 col-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control @error('reason_reject') is-invalid @enderror" 
                                                name="reason_reject" 
                                                id="reason_reject" 
                                                rows="4" 
                                                placeholder="Masukkan alasan penolakan...">{{ old('reason_reject', $data->reason_reject) }}</textarea>
                                            @error('reason_reject')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="text-muted">Alasan penolakan akan dikirimkan ke staff yang mengajukan.</small>
                                        </div>
                                    </div>

                                    @if($data->status != 'pending')
                                    <div class="alert alert-info mt-3">
                                        <h5><i class="fas fa-info-circle"></i> Status Saat Ini</h5>
                                        <p class="mb-1">
                                            <strong>Status:</strong> 
                                            @if($data->status == 'approved')
                                                <span class="badge badge-success">Disetujui</span>
                                            @elseif($data->status == 'rejected')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </p>
                                        @if($data->reason_reject && $data->status == 'rejected')
                                            <p class="mb-0"><strong>Alasan:</strong> {{ $data->reason_reject }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <div class="card-footer" align="right">
                                    <a href="{{ url('/manajer-pengajuan') }}" class="btn btn-warning">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Simpan Keputusan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('custom-js')
    <script src="{{url('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{url('plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            function toggleReasonReject() {
                const status = $('#status').val();
                const reasonGroup = $('#reason-reject-group');
                const reasonField = $('#reason_reject');

                if (status === 'rejected') {
                    reasonGroup.slideDown();
                    reasonField.attr('required', true);
                } else {
                    reasonGroup.slideUp();
                    reasonField.attr('required', false);
                    reasonField.val('');
                }
            }

            toggleReasonReject();

            $('#status').on('change', function() {
                toggleReasonReject();
            });

            $('#approvalForm').on('submit', function(e) {
                e.preventDefault();
                
                const status = $('#status').val();
                const reason = $('#reason_reject').val();

                if (!status) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih status approval terlebih dahulu!',
                        confirmButtonColor: '#007bff'
                    });
                    return false;
                }

                if (status === 'rejected' && !reason.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Alasan Penolakan Diperlukan',
                        text: 'Silakan isi alasan penolakan terlebih dahulu!',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        $('#reason_reject').focus();
                    });
                    return false;
                }

                const statusText = status === 'approved' ? 'menyetujui' : 'menolak';
                const statusIcon = status === 'approved' ? 'success' : 'warning';
                const statusColor = status === 'approved' ? '#28a745' : '#dc3545';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${statusText} pengajuan lembur ini?`,
                    icon: statusIcon,
                    showCancelButton: true,
                    confirmButtonColor: statusColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Ya, ${statusText.charAt(0).toUpperCase() + statusText.slice(1)}!`,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                        e.target.submit();
                    }
                });
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endsection