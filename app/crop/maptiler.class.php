<?php

/*
 * 
 * Generator images for all zooms of 3D globe from one source file.
 * zoom 7 = 32768x32768
 * zoom 6 = 16384x16384
 * zoom 5 = 8192x8192
 * zoom 4 = 4096x4096
 * zoom 3 = 2048x2048
 * zoom 2 = 1024x1024
 * zoom 1 = 512x512
 * 
 * 
 * 
 *  */

class MapTiler {

    // max available zoom => max resolutions pow(2, 8 + $baseZoom);
    private $baseZoom = 3;
    // a PNG file
    private $sourcefile = "";
    //
    private $oneblocksize = 256;
    // output folder
    private $outputdir = "map2";

    public function setSource($source) {
        $this->sourcefile = $source;
    }

    public function setBaseZoom($zoom) {
        $this->baseZoom = $zoom;
    }
    
    public function setOutputDir($dir){
        $this->outputdir = $dir;
    }

    public function run() {
        $this->createDirs();
        $this->prepareSource();
        $this->createFiles();
    }

    /* create resized source images */
    private function prepareSource() {
        for ($i = $this->baseZoom; $i > 0; $i--) {
            $this->resize($this->getImageDimension($i), $this->getSource($i), $this->sourcefile);
        }
    }

    // return full path for every option
    private function getPath($zoom, $part, $file = "") {
        return $this->outputdir . "/" . $zoom . "/" . $part . "/" . $file;
    }

    // return name of source file for generation files from file by zoom
    private function getSource($zoom) {
        $s = $this->getImageDimension($zoom);
        return $this->outputdir . "/" . $zoom . "/" . $s . "x" . $s;
    }

    // return sizeX (same as sizeY for square) of sourcefile by zoom
    private function getImageDimension($zoom) {
        return pow(2, 8 + $zoom);
    }

    private function createDirs() {

        mkdir($this->outputdir, null);
        chmod($this->outputdir, 0777);

        for ($i = $this->baseZoom; $i > 0; $i--) {
            mkdir($this->outputdir . "/" . $i, null, true);
            chmod($this->outputdir . "/" . $i, 0777);
            for ($j = 0; $j < pow(2, $i); $j++) {
                $folder = $this->outputdir . "/" . $i . "/" . $j;
                mkdir($folder, null, true);
                chmod($folder, 0777);
            }
        }
    }

    private function createFiles() {
        for ($i = $this->baseZoom; $i > 0; $i--) {            
            $src = imagecreatefrompng($this->getSource($i) . ".png");
            $dest = imagecreatetruecolor($this->oneblocksize, $this->oneblocksize);
            
            for ($j = 0; $j < pow(2, $i); $j++) {
                for ($k = 0; $k < pow(2, $i); $k++) {                                        
                    imagecopy($dest, $src, 0, 0, $j * $this->oneblocksize, $k * $this->oneblocksize, $this->oneblocksize, $this->oneblocksize);
                    $fname = $this->getPath($i, $j, $k . ".png");
                    imagepng($dest, $fname);
                    chmod($fname, 0777);
                }
            }
        }

        imagedestroy($dest);
        imagedestroy($src);
    }

    private function resize($newWidth, $targetFile, $originalFile) {

        $info = getimagesize($originalFile);
        $mime = $info['mime'];

        switch ($mime) {
            /*
              case 'image/jpeg':
              $image_create_func = 'imagecreatefromjpeg';
              $image_save_func = 'imagejpeg';
              $new_image_ext = 'jpg';
              break;
              /* */

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            default:
                throw Exception('Unknown image type.');
        }
       
        
        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);

        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        
        $image_save_func($tmp, "$targetFile.$new_image_ext");
    }

}
