<?php
class DataMining {
	private $api_key_freebase = 'AIzaSyCwnpSWj7Hz5VFIE47L13acKmkNvNowGiI';
	private $api_key_lastFM = '3f727d7f46b7c9c9d956bed64112b212';
	
	public function searchFreeBaseGenre($query, $filter = '', $output = '', $start = 0, $limit = 10, $exact = 'true') {
		if (! empty ( $query )) {
			$query = urlencode ( $query );
			$url = 'https://www.googleapis.com/freebase/v1/search?query=' . $query;
			$url .= '&filter=(' . urlencode ( $filter ) . ')';
			$url .= '&output=(' . urlencode ( $output ) . ')';
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
	function searchLastFMCorrection($artist, $data = json) {
		if (! empty ( $artist )) {
			$url = 'http://ws.audioscrobbler.com/2.0/?' . $query;
			$url .= 'method=' . urlencode ( 'artist.getcorrection' );
			$url .= '&artist=' . urlencode ( $artist );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['corrections'];
			
			$result = $raw_result ['correction'] ['artist'] ['name'];
		}
		
		return $result;
	}
	function searchLastFMArtist($artist, $data = json) {
		if (! empty ( $artist )) {
			$artist = $this->searchLastFMCorrection ( $artist );
			$url = 'http://ws.audioscrobbler.com/2.0/?' . $query;
			$url .= 'method=' . urlencode ( 'artist.getinfo' );
			$url .= '&artist=' . urlencode ( $artist );
			$url .= '&api_key=' . $this->api_key_lastFM;
			$url .= '&format=' . $data;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true );
			$raw_result = $raw_result ['artist'];
			
			$result ['name'] = $raw_result ['name'];
			$result ['similar'] = $raw_result ['similar'];
		}
		
		return $result;
	}
}

// Teste
$fb = new DataMining ();
$result = $fb->searchLastFMArtist ( 'Hypnos69' );

$result = $fb->searchFreeBaseGenre ( 'Metallica', 'any type:/music/artist', '/music/artist/genre' );
?>