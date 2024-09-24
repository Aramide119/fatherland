<?php 
namespace App\Traits;
use Illuminate\Http\Request;


trait VideoUpload
{

    
    public function getVideosAttribute()
    {
        $files = $this->getMedia('videos');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
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



    public function storeMedia(Request $request)
    {
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:' . request()->input('size') * 1024,
            ]);
        }
        // If width or height is preset - we are validating it as an image
        if (request()->has('width') || request()->has('height')) {
            $this->validate(request(), [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('width', 100000),
                    request()->input('height', 100000)
                ),
            ]);
        }

        $path = storage_path('tmp/uploads');

        try {
            if (! file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function uploadVideo($video)
    {
        $fileNameWithExt = $video->getClientOriginalName();

        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

        $extension = $video->getClientOriginalExtension();

        $fileNameToStore = rand().'_'.time().'_'.$extension;

        $path = $video->move(public_path('videos/'), $fileNameToStore);

        $postVideo = "/videos/".$fileNameToStore;

        return $postVideo;
    }


    Public function uploadPostVideo($medias)
    {
        $paths = [];

        foreach ($medias as $media) {
           
           array_push($paths, $this->uploadVideo($media));
           // Add the image path to the array
         }

         return $paths;
    }
    public function videoResponse($media)
    {
        $result = [];

        $datas = json_decode($media,true);

        

        if($datas == NULL){
            return NULL;
        }

        foreach( $datas as $data){
            $result[] = $data;
        }

        
        return $result;
    }
}

?>