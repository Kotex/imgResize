<?
define("ROOTDIR", $_SERVER['DOCUMENT_ROOT']);
define("CACHEFOLDER", ROOTDIR."/imgResize/cache/");


$defPathCache = CACHEFOLDER;
if (!is_dir($defPathCache)){
	mkdir($defPathCache, 0777, true);
}
require 'vendor/autoload.php';
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['file'])){
	$work = "/".$_GET['file'];
	$work = explode(":",$work);

	$rFile = ROOTDIR.array_shift($work);
	$opts = array_shift($work);
	if(!file_exists($rFile)){
		header("HTTP/1.0 404 Not Found");
		exit('No File');
	}

	if(isset($opts)){
		if(preg_match("/^[a-z]+/i", $opts)){
			switch ($opts) {
				case 'medium':
					doIt($rFile,600,400);				
					break;
				
				case 'large':
					doIt($rFile,800,600);				
					break;
				
				case 'small':
					doIt($rFile,250,200);
					break;
				
				case 'thumb':
					doIt($rFile,50,50);				
					break;
				
				default:
					$gData = explode("x",$opts);
					doIt($rFile,100,100);		
					break;
			}			
		}else{
			$gData = explode("x",$opts);
			doIt($rFile,$gData[0],$gData[1],(isset($gData[2])?$gData[2]:false));
		}
	}else{
		doIt($rFile);
	}
}

function doIt($path, $width = false, $height = false, $case = 1){
	$source = $path;
	$fileData = pathinfo($path);

	$size = @filesize($path);
	$lastModified = filemtime($path);

	$mime = "application/octet-stream";

	$genFileName = md5($lastModified.$width.$height.$case.$fileData['filename']).".".$fileData['extension'];
	if(file_exists(CACHEFOLDER.$genFileName)){
		justShow($mime,$lastModified,$fileData,CACHEFOLDER.$genFileName);
		exit;
	}

	header($_SERVER['SERVER_PROTOCOL']." 200 OK");

	if($width){
		$imagine = new Imagine\Gd\Imagine();
		$size = new Imagine\Image\Box($width, $height);
		switch($case){
			case '2':
				$mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
				break;
			
			default:
				$mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
				break;
		}
		$resizeimg = $imagine->open($source)->thumbnail($size, $mode)->save(CACHEFOLDER.$genFileName);
		justShow($mime,$lastModified,$fileData,CACHEFOLDER.$genFileName);
	}else{
		justShow($mime,$lastModified,$fileData,$source);
	}

	exit;
}

function justShow($mime,$lastModified,$fileData,$source){

	header("Last-Modified: ".$lastModified);
	header("Content-string: ".$mime);
	header("Content-type: image/".$fileData['extension']);
	header('Content-Transfer-Encoding: binary');

	header("Connection: close");
	echo file_get_contents($source);
}

?>