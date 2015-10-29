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
				shell_exec("./browser.sh $movieId > /dev/null 2>&1 &");
				return array('return'=>'0');
			}
		}

		return FALSE;
	}
}
?>
