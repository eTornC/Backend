<?php

require dirname(__FILE__) . '/../constants/ConstantsPaths.php';

class ImageAlmacenator {

    // File for images
    private $target_dir;

    // Image file name
    private $image_name;

    // Complete path
    private $target_path;
    private $target_path_mobile;

    // Image File
    private $image_file;
    private $image_file_without_extension; // Without Extension

    // Image size
    private $image_size;

    // Extension file (png, jpg, jpeg o gif)
    private $imageFileType;
    private $imageFileTypeExtension; // only extension

    // Resolution
    private $width;
    private $heigth;


    public function __construct ($photoFileName, $photoFile, $photoSize, $photoFileType, $targetDir) {

        $this->target_dir = $targetDir;
        $this->image_name = $photoFileName;
        $this->target_path = $this->target_dir . basename($photoFileName);
        $this->image_size = $photoSize;
        $this->image_file = $photoFile;
        $this->imageFileType = $photoFileType;

        if ($this->checkFormat()){
            $size = getimagesize($photoFile);
            $this->width = $size[0];
            $this->heigth = $size[1];
        }
    }

    public function checkFormat(){

        $allowed = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

        if (in_array($this->imageFileType, $allowed)){

            if ($this->imageFileType == 'image/jpeg'){
                $this->imageFileTypeExtension = 'jpeg';
            } else if ($this->imageFileType == 'image/jpg'){
                $this->imageFileTypeExtension = 'jpg';
            } else if ($this->imageFileType == 'image/png'){
                $this->imageFileTypeExtension = 'png';
            } else if ($this->imageFileType == 'image/gif'){
                $this->imageFileTypeExtension = 'gif';
            }

            return true;
        } else {
            return false;
        }
    }

    public function checkSize(){
        if ($this->image_size > 4000000){
            return false;
        } else {
            return true;
        }
    }

    public function checkGeneral(){
        if ($this->checkSize() && $this->checkFormat()){
            return true;
        } else {
            return false;
        }
    }

    public function uploadPhoto(){
        return move_uploaded_file($this->image_file, getcwd() . "/../" .$this->target_path);
    }

    public function copyPhotoWithEspecificDimensions($newWidth, $newHeigth, $compresionLevel){

        $truecolor = imagecreatetruecolor($newWidth, $newHeigth);

        $nameFileM = $this->target_dir . $this->image_file_without_extension . "m." . $this->imageFileTypeExtension;
        $this->target_path_mobile = $nameFileM;

        if ($this->imageFileTypeExtension == "png"){
            imagecopyresampled($truecolor, imagecreatefrompng($this->target_path), 0, 0, 0, 0,
                                $newWidth, $newHeigth, $this->width, $this->heigth);

            imagepng($truecolor, $nameFileM, $compresionLevel);

            return true;

        } else if ($this->imageFileTypeExtension == "jpg" || $this->imageFileTypeExtension == "jpeg"){
            imagecopyresampled($truecolor, imagecreatefromjpeg($this->target_path), 0, 0, 0, 0,
                                $newWidth, $newHeigth, $this->width, $this->heigth);

            imagejpeg($truecolor, $nameFileM, $compresionLevel);

            return true;

        } else if ($this->imageFileTypeExtension == "gif") {
            imagecopyresampled($truecolor, imagecreatefromgif($this->target_path), 0, 0, 0, 0,
                                $newWidth, $newHeigth, $this->width, $this->heigth);

            imagegif($truecolor, $nameFileM, $compresionLevel);

            return true;
        } else {
            return false;
        }
    }

    // Setters

    public function setImageName($imageName){
        $this->image_name = $imageName . $this->imageFileTypeExtension;
        $this->image_file_without_extension = $imageName;
        $this->target_path = $this->target_dir . $imageName . "." . $this->imageFileTypeExtension;
    }

    // Getters:

    public function getTargetDir() {
        return $this->target_dir;
    }

    public function getImageFile() {
        return $this->image_file;
    }

    public function getTargetPath() {
        return $this->target_path;
    }

    public function getTargetPathMobile() {
        return $this->target_path_mobile;
    }

    public function getImageSize() {
        return $this->image_size;
    }

    public function getWidth(){
        return $this->width;
    }

    public function getHeigth(){
        return $this->heigth;
    }

}

?>
