<?php 

namespace App\Scripts;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ImageMethods {
  public static function saveImage($image) {
    if(preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
      $image = substr($image, strpos($image, ',') + 1);
      $fileExtension = strtolower($matches[1]);
      $fileExtension = str_replace('.', '', $fileExtension);

      if(!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        throw new \Exception('Invalid Image Extension');
      }

      //Remove spaces in base64. spaces are not allowed in base64 format.
      $image = str_replace(' ', '+', $image);
      $image = base64_decode($image);

      if($image === false) {
        throw new \Exception('Invalid base64 format.');
      }

      $dirPath = 'images/';
      $file = Str::random() . '.' . $fileExtension;
      $absolutePath = public_path($dirPath);
      $relativePath = $dirPath . $file;

      if(!File::exists($absolutePath)) {
        File::makeDirectory($absolutePath, 0755, true);
      }

      //index.php is located in public directory. Thus,
      //$relativePath will correctly point to the images directory.
      //Although, we can use $absolutePath here.
      file_put_contents($relativePath, $image);

      return $relativePath;
    }
    else {
      throw new \Exception('Invalid Image Data');
    }
  }
}

?>