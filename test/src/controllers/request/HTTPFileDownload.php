<?php
namespace ncsa\phpmcj\test\controllers\request;

use ncsa\phpmcj\Application;
use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;
use ncsa\phpmcj\util\cors\StandardGet;
use ncsa\phpmcj\util\files\DownloadFileByPath;

class HTTPFileDownload implements RequestHandler {
	use StandardGet, DownloadFileByPath;

	public function handle(array $config, Request $request, Response $response): void {
		/**
		 * This controller is minimal and built for testing only.
		 * Do not use this controller in production, it does not
		 * properly sanitize user input and effectivly grants
		 * read access to the webservers entire file system.
		 */
		$file_path = __DIR__ . '/../../../tests/resources/request/' . $request->getUriParameters()['filename'];

		if (!file_exists($file_path) || !is_file($file_path)) {
			$response->setHttpResponseCode(Response::HTTP_NOT_FOUND);
			$response->setErrorCode(404);
			$response->setErrorMessage('File not found.');
			return;
		}

		$this->_downloadFileByPath($response, $file_path);
	}
}