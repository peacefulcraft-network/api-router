<?php
namespace net\peacefulcraft\apirouter\util\files;

use net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\router\Response;
use RuntimeException;

trait DownloadFileByPath {
	/**
	 * Co-opts response to send back a file for download.
	 * Function assumes input has already been sanitized
	 * and that that provided filepath exists.
	 * @param response The response object to manipulate
	 * @param file Fully qualified path to the target file
	 * @param download_name Name of the file to send to the user. Defaults to filename on disk.
	 * @throws RuntimeException when unable to open requested file
	 */
	private function _downloadFileByPath(Response $response, string $file, string $download_name = null): void {
		if ($download_name === null || strlen(trim($download_name)) === 0) {
			$download_name = basename($file);
		}

		// For downloads, treat all files as either plaintext or binary
		$content_length = filesize($file);

		// Disable Response output
		$response->setHttpResponseCode(Response::HTTP_OK);
		$response->setResponseTypeRaw(true);
		$response->setHeader('Content-Type', 'application/octet-stream');
		$response->setHeader('Content-Description', 'File Transfer');
		$response->setHeader('Content-Disposition', 'attachment; filename=' . $download_name);
		$response->setHeader('Content-Transfer-Encoding', 'binary');
		$response->setHeader('Content-Length', $content_length);
		ob_clean();
		readfile($file);
	}
}