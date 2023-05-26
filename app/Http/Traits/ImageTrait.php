<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
trait ImageTrait{

    public function AddImageInPublic($FolderName = "Images", $SupFolderName = "Img" , $img)
    {
        $imagePath = time() . rand() . $SupFolderName . '.'. $img->extension();
        $img->move(public_path( $FolderName.'/'.$SupFolderName), $imagePath);
        return $FolderName.'/'.$SupFolderName.'/'.$imagePath;
    }

    public function UpdateImageInPublic($FolderName = "Images", $SupFolderName = "Img" , $img , $oldimg)
    {
        unlink(public_path($oldimg));
        $imagePath = time() . rand() . $SupFolderName .  '.'.  $img->extension();
        $img->move(public_path( $FolderName.'/'.$SupFolderName), $imagePath);
        return $FolderName.'/'.$SupFolderName.'/'.$imagePath;
    }

    public  function AddImageInStorge($FolderName = "Images", $SupFolderName = "Img" , $img)
    {
     $imagePath = time() . rand() . $SupFolderName . '.'. $img->extension();
     $path = Storage::putFileAs('images', $img, $FolderName.'/'.$SupFolderName.'/'.$imagePath);
     return Storage::url($path);
    }
   
    public  function UpdateImageInStorge($FolderName = "Images", $SupFolderName = "Img" , $img , $oldimg)
    {
        unlink(storage_path($oldimg));
        $imagePath = time() . rand() . $SupFolderName . '.'. $img->extension();
        $path = Storage::putFileAs('images', $img, $FolderName.'/'.$SupFolderName.'/'.$imagePath);
        return Storage::url($path);
    }
}
