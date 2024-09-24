<?php
   namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ImageUpload
{
    Public function uploadPostImage($photo)
    {
        $paths = [];

        foreach ($photo as $image) {
           
           array_push($paths, $this->uploadImage($image));
           // Add the image path to the array
         }

         return $paths;
    }


    // public function uploadPostImage($photos)
    // {
    //     $paths = [];

    //     foreach ($photos as $photo) {
    //         $paths[] = $this->uploadImage($photo);
    //     }

    //     return $paths;
    // }

    public function uploadImage($image)
    {
        $fileNameWithExt = $image->getClientOriginalName();

        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

        $extension = $image->getClientOriginalExtension();

        $fileNameToStore = rand().'_'.time().'_'.$extension;

        $path = $image->move(public_path('images/'), $fileNameToStore);

        $postImage = "/images/".$fileNameToStore;

        return $postImage;
    }

    public function imageResponse($image)
    {
        $result = [];

        $datas = json_decode($image,true);
        

        if($datas == NULL){
            return NULL;
        }

        foreach( $datas as $data){
            $result[] = $data;
        }

        return $result;
    }


    // public function imageResponse($images)
    // {
    //     $result = [];

    //     $decodedImages = json_decode($images, true);

    //     if (is_array($decodedImages)) {
    //         foreach ($decodedImages as $imagePath) {
    //             // Use the 'url' method to generate the full URL of the image
    //             $url = Storage::url($imagePath);
    //             $result[] = $url;
    //         }
    //     }

    //     return $result;
    // }



    // public function imageResponse($image)
    // {
    //     if (is_string($image)) {
    //         // If $image is a JSON string, decode it to an array
    //         $datas = json_decode($image, true);
    //     } elseif (is_array($image)) {
    //         // If $image is already an array, use it directly
    //         $datas = $image;
    //     } else {
    //         // If $image is neither a JSON string nor an array, return NULL
    //         return null;
    //     }

    //     // Convert the array of images to the desired format if needed
    //     $result = [];

    //     foreach ($datas as $data) {
    //         $result[] = $data;
    //     }

    //     return $result;
    // }

}



    
?>