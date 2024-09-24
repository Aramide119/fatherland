<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LearningCategoryController extends Controller
{
    //
    public function index()
    {
        
        $learningCategories = LearningCategory::all();

        return view('admin.learningCategories.index', compact('learningCategories'));
    }

    public function create()
    {

        return view('admin.learningCategories.create');
    }

    public function store(Request $request)
    {
        $learningCategory = LearningCategory::create($request->all());

        return redirect()->route('admin.learning-categories.index');
    }

    public function edit(LearningCategory $learningCategory)
    {

        return view('admin.learningCategories.edit', compact('learningCategory'));
    }

    public function update(Request $request, LearningCategory $learningCategory)
    {
        $learningCategory->update($request->all());

        return redirect()->route('admin.learning-categories.index');
    }

    public function show(LearningCategory $learningCategory)
    {
        return view('admin.learningCategories.show', compact('learningCategory'));
    }

    public function destroy(LearningCategory $learningCategory)
    {

        $learningCategory->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $learningCategories = LearningCategory::find(request('ids'));

        foreach ($learningCategories as $learningCategory) {
            $learningCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
