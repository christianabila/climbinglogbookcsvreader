<?php

namespace ClimbingLogbook\Controller;

use ClimbingLogbook\Model\Entry;
use ClimbingLogbook\Model\EntryLabels;
use ClimbingLogbook\Model\Label;
use Exception;

/**
 * Enables manipulations on the climbing logbook.
 */
class LogbookController
{
    /**
     * All available labels.
     *
     * @var array [label1Name => label1Id, label2Name => label2Id, ...]
     */
    private static $labels;

    /**
     * Save the log book entries into the database
     *
     * @param array $entries
     * @return void
     */
    public static function save(array $entries)
    {
         // I need to know the currently available labels.
        if (empty(self::$labels)) {
            self::loadAllLabels();
        }

         // I will save data into the tables entry, label, and entrylabels.
        foreach ($entries as $entry) {
            DatabaseController::insert(new Entry(
                $entry[0],
                $entry[1],
                $entry[2],
                $entry[3],
                $entry[4],
                $entry[5] === '' ? 1 : $entry[5],
                $entry[6],
                $entry[7],
                $entry[8],
            ));

            $entryId = DatabaseController::select(
                'entry',
                ['MAX(id) id'],
                [],
            );

            $entryId = $entryId->fetchObject();

            if (!$entryId) {
                throw new Exception("Could not fetch entry database object!");
            }

            $entryId = $entryId->id;

            for ($i = 9; $i < count($entry); $i++) {
                if (!array_key_exists($entry[$i], self::$labels)) {
                    // Create a new label first, if necessary.
                    DatabaseController::insert(new Label(0, $entry[$i]));

                    // A new label exists, reload the labels array!
                    self::loadAllLabels();
                }

                // Create a new entrylabel row.
                DatabaseController::insert(new EntryLabels($entryId, self::$labels[$entry[$i]]));
            }
        }
    }

    /**
     * Fetches all available labels.
     *
     * @return void
     */
    private static function loadAllLabels()
    {
        $result = DatabaseController::select('label', [], []);

        self::$labels = [];

        while ($label = $result->fetchObject()) {
            self::$labels[$label->name] = $label->id;
        }
    }
    
    /**
     * Fetch all log book entries.
     *
     * @return void
     */
    public static function fetchAll()
    {
        $allEntries = DatabaseController::select('entry', [], []);
        $allEntryLabels = DatabaseController::select('entrylabels', [], []);
    }
}
