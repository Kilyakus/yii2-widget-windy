<?php
namespace kilyakus\widget\windy;

use yii\web\AssetBundle;

class WindyAssets extends AssetBundle
{
	public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        $this->js[] = 'vendor/leaflet/leaflet.js';
        // $this->js[] = 'https://api4.windy.com/assets/libBoot.js';
        $this->js[] = 'js/libBoot.js';
        // $this->js[] = 'https://www.windy.com/v/23.1.1.lib.baaa/libBoot.js';

        $this->css[] = 'css/windy.css';
    }

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}