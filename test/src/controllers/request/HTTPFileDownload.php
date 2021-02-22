<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\util\cors\StandardGet;
use ncsa\phpmvj\util\files\DownloadFileByPath;

class HTTPFileDownload implements RequestHandler {
	use StandardGet, DownloadFileByPath;

	public function handle(): void {
		/**
		 * This controller is minimal and built for testing only.
		 * Do not use this controller in production, it does not
		 * properly sanitize user input and effectivly grants
		 * read access to the webservers entire file system.
		 */
		$file_path = __DIR__ . '/../../../tests/resources/request/' . $_GET['filename'];

		if (!file_exists($file_path)) {
			Application::getResponse()->setHttpResponseCode(Response::HTTP_NOT_FOUND);
			Application::getResponse()->setErrorCode(404);
			Application::getResponse()->setErrorMessage('File not found.');
			return;
		}

		$this->_downloadFileByPath($file_path);
	}
}