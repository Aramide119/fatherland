<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dynasty;
use Illuminate\Http\Request;

class LineageController extends Controller
{
    public function index()
    {
        $dynasties = Dynasty::with('user')->get();

        return view('admin.dynasties.index', compact('dynasties'));
    }

    public function show(Dynasty $showDynasty)
    {
        return view('admin.dynasties.show', compact('showDynasty'));
    }

    public function edit(Dynasty $editDynasty)
    {
        return view('admin.dynasties.edit', compact('editDynasty'));
    }

    public function update(Request $request, $id)
    {
        $editDynasty = Dynasty::findOrFail($id);
        $input = $request->validate([
            'status' => 'required',
        ]);

        // dd($editFamily);

        $editDynasty->update($input);

        return redirect()->route('admin.dynasties.index')
            ->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
    {
        $deleteDynasty = Dynasty::findOrFail($id);

        $deleteDynasty->destroy($id);

        return redirect()->route('admin.dynasties.index')
            ->with('success', 'Dynasty Deleted successfully.');
    }
}
