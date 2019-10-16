<?php
namespace kilyakus\widget\windy;

use yii\web\AssetBundle;

class WindyAssets extends AssetBundle
{
    public function init()
    {
        
        
        parent::init();
    }

    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');

        $this->js = [
            'vendor/leaflet/leaflet.js',
            'https://api4.windy.com/assets/libBoot.js',
        ];

        $this->setupAssets('css', ['css/windy'],'widget-windy');
    }
}
