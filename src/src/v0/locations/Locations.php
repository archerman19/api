<?php

namespace Api\v0;

use DistanceCalculator;
use Geocoder\Query\GeocodeQuery;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Client;

class Locations extends ApiAbstract {
	/**
	 * Расчет расстояния
	 *
	 * @param string $addressFrom
	 * @param string $addressTo
	 * @return Response
	 * @access(auth)
	 */
	public function distance(string $addressFrom, string $addressTo) : Response {
		$client = new Client();
		$provider = new \Geocoder\Provider\Yandex\Yandex($client, null, 'a1a248db-7022-4b44-9e29-85bc17cd7f22');
		$resultFrom = $provider->geocodeQuery(GeocodeQuery::create($addressFrom));
		$resultTo = $provider->geocodeQuery(GeocodeQuery::create($addressTo));
		$point1 = $resultFrom->first()->getCoordinates()->toArray();
		$point2 = $resultTo->first()->getCoordinates()->toArray();

		$this->data['distance'] = DistanceCalculator::calcDistance($point1, $point2);
		$this->data['measurement'] = 'km';
		
		return $this->response();
	}
}