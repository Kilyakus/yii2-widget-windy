<?php
namespace kilyakus\widget\windy;

use yii\web\AssetBundle;

class WindyAssets extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        $this->js[] = 'vendor/leaflet/leaflet.js';
        $this->js[] = 'https://api4.windy.com/assets/libBoot.js';

        $this->css[] = 'css/windy.css';
    }
}