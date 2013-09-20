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
	public function searchFreeBaseLocation($query, $filter = '', $output = '', $start = 0, $limit = 10, $exact = 'false') {
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
			$i = 0;
			foreach ( $raw_result [0] ['output'] ['/location/location/containedby'] ['/location/location/containedby'] as $genre ) {
				$result ['containdby'] [$i] = $genre ['name'];
				$i ++;
			}
			return $result;
		}
	}
	function searchLastFMCorrection($artist, $data = json) {
		if (! empty ( $artist )) {
			$url = 'http://ws.audioscrobbler.com/2.0/?' . $query;
			$url .= 'method=' . urlencode ( 'artist.getcorrection' );
			$url .= '&artist=' . urlencode ( $artist );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['corrections'];
			if (strlen ( $raw_reult ) == 0) {
				$result = false;
			} else {
				$result = $raw_result ['correction'] ['artist'] ['name'];
			}
		}
		
		return $result;
	}
	function searchLastFMArtist($artist, $data = json) {
		if (! empty ( $artist )) {
			$result = $this->searchLastFMCorrection ( $artist );
			if (! empty ( $result )) {
				$artist = $result;
			}
			$url = 'http://ws.audioscrobbler.com/2.0/?' . $query;
			$url .= 'method=' . urlencode ( 'artist.getinfo' );
			$url .= '&artist=' . urlencode ( $artist );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['artist'];
			
			$result ['name'] = $raw_result ['name'];
			$result ['similar'] = $raw_result ['similar'];
			$result ['placeformed'] = $raw_result ['bio'] ['placeformed'];
		}
		
		return $result;
	}
}

// Teste
$fb = new DataMining ();

$result = $fb->searchLastFMArtist ( 'Zeca Baleiro' );
$result = $fb->searchFreeBaseGenre ( 'Metallica' );
$result = $fb->searchFreeBaseLocation ( 'Brasil' );

?>