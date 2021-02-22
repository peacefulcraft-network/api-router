<?php
namespace ncsa\phpmvj\util\files;

use ncsa\phpmvj\Application;
use RuntimeException;

trait DownloadFileByPath {
	/**
	 * Co-opts response to send back a file for download.
	 * Function assumes input has already been sanitized
	 * and that that provided filepath exists.
	 * @param file Fully qualified path to the target file
	 * @throws RuntimeException when unable to open requested file
	 */
	private function _downloadFileByPath(string $file, string $download_name = null): void {
		if ($download_name === null || strlen(trim($download_name)) === 0) {
			$download_name = basename($file);
		}

		// For downloads, treat all files as either plaintext or binary
		$content_length = filesize($file);

		// Disable Response output
		Application::getResponse()->setResponseTypeRaw(true);
		Application::getResponse()->setHeader('Content-Type', 'application/octet-stream');
		Application::getResponse()->setHeader('Content-Description', 'File Transfer');
		Application::getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $download_name);
		Application::getResponse()->setHeader('Content-Transfer-Encoding', 'binary');
		Application::getResponse()->setHeader('Content-Length', $content_length);
		ob_clean();
		readfile($file);
	}
}