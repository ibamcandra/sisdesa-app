<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;

class ApplicantDetailController extends Controller
{
    public function show(ApplicantProfile $applicant)
    {
        // Eager load all relations for the CV view
        $applicant->load([
            'user',
            'province',
            'city',
            'district',
            'educationLevel',
            'skills',
            'experiences',
            'educations',
            'certifications'
        ]);

        return view('admin.applicant-cv', compact('applicant'));
    }
}
