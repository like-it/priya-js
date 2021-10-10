<?php 
namespace model;

use R3m\Io\App;
use R3m\Io\Module\Core;
use R3m\Io\Module\Data;
use R3m\Io\Module\Dir;
use R3m\Io\Module\File;
use Controller\Js as Controller;
use ZipArchive;

class Js {
	const CREATE = 'create';
	const VERSION = 'version';
	const UPDATE = 'update';
	const ZIP = 'zip';
	
	public static function create(App $object, $options=[]){
		$command = $object->parameter($object, Js::CREATE, 1);
		switch($command){
			case 'core' :
				return Js::create_core($object, $options);
			case 'zip' :
				return Js::create_zip($object, $options);
            case 'all' :
                $result = [];
                $result[] = Js::create_core($object, $options);
                $result[] = Js::create_zip($object, $options);
                return implode("\n", $result);
			default :
				return Js::create_options($object, $options);
		}
	}

	public static function create_core(App $object, $options=[]){		
		$url = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/Bootstrap.json';
		$data = $object->data_read($url);		
		$list = $data->data('require.file');		
		sort($list, SORT_NATURAL);
		$core = [];
		foreach($list as $nr => $file){
			$comment = [];
			$comment[] = '/**';
			$comment[] = ' * ' . $file;
			$comment[] = ' */';
			$core[] = implode("\n", $comment);
			$file = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/' . $file;
			$read = File::read($file);
			$core[] = $read;
			$core[] = '';
		}
		$core = implode("\n", $core);
		
		$dir = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/Bin/'; //Core-' . $data->data('collect.version') . '.js';
		
		Dir::create($dir);
		$file = 'Core-' . $data->data('collect.version') . '.js';
		$url = $dir . $file;
		$written = File::write($url, $core);
		echo 'Bytes written: ' . $written . "\n";
		$list = [];
		$list[] = $file;
		$data->data('require.file', $list);
		$url = $dir . 'Bootstrap.json';
		$written = File::write($url, Core::object($data->data(), Core::OBJECT_JSON));
		echo 'Bytes written: ' . $written . "\n";
		$source_url = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/Priya.prototype.js';		
		$target_url = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/Bin/Priya.prototype.js';
		File::copy($source_url, $target_url);
		$written = File::size($target_url);
		echo 'Bytes written: ' . $written . "\n";
		$source_url = $object->config('project.dir.public') . 'Js/Development/Priya/Priya.js';
		$target_url = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/Priya.js';
		File::copy($source_url, $target_url);
		$written = File::size($target_url);
		echo 'Bytes written: ' . $written . "\n";		
		$source_url = $object->config('project.dir.public') . 'Js/Development/Priya/README.md';
		$target_url = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/README.md';
		File::copy($source_url, $target_url);
		$written = File::size($target_url);
		echo 'Bytes written: ' . $written . "\n";
		$source_url = $object->config('project.dir.public') . 'Js/Development/Priya/LICENSE';
		$target_url = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/LICENSE';
		File::copy($source_url, $target_url);
		$written = File::size($target_url);
		echo 'Bytes written: ' . $written . "\n";
		$source_url = $object->config('project.dir.public') . 'Js/Development/Priya/example.html';
		$target_url = $object->config('controller.dir.data') . 'Create/' . $data->data('collect.version') . '/Priya/example.html';
		File::copy($source_url, $target_url);
		$written = File::size($target_url);
		
		$url = $object->config('controller.dir.data') . 'Js.json';
		if(File::exist($url)){
			$js = $object->data_read($url);
		} else {
			$js = new Data();
		}		
		$js->data('create.version', $data->data('collect.version'));
		File::write($url, Core::object($js->data(), Core::OBJECT_JSON));				
		echo 'Bytes written: ' . $written . "\n";
	}
	
