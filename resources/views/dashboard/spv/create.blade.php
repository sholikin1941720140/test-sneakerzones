@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{url('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{url('plugins/sweetalert2/sweetalert2.min.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Tambah Data Pengajuan Lembur</li>
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
                                <h3 class="card-title">Tambah Data Pengajuan Lembur</h3>
                            </div>
                            <form action="{{ url('/spv-pengajuan/store') }}" method="POST" id="overtimeForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="user_id" class="col-sm-2 col-form-label">Staff<span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2" name="user_id" required>
                                                <option value="">Pilih Staff</option>
                                                @foreach($data as $s)
                                                    <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                                        {{ $s->name }} - {{ $s->email }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label for="date" class="col-sm-2 col-form-label">Tanggal Lembur<span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="jam" class="col-sm-2 col-form-label">Jam Mulai s/d Selesai<span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <div class="d-flex">
                                                <input type="time" class="form-control mr-2" name="start_time" value="{{ old('start_time') }}" required>
                                                <span class="align-self-center mx-2">-</span>
                                                <input type="time" class="form-control ml-2" name="end_time" value="{{ old('end_time') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="reason" class="col-sm-2 col-form-label">Alasan Lembur<span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="reason" rows="4" required>{{ old('reason') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer" align="right">
                                    <a href="{{ url('/spv-pengajuan') }}" class="btn btn-warning">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                theme: 'bootstrap4',
                placeholder: 'Pilih Staff'
            });

            @if ($errors->any())
                let errorMessages = '<ul style="text-align: left;">';
                @foreach ($errors->all() as $error)
                    errorMessages += '<li>{{ $error }}</li>';
                @endforeach
                errorMessages += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errorMessages,
                    confirmButtonColor: '#d33'
                });
            @endif

            $('#overtimeForm').on('submit', function(e) {
                const startTime = $('input[name="start_time"]').val();
                const endTime = $('input[name="end_time"]').val();
                const date = $('input[name="date"]').val();

                if (startTime && endTime && date) {
                    const start = new Date(date + ' ' + startTime);
                    const end = new Date(date + ' ' + endTime);

                    if (end <= start) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Waktu selesai harus lebih besar dari waktu mulai!',
                            confirmButtonColor: '#d33'
                        });
                        return false;
                    }
                }
            });
        });
    </script>
@endsection