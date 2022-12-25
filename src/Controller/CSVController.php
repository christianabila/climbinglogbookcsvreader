<?php

namespace ClimbingLogbook\Controller;

/**
 * Handles the CSV files
 */
class CSVController
{
    /**
     * Extract data from the CSV file
     *
     * Separate the basic information from the labels
     *
     * @return array Multi-dimensional array in the form of [0 => [basic information, label1, label2, ...], ...]
     */
    public static function extract($file): array
    {
        $headers = fgetcsv($file);

        // Labels start at column index 9!
        $logentries = [];
        while ($row = fgetcsv($file)) {
            $entry = [];
            for ($i = 0; $i < count($row); $i++) {
                if ($i < 9) {
                    $entry[] = $row[$i];
                } else {
                    if (!empty($row[$i])) {
                        $entry[] = $headers[$i];
                    }
                }
            }
            $logentries[] = $entry;
        }
        return $logentries;
    }
}
