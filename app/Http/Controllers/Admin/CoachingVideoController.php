<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\CoachingVideo;
use App\Models\LearningCategory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CoachingVideoController extends Controller
{
    //
    public function index()
    {
        
        $videos = CoachingVideo::with('media', 'coach', 'learning_category')->get();
        return view('admin.coachingVideos.index', compact('videos'));
    }

    public function coach(Request $request)
    {
        $categoryId = $request->input('category');
        $coaches = Coach::where('learning_category_id', $categoryId)->get();
        $categories = LearningCategory::all();

        session(['selected_category' => $categoryId]);

        return view('admin.coachingVideos.create', compact('coaches', 'categories'));
    }

    public function create()
    {
        $categories = LearningCategory::all();

        $selectedCategory = session('selected_category');
        $coaches = null;

        if ($selectedCategory) {
            $coaches = Coach::where('learning_category_id', $selectedCategory)->get();
        }
        return view('admin.coachingVideos.create', compact('categories', 'coaches'));
    }

    public function store(Request $request)
    {
        $categoryId = $request->input('category');
        $coachId = $request->input('coach');

        if ($request->hasFile('video')) {
                $video = new CoachingVideo();
                $video->learning_category_id = $categoryId;
                $video->coach_id = $coachId;
                $video->save();

                $data = $request->file('video');
                $coachingVideo = $this->manualStoreMedia($data)['name'];
                $video->addMedia(storage_path('tmp/uploads/'.basename($coachingVideo)))->toMediaCollection('video');
    
        }
            return redirect()->route('admin.coaching-videos.index');
    }

    public function edit($id)
    {
        
        
        $video = CoachingVideo::with('media', 'coach', 'learning_category')->where('id', $id)->first();

        return view('admin.coachingVideos.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $video = CoachingVideo::with('media', 'coach', 'learning_category')->where('id',$request->input('coach'))->first();

        if ($request->hasFile('video')) {

            $data = $request->file('video');       

            if ($request->hasFile('video')) {
                    if ($video->hasMedia('video')) {
                        $video->clearMediaCollection('video');
                    }
                    $coachingVideo = $this->manualStoreMedia($data)['name'];
                    $video->addMedia(storage_path('tmp/uploads/' . $coachingVideo))->toMediaCollection('video');
            }

            return redirect()->route('admin.coaching-videos.index');
        }
    }

    public function show($id)
    {
        $coachingVideo = CoachingVideo::where('id', $id)->first();

        return view('admin.coachingVideos.show', compact('coachingVideo'));
    }


    public function destroy($id)
    {

       
        $video = CoachingVideo::with('media', 'coach', 'learning_category')->findOrFail($id);

        $video->clearMediaCollection('video');
        $video->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        
        $videos = CoachingVideo::with('media', 'coach', 'learning_category')->find(request('ids'));

        foreach ($videos as $video) {
            $video->clearMediaCollection('video');
            $video->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function manualStoreMedia($file)
    {

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        if(is_array($file)){
            $files = $file;
            $response = [];
            foreach($files as $key => $file){
                $name = uniqid() . '_' . trim($file->getClientOriginalName());
                $file->move($path, $name);
                $response[$key] = ['name' => $name, 'original_name' => $file->getClientOriginalName()];
            }
            return $response;
        } else{
            $name = uniqid() . '_' . trim($file->getClientOriginalName());

            $file->move($path, $name);

            return array(
                'name'=> $name,
                'original_name' => $file->getClientOriginalName()
            );
        }
    }

    public function storeCKEditorVideos(Request $request)
    {
        
        $model         = new Coach();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