	public static function create_zip(App $object, $options=[]){
		$command = $object->parameter($object, Js::ZIP, 1);
		if(empty($command)){
			$url = $object->config('controller.dir.data') . 'Js.json';
			$js = $object->data_read($url);
			$version = $js->data('create.version');
		} else {
			$version = $command;
		}
		$dir = new Dir();
		$url = $object->config('controller.dir.data') . 'Create/' . $version . '/';		
		$read = $dir->read($url, true);
		$dir = $object->config('project.dir.public') . 'Download/';
		Dir::create($dir);
		$target = $dir . 'Priya-' . $version . '.zip';
		echo $target . "\n";
		$zip = new ZipArchive();
		$result = $zip->open($target, ZipArchive::CREATE);
		foreach($read as $nr => $file){
			if($file->type != 'File'){
				continue;
			}
			$explode = explode($url, $file->url, 2);
			$location = '';
			if(array_key_exists(1, $explode)){
				$location = $explode[1];
			}			
			$zip->addFile($file->url, $location);
		}
		$zip->close();						
	}
	
	public static function create_options(App $object, $options=[]){		
		return Controller::create_options($object);				
	}
	
	public static function version(App $object, $options=[]){
		$command = $object->parameter($object, Js::VERSION, 1);		
		switch($command){
			case 'update' :
				return Js::version_update($object, $options);
            case '?' :
            case 'help' :
                return Js::version_options($object, $options);
			default:
				return Js::version_get($object, $options);
		}		
	}

    public static function version_options(App $object, $options=[]){
        return Controller::version_options($object);
    }
	
	public static function version_update(App $object, $options=[]){
		$version = $object->parameter($object, Js::UPDATE, 1);
		echo 'Updating Bootstrap.json with new version...' . "\n";
		$url = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/Bootstrap.json';
		$data = $object->data_read($url);		
		if(empty($version)){			
			$explode = explode('.', $data->data('collect.version'));
			$major = 0;
			$minor = 0;
			$patch = 0;
			if(array_key_exists(0,$explode)){
				$major = intval($explode[0]) + 0;
			}
			if(array_key_exists(1,$explode)){
				$minor = intval($explode[1]) + 0;
			}
			if(array_key_exists(2,$explode)){
				$patch = intval($explode[2]) + 0;
			}
			$patch++;
			$version = $major . '.' . $minor . '.' . $patch;			
		}
		$data->data('collect.version', $version);
		$data->data('query', $data->data('collect.version'));										
		File::write($url, Core::object($data->data(), Core::OBJECT_JSON));
		echo 'Updating Priya.prototype.js with new version...' . "\n";
		$url = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/Priya.prototype.js';		
		$read = File::read($url);
		$explode = explode('this.version = \'', $read, 2);
		if(array_key_exists(1, $explode)){
			$temp = explode('\';', $explode[1], 2);
			if(array_key_exists(1, $temp)){
				$temp[0] = $data->data('collect.version');
				$explode[1] = implode('\';', $temp);
				$read = implode('this.version = \'', $explode);
			}						
		}
		File::write($url, $read);
		echo 'Updating Example.html with new version...' . "\n";
		$url = $object->config('project.dir.public') . 'Js/Development/Priya/example.html';
		$read = File::read($url);
		$explode = explode('src="Priya.js?', $read, 2);
		if(array_key_exists(1, $explode)){
			$temp = explode('"', $explode[1], 2);
			if(array_key_exists(1, $temp)){				
				$temp[0] = $data->data('collect.version');
				$explode[1] = implode('"', $temp);
				$read = implode('src="Priya.js?', $explode);
			}
		}
		File::write($url, $read);
		return $data->data('collect.version'). "\n";
	}
	
	public static function version_get(App $object, $options=[]){
		$url = $object->config('project.dir.public') . 'Js/Development/Priya/Bin/Bootstrap.json';
		$data = $object->data_read($url);
		if($data){
            return $data->data('collect.version'). "\n";
        }
	}
}