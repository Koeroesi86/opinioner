<?php
namespace App\Http\Controllers;

use DB;
use Config;
use File;

class AttachmentController extends BaseController {
    /**
     * Create a new controller instance.
     *
     */
	public function __construct()
	{
		$this->getSettings();
	}
	
	/**
     * @param string $filename
     * @return string mimetype
     */
    public function getMimeType($filename) {

        // Make the input file path.
        $inputDir = Config::get('assets.images.paths.input');
        $inputFile = $inputDir . '/' .  $filename;

        // Get the file mimetype using the Symfony File class.
        $file = new \Symfony\Component\HttpFoundation\File\File($inputFile);
        return $file->getMimeType();
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return \Illuminate\Http\Response
     */
	public function get($attachment)
	{
		\Event::fire('BeforeAttachmentLoad', $attachment);
		
		$code = 200;
		$modified = File::lastModified($attachment->body);
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $modified) {
			\App::abort(304);
			$code = 304;
		}

		// We return our image here.
        $filename = $attachment->body;
        return response()->file($filename, array(
            'Content-Type' => $this->getMimeType($filename),
            'Content-Length' => File::size($filename),
            'Last-Modified' => gmdate("D, d M Y H:i:s", $modified) . " GMT"
        ));
	}

}
