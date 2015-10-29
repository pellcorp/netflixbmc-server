<?php

class Addons {
	public function GetAddons($params_r) {
		return array('addons'=>
			array(array('addonid'=>'plugin.video.netflixbmc', 'type'=>'xbmc.python.pluginsource')));
	}
}
?>
