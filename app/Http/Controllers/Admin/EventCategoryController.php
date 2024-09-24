<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventCategoryRequest;
use App\Http\Requests\StoreEventCategoryRequest;
use App\Http\Requests\UpdateEventCategoryRequest;
use App\Models\EventCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('event_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventCategories = EventCategory::all();

        return view('admin.eventCategories.index', compact('eventCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('event_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.eventCategories.create');
    }

    public function store(StoreEventCategoryRequest $request)
    {
        $eventCategory = EventCategory::create($request->all());

        return redirect()->route('admin.event-categories.index');
    }

    public function edit(EventCategory $eventCategory)
    {
        abort_if(Gate::denies('event_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.eventCategories.edit', compact('eventCategory'));
    }

    public function update(UpdateEventCategoryRequest $request, EventCategory $eventCategory)
    {
        $eventCategory->update($request->all());

        return redirect()->route('admin.event-categories.index');
    }

    public function show(EventCategory $eventCategory)
    {
        abort_if(Gate::denies('event_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.eventCategories.show', compact('eventCategory'));
    }

    public function destroy(EventCategory $eventCategory)
    {
        abort_if(Gate::denies('event_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventCategoryRequest $request)
    {
        $eventCategories = EventCategory::find(request('ids'));

        foreach ($eventCategories as $eventCategory) {
            $eventCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
