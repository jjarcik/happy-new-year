<?php

class MapMerge {

    // max available zoom => max resolutions pow(2, 8 + $baseZoom);
    private $baseZoom = 1;
    // a PNG file
    private $inputdir = "../maps/map2/";
    //
    private $oneblocksize = 256;
    // output folder
    private $outputdir = "../maps/";
    // output file
    private $outputfile = "test.png";
    //
    private $ext = "png";
    //
    private $saveas = "png";

    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(60);
    }

    public function run() {

        $folder = $this->inputdir . $this->baseZoom . "/";
        $outputfile = $this->outputdir . $this->outputfile;
        $output = imagecreatetruecolor($this->oneblocksize * pow(2, $this->baseZoom), $this->oneblocksize * pow(2, $this->baseZoom));
        for ($i = 0; $i < pow(2, $this->baseZoom); $i++) {
            for ($j = 0; $j < pow(2, $this->baseZoom); $j++) {
                switch ($this->ext) {
                    case "png":
                        $src = imagecreatefrompng($folder . $i . "/" . $j . ".png");                    
                        break;
                    case "jpg":
                        $src = imagecreatefromjpeg($folder . $i . "/" . $j . ".jpg");                    
                        break;
                    default:
                        die ("no ext selected");
                        break;
                }
                
                
                
                imagecopymerge($output, $src, $i * $this->oneblocksize, $j * $this->oneblocksize, 0, 0, $this->oneblocksize, $this->oneblocksize, 100);                
                imagedestroy($src);
            }
        }
        switch ($this->saveas) {
            case "png":
                imagepng($output, $outputfile);
                break;
            case "jpg":
                imagejpeg($output, $outputfile, 100);
                break;
        }
        
        chmod($outputfile, 0777);
        imagedestroy($output);
        //imagedestroy($src);
    }

    public function setOutputDir($dir) {
        $this->outputdir = $dir;
    }

    public function setOutputFile($file) {
        $this->outputfile = $file;
    }

    public function setBaseZoom($zoom) {
        $this->baseZoom = $zoom;
    }

    public function setInputDir($dir) {
        $this->inputdir = $dir;
    }
    
    public function setImageType($ext){
        $this->ext = $ext;
    }

    public function setSaveAs($saveAs){
        $this->saveas = $saveAs;
    }
    
    public function getOutputFileLink() {
        return $this->outputdir . $this->outputfile;
    }

}
