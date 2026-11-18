@extends('layouts.app')

@section('title', 'Tambah Enrollment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-1">Tambah Enrollment</h1>
                <p class="text-secondary">
                    Pilih participant dan course yang sudah ada. Status awal ditetapkan
                    <strong>registered</strong> oleh controller, bukan oleh form.
                </p>

                <form method="POST" action="{{ route('enrollments.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="participant_id" class="form-label">Participant</label>
                        <select name="participant_id" id="participant_id"
                                class="form-select @error('participant_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Participant --</option>
                            @foreach ($participants as $participant)
                                <option value="{{ $participant->id }}"
                                    @selected(old('participant_id') == $participant->id)>
                                    {{ $participant->name }} — {{ $participant->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('participant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select name="course_id" id="course_id"
                                class="form-select @error('course_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Course --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    @selected(old('course_id') == $course->id)>
                                    {{ $course->code }} — {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="availability" class="alert alert-secondary">
                        Pilih kursus untuk melihat kuota dan status.
                    </div>

                    <button class="btn btn-success" type="submit">Simpan Enrollment</button>
                    <a class="btn btn-outline-secondary" href="{{ route('enrollments.index') }}">Lihat Laporan</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
