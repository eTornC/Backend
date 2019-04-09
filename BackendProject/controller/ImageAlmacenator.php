<?php

namespace eTorn\Controller;

use eTorn\Constants\ConstantsPaths;

class ImageAlmacenator {

    public function __construct() {}

    public static function getInstance() {
        return new self();
    }

    public function saveImage($imageFile) {

        $imageExtension = $this->checkFormatAndGetExtension($imageFile['type']);
        $newFileName = $this->generateRandomName($imageExtension);

        $path = ConstantsPaths::PATH_IMAGES . $newFileName;

        if ($this->uploadPhoto($imageFile['tmp_name'], getcwd() . $path)) {
            return $path;
        }

        throw new \Exception('Problem to upload message');
    }

    private function generateRandomName($extension, $len = 10) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle($word);
        return substr(implode($word), 0, $len) . $extension;
    }

    private function checkFormatAndGetExtension($type){

        $allowed = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

        if (in_array($type, $allowed)){
            return '.' . substr($type, 6);
        }
        
        throw new \Exception('Bad image extension');
    }

    private function checkSize($size){
        return ($size > 4000000); // 4MB
    }

    public function uploadPhoto($file, $path) {
        return move_uploaded_file($file, $path);
    }

}

?>
