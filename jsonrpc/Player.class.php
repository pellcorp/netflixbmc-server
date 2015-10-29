<?php

class Player {
	public function Open($params) {
		$item_r = $params['item'];
		$file = $item_r['file'];
		$url = parse_url($file);
		if ($url['host'] == 'plugin.video.netflixbmc') {
			$query = $url['query'];
			parse_str($query, $query_array);
			if ($query_array['mode'] == 'playVideo') {
				$movieId = $query_array['url'];
				$netflixUrl = "https://netflix.com/WiPlayer?movieid=$movieId";
				exec("./browser.sh $netflixUrl", $output, $return_val);

				return array('return'=>$return_val);
			}
		}

		return FALSE;
	}
}
?>
