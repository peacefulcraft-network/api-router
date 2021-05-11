<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\router\Controller;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\util\cors\StandardGet;
use net\peacefulcraft\apirouter\util\files\DownloadFileByPath;

class HTTPFileDownload implements Controller {
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