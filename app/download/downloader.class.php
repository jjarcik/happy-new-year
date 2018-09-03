<?php

class DownloaderMap {

    private $url = "http://c.tile.openstreetmap.org/"; //"http://c.tile.openstreetmap.org/0/0/0.png";
    private $zoom = 5;
    private $saveto = "../maps/map3/"; // with "/" at the end
    private $imgext = "png";

    public function run() {

        for ($i = 0; $i <= $this->zoom; $i++) {
            for ($j = 0; $j < pow(2, $i); $j++) {
                $folder2save = $this->saveto . $i . "/" . $j . "/";
                $this->checkFolderTree(array($this->saveto, $this->saveto . $i, $folder2save));
                for ($k = 0; $k < pow(2, $i); $k++) {
                    $file2save = $folder2save . $k . "." . $this->imgext;
                    file_put_contents($file2save, fopen($this->url . $i . "/" . $j . "/" . $k . "." . $this->imgext, 'r'));
                }
            }
        }
    }
    
    public function setUrl($url){
        $this->url = $url;
    }
    
    public function setImageType($ext){
        $this->imgext = $ext;
    }
    
    public function setSaveToFolder($folder){
        $this->saveto = $folder;
    }
    
    public function setMaxZoom($zoom){
        $this->zoom = $zoom;
    }

    private function checkFolderTree($array) {
        foreach ($array as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder, null);
                chmod($folder, 0777);
            }
        }
    }

}
