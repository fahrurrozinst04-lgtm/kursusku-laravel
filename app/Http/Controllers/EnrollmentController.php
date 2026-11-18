<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['participant', 'course'])
            ->latest()
            ->get();

        return view('enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $participants = Participant::orderBy('name')->get();

        // Di project KursusKu, status course adalah draft|open|closed.
        // Panduan menulis "active". Yang setara agar kursus tampil di form adalah "open".
        $courses = Course::where('status', 'open')
            ->orderBy('name')
            ->get();

        return view('enrollments.create', compact('participants', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'participant_id' => ['required', 'exists:participants,id'],
                'course_id' => [
                    'required',
                    'exists:courses,id',
                    Rule::unique('enrollments')->where(
                        fn ($query) => $query->where(
                            'participant_id',
                            $request->participant_id
                        )
                    ),
                ],
            ],
            [
                'participant_id.required' => 'Participant wajib dipilih.',
                'participant_id.exists' => 'Participant tidak ditemukan.',
                'course_id.required' => 'Course wajib dipilih.',
                'course_id.exists' => 'Course tidak ditemukan.',
                'course_id.unique' => 'Participant ini sudah terdaftar pada course tersebut.',
            ]
        );

        $data['status'] = 'registered';

        Enrollment::create($data);

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'Enrollment berhasil disimpan.');
    }
}
