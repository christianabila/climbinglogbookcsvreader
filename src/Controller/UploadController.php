<?php

namespace ClimbingLogbook\Controller;

use Exception;

/**
 * Controls things regarding the file upload.
 */
class UploadController
{
    public const ACCEPTED_MIMETYPE = 'text/csv';

    /**
     * Validate the uploaded file
     *
     * - Check, if it is a .csv-file
     * - Check, that the first row contains the headers
     *
     * @param array $file The uploaded file (= content of $_FILES[''])
     * @return boolean true on success, Exception thrown otherwise
     * @throws Exception
     */
    public static function validate(array $file): bool
    {
        if (!(isset($file['type']) && $file['type'] === self::ACCEPTED_MIMETYPE)) {
            throw new Exception('I only accept .csv-files!');
        }

        if (isset($file['error']) && $file['error'] > 0) {
            throw new Exception('Upload error code: ' . $file['error']);
        }

        return true;
    }

    /**
     * Save the uploaded file on the server.
     *
     * @param array $file The uploaded file (= content of $_FILES[''])
     * @return string The local save path of the uploaded file on success, Exception thrown otherwise
     * @throws Exception
     */
    public static function save(array $file): string
    {
        $localURI = $_ENV['UPLOAD_DIR'] . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $localURI)) {
            return $localURI;
        }

        throw new Exception("Could not move uploaded file!");
    }
}
