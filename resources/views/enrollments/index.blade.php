@extends('layouts.app')

@section('title', 'Laporan Enrollment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Laporan Enrollment KursusKu</h1>
        <p class="text-muted mb-0">
            Nama peserta dan tanggal mulai dibaca lewat relasi, bukan disalin ke tabel enrollments.
        </p>
    </div>
    <a href="{{ route('enrollments.create') }}" class="btn btn-success">Tambah Enrollment</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Course</th>
                        <th>Start Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->participant->name }}</td>
                            <td>{{ $enrollment->course->name }}</td>
                            <td>{{ $enrollment->course->start_date?->format('d/m/Y') }}</td>
                            <td>{{ $enrollment->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada enrollment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
