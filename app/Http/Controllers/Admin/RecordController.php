<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRecordRequest;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Models\Record;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class RecordController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $records = Record::with(['media'])->get();

        return view('admin.records.index', compact('records'));
    }

    public function create()
    {
        abort_if(Gate::denies('record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.records.create');
    }

    public function store(StoreRecordRequest $request)
    {
        $record = Record::create($request->all());

        if ($request->input('profile_picture', false)) {
            $record->addMedia(storage_path('tmp/uploads/' . basename($request->input('profile_picture'))))->toMediaCollection('profile_picture');
        }

        if ($request->input('cover_picture', false)) {
            $record->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_picture'))))->toMediaCollection('cover_picture');
        }

        foreach ($request->input('reference', []) as $file) {
            $record->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('reference');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $record->id]);
        }

        return redirect()->route('admin.records.index');
    }

    public function edit(Record $record)
    {
        abort_if(Gate::denies('record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.records.edit', compact('record'));
    }

    public function update(UpdateRecordRequest $request, Record $record)
    {
        $record->update($request->all());

        if ($request->input('profile_picture', false)) {
            if (! $record->profile_picture || $request->input('profile_picture') !== $record->profile_picture->file_name) {
                if ($record->profile_picture) {
                    $record->profile_picture->delete();
                }
                $record->addMedia(storage_path('tmp/uploads/' . basename($request->input('profile_picture'))))->toMediaCollection('profile_picture');
            }
        } elseif ($record->profile_picture) {
            $record->profile_picture->delete();
        }

        if ($request->input('cover_picture', false)) {
            if (! $record->cover_picture || $request->input('cover_picture') !== $record->cover_picture->file_name) {
                if ($record->cover_picture) {
                    $record->cover_picture->delete();
                }
                $record->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_picture'))))->toMediaCollection('cover_picture');
            }
        } elseif ($record->cover_picture) {
            $record->cover_picture->delete();
        }

        if (count($record->reference) > 0) {
            foreach ($record->reference as $media) {
                if (! in_array($media->file_name, $request->input('reference', []))) {
                    $media->delete();
                }
            }
        }
        $media = $record->reference->pluck('file_name')->toArray();
        foreach ($request->input('reference', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $record->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('reference');
            }
        }

        return redirect()->route('admin.records.index');
    }

    public function show(Record $record)
    {
        abort_if(Gate::denies('record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.records.show', compact('record'));
    }

    public function destroy(Record $record)
    {
        abort_if(Gate::denies('record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $record->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecordRequest $request)
    {
        $records = Record::find(request('ids'));

        foreach ($records as $record) {
            $record->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('record_create') && Gate::denies('record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Record();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
