<?php
class Freebase {
	private $api_key = 'AIzaSyCwnpSWj7Hz5VFIE47L13acKmkNvNowGiI';
	public function search($query, $filter = '', $output = '', $start = 0, $limit = 10, $exact = 'true') {
		if (! empty ( $query )) {
			$query = urlencode ( $query );
			$url = 'https://www.googleapis.com/freebase/v1/search?query=' . $query;
			$url .= '&filter=(' . urlencode ( $filter ) . ')';
			$url .= '&output=(' . urlencode ( $output ) . ')';
			$url .= '&start=' . $start;
			$url .= '&limit=' . $limit;
			$url .= '&exact=' . $exact;
			$url .= '&key=' . $this->api_key;
			
			$raw_result = json_decode ( file_get_contents ( $url ), true )['result'];
			$result ['name'] = $raw_result [0] ['name'];
			$i = 0;
			foreach ( $raw_result [0] ['output'] ['/music/artist/genre'] ['/music/artist/genre'] as $genre ) {
				$result ['genre'] [$i] = $genre ['name'];
				$i ++;
			}
			
			return $result;
		}
	}
}

// Teste
$fb = new Freebase ();
$result = $fb->search ( 'Bob Dylan', 'any type:/music/artist', '/music/artist/genre' );
?>