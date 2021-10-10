<?php 
namespace Controller;

use R3m\Io\App;
use R3m\Io\Module\Core;
use R3m\Io\Module\View;

class Js extends View {
	const DIR = __DIR__ . DIRECTORY_SEPARATOR;
	
	public static function version(App $object){
		$name = Core::ucfirst_sentence(str_replace('_','.', __FUNCTION__));
		$url = Js::locate($object, $name);
		$view = Js::response($object, $url);
		return $view;
	}

    public static function version_options(App $object){
        $name = Core::ucfirst_sentence(str_replace('_','.', __FUNCTION__));
        $url = Js::locate($object, $name);
        $view = Js::response($object, $url);
        return $view;
    }

	public static function create(App $object){
		$name = Core::ucfirst_sentence(str_replace('_','.', __FUNCTION__));
		$url = Js::locate($object, $name);
		$view = Js::response($object, $url);
		return $view;
	}
	
	public static function create_options(App $object){
		$name = Core::ucfirst_sentence(str_replace('_','.', __FUNCTION__));
		$url = Js::locate($object, $name);
		$view = Js::response($object, $url);
		return $view;
	}
}

