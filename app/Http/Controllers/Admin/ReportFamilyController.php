<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportFamily;
use Illuminate\Http\Request;

class ReportFamilyController extends Controller
{
    //
    public function index()
    {
        $families = ReportFamily::with('family')->get();
        // dd($posts);

        return view('admin.reportedFamilies.index', compact('families'));
    }

    public function show(ReportFamily $showFamily)
    {
        // dd($showPost);
        return view('admin.reportedFamilies.show', compact('showFamily'));
    }
}
