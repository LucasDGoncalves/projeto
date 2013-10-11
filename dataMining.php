<?php
class DataMining {
	private $api_key_freebase = 'AIzaSyCwnpSWj7Hz5VFIE47L13acKmkNvNowGiI';
	private $api_key_lastFM = '3f727d7f46b7c9c9d956bed64112b212';
	public function searchFreeBaseGenre($query, $filter = '', $output = '', $start = 0, $limit = 10, $exact = 'true') {
		if (! empty ( $query )) {
			$query = urlencode ( $query );
			$url = 'https://www.googleapis.com/freebase/v1/search?query=' . $query;
			$url .= '&filter=(' . urlencode ( 'any type:/music/artist' ) . ')';
			$url .= '&output=(' . urlencode ( '/music/artist/genre' ) . ')';
			$url .= '&start=' . $start;
			$url .= '&limit=' . $limit;
			$url .= '&exact=' . $exact;
			$url .= '&key=' . $this->api_key_freebase;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['result'];
			
			$result ['name'] = $raw_result [0] ['name'];
			$i = 0;
			foreach ( $raw_result [0] ['output'] ['/music/artist/genre'] ['/music/artist/genre'] as $genre ) {
				$result ['genre'] [$i] = $genre ['name'];
				$i ++;
			}
			return $result;
		}
	}
	public function freeBaseLocation($query, $filter = '', $output = '', $start = 0, $limit = 10, $exact = 'false') {
		if (! empty ( $query )) {
			$query = urlencode ( $query );
			$url = 'https://www.googleapis.com/freebase/v1/search?query=' . $query;
			$url .= '&filter=(' . urlencode ( 'any type:\'/location\'' ) . ')';
			$url .= '&output=(' . urlencode ( '/location/location/containedby' ) . ')';
			$url .= '&start=' . $start;
			$url .= '&limit=' . $limit;
			$url .= '&exact=' . $exact;
			$url .= '&key=' . $this->api_key_freebase;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['result'];
			
			$result ['name'] = $raw_result [0] ['name'];
			$result ['notable'] = $raw_result [0] ['notable'] ['name'];
			$result ['name2'] = $raw_result [1] ['name'];
			$result ['notable2'] = $raw_result [1] ['notable'] ['name'];
			$i = 0;
			foreach ( $raw_result [0] ['output'] ['/location/location/containedby'] ['/location/location/containedby'] as $genre ) {
				$result ['containdby'] [$i] = $genre ['name'];
				$i ++;
			}
			return $result;
		}
	}
	function getLocationInfo($query) {
		$res = $this->freeBaseLocation ( $query );
		$location ['city'] = $res ['name'];
		if (strcmp ( $res ['notable'], 'City/Town/Village' ) == 0) {
			$size = count ( $res ['containdby'] );
			if (strcmp ( $res ['name'], $res ['name2'] ) == 0) {
				if (strpos ( $res ['notable2'], 'state' ) !== false) {
					$location ['state'] = $res ['name2'];
				}
			}
			if ($location ['state'] == null || $location ['country'] == null) {
				for($i = 0; $i < $size; $i ++) {
					$query = $res ['containdby'] [$i];
					$l = $this->getLocationInfo ( $query );
					if (isset ( $l )) {
						$location = array_merge ( $location, $l );
					}
					if ($location ['state'] !== null && $location ['country'] !== null) {
						break;
					}
				}
			}
			$location ['city'] = $res ['name'];
		} elseif (strpos ( $res ['notable'], 'state' ) !== false) {
			$location ['state'] = $res ['name'];
			$size = count ( $res ['containdby'] );
			for($i = 0; $i < $size; $i ++) {
				$query = $res ['containdby'] [$i];
				$l = $this->getLocationInfo ( $query );
				if (isset ( $l )) {
					$location = array_merge ( $location, $l );
				}
				if ($location ['country'] !== null) {
					break;
				}
			}
		} elseif (strcmp ( $res ['notable'], 'Country' ) == 0) {
			$location ['country'] = $res ['name'];
		}
		return $location;
	}
	public function searchLastFMCorrection($artist, $data = 'json') {
		if (! empty ( $artist )) {
			
			$url = 'http://ws.audioscrobbler.com/2.0/?';
			$url .= 'method=' . urlencode ( 'artist.getcorrection' );
			$url .= '&artist=' . urlencode ( $artist['name'] );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			echo $url.'<br>';
			
			$json = file_get_contents($url);
			$raw_result = json_decode ($json, true);
 			$raw_result = $raw_result['corrections'];
			
 			if (count($raw_result, 1) <= 1) {
				$result = $artist['name'];
			} else {
				$result = $raw_result ['correction'] ['artist'] ['name'];
			}
		}
		
		return $result;
	}
	public function searchLastFMArtist($artist, $data = 'json') {
		if (! empty ( $artist )) {

			$result = $this->searchLastFMCorrection ( $artist['name'] );
			if (! empty ( $result )) {
				$artist = $result;
			}
			$url = 'http://ws.audioscrobbler.com/2.0/?';
			$url .= 'method=' . urlencode ( 'artist.getinfo' );
			$url .= '&artist=' . urlencode ( $artist );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['artist'];
			
			//$result ['name'] = $raw_result ['name'];
			//$result ['similar'] = $raw_result ['similar'];
			//$result ['placeformed'] = $raw_result ['bio'] ['placeformed'];
		}
		
		return $result;
	}
}
?>