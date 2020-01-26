<?php
namespace kilyakus\widget\windy;

use Yii;
use yii\web\JsExpression;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Windy extends Widget
{
	public $pluginName = 'windy';

	public $wrapperOptions = [];

	public $key;

	public $verbose = true;

	public $model = 'ecmwf';

	public $latitude = 0;

	public $longitude = 0;

	public $zoom = 10;

	public $hourFormat = '12h';

	public $jsOptions = [];

	public $overlays = true;

	public $disableEvents = [];

	public function init()
	{
		$this->wrapperOptions = ArrayHelper::merge(['id' => 'windy'], $this->wrapperOptions);

		$this->jsOptions = ArrayHelper::merge([
			'key'			=> $this->key,
			'verbose'		=> $this->verbose,
			'model'			=> $this->model,
			'lat'			=> $this->latitude,
			'lon'			=> $this->longitude,
			'zoom'			=> $this->zoom,
			'timestamp'		=> time() + 3 * 24 * 60 * 60 * 1000,
			'hourFormat'	=> $this->hourFormat,
		], $this->jsOptions);

		$this->jsOptions = Json::encode($this->jsOptions);

		$this->disableEvents = ArrayHelper::merge([
			'doubleClickZoom' => false,
			'scrollWheelZoom' => false,
			'touchZoom' => false,
			'dragging' => false,
			'boxZoom' => false,
		], $this->disableEvents);

		$this->disableEvents = Json::encode($this->disableEvents);
	}

	public function run()
	{
		parent::run();

		WindyAssets::register(Yii::$app->getView());

		$this->view->registerJs(new JsExpression("
			$(document).ready(function() {

				const " . $this->id . "Options = " . $this->jsOptions . ";

				windyInit( " . $this->id . "Options, windyAPI => {

					const { map, marker, overlays, picker, utils, broadcast, store } = windyAPI

					/*--- disable events methods ---*/

					const disabledEvents = ".$this->disableEvents.";

					for(var i in disabledEvents) {
						if(disabledEvents[i] == true){
							// console.log(map[i]);
							map[i].disable();
						}
					}


					const windMetric = overlays.wind.metric;

					overlays.wind.listMetrics();
					// ['kt', 'bft', 'm/s', 'km/h', 'mph'] .. available metrics
					overlays.wind.setMetric('m/s');


					map.on('click',latLon => {

						const latlng = latLon.latlng;

						return picker.open({ lat: latlng.lat, lon: latlng.lng });

					})

					picker.on('pickerOpened', latLon => {
						// picker has been opened at latLon coords
						// console.log(latLon);

						const { lat, lon, values, overlay } = picker.getParams();
						// -> 50.4, 14.3, 'wind', [ U,V, ]
						// console.log(lat, lon, values, overlay);

						const windObject = utils.wind2obj(values);
						// console.log(windObject);
					});

					picker.on('pickerMoved', latLon => {
						// picker was dragged by user to latLon coords
						// console.log(latLon);
					});

					picker.on('pickerClosed', () => {
						// picker was closed
					});

					broadcast.once('redrawFinished', () => {

						picker.open({ lat: " . $this->latitude . ", lon: " . $this->longitude . " })

					})

					L.marker([" . $this->latitude . "," . $this->longitude . "],{icon: map.myMarkers.pulsatingIcon}).addTo(map).setLatLng([" . $this->latitude . "," . $this->longitude . "]);
				})
			});
		"), yii\web\View::POS_END);

		return Html::tag('div', '', $this->wrapperOptions);
	}
}
