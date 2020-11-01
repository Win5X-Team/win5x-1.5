<?php

class randomAvatarsGenerator {

    private $image;
    private $preset;
    private $arraypreset;
    private $color1;
    private $color2;
    private $image_location;

    //List of colors (from http://flatuicolors.com/) and presets
    private $primary_colors = ['#2ecc71', '#3498db', '#e74c3c', '#f39c12', '#1abc9c', '#9b59b6'];
    private $secondary_colors = ['#34495e', '#ecf0f1', '#95a5a6'];
    private $presets = ['------*-*---*---*-*--*-*-','******---**-*-**---******','*-----*----***----*-----*','------***---*---***------','--*--**-**--*--*****--*--','-------*---***---*-------','------*-*-*-*-*-*-*-*---*','-----------*-*--*-*--***-','-----*----*-*--*-----***-','------*-*-*****----------'];

    //Functions to convert # colors to RGB
    private function getRed($color) {
        $color = substr($color, 1);
        $red   = substr($color, 0, 2);
        return hexdec($red);
    }
    private function getGreen($color) {
        $color = substr($color, 1);
        $green = substr($color, 2, 2);
        return hexdec($green);
    }
    private function getBlue($color) {
        $color = substr($color, 1);
        $blue  = substr($color, 4, 2);
        return hexdec($blue);
    }

    //Function to randomly take an item in a list
    private function pickrandom($data) {
        $number = rand(0, count($data) - 1);
        return $data[$number];
    }

    public function generate() {
        //Choose randomly color1, color2 and a preset
        $this->color1 = $this->pickrandom($this->primary_colors);
        $this->color2 = $this->pickrandom($this->secondary_colors);
        $preset = $this->pickrandom($this->presets);

        //Parse $preset to replace with color1 or color2
        $newpreset[''] = null;
        $i = 0;
        while ($i < 25) {
            if(substr($preset, $i, 1) == "*") {
                $newpreset[] = $this->color1;
            } elseif (substr($preset, $i, 1) == "-") {
                $newpreset[] = $this->color2;
            }
            $i++;
        }
        $this->arraypreset = $newpreset;
        $this->preset = implode("", $newpreset);
    }

    public function draw() {
        //Create image (5px x 5px) and define RGB colors
        $image = imagecreate(5,5);
        $rgb_color1 = imagecolorallocate($image, $this->getRed($this->color1), $this->getGreen($this->color1), $this->getBlue($this->color1));
        $rgb_color2 = imagecolorallocate($image, $this->getRed($this->color2), $this->getGreen($this->color2), $this->getBlue($this->color2));

        //Parse $newpreset to write a pixel with the specified color
        $x = 0; $y = 0; $i = 0;
        while ($y != 5) {
            if($this->arraypreset[$i] == $this->color1) {
                ImageSetPixel ($image, $x, $y, $rgb_color1);
            } elseif ($this->arraypreset[$i] == $this->color2) {
                ImageSetPixel ($image, $x, $y, $rgb_color2);
            }
            $x++; $i++;
            if($x == 5) { $x = 0; $y++; }
        }

        //Resizing $image (5px x 5px) to $image_resized (320px x 320px)
        $image_resized = imagecreate(320,320);
        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, 320, 320, 5, 5);
        $this->image = $image_resized;
    }

    public function saveImage($dirname, $filename) {
        if(!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }
        imagepng($this->image, $dirname . '/' . $filename);
        $this->image_location = $dirname . '/' . $filename;
    }

    public function show($tag) {
        $tag = str_replace('<img', '<img src="' . $this->image_location . '"', $tag);
        echo $tag;
    }

}