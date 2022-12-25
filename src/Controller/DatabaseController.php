<?php

namespace ClimbingLogbook\Controller;

use Exception;
use PDO;

class DatabaseController
{
    /**
     * All available labels.
     *
     * @var array [label1Name => label1Id, label2Name => label2Id, ...]
     */
    private static $labels;

    /**
     * Undocumented function
     *
     * @return PDO
     */
    private static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        return new PDO('mysql:host='.$host.';dbname='.$name, $user, $password);
    }

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

        $connection = self::connect();

        // I will save data into the tables entry, label, and entrylabels

        $newEntrySql = "
        INSERT INTO entry (date, grade, gradeindex, climbtype, ascenttype, attempts, walltype, climbname, details)
             VALUES (:date, :grade, :gradeindex, :climbtype, :ascenttype, :attempts, :walltype, :climbname, :details)";
        $newEntryStatement = $connection->prepare($newEntrySql);

        $getNewestEntrySql = "
        SELECT MAX(id) AS id
          FROM entry";

        $newLabelSql = "
        INSERT INTO label (name)
             VALUES (:name)";

        $newLabelStatement = $connection->prepare($newLabelSql);

        $getNewestLabelSql = "
        SELECT MAX(id) id
          FROM label";

        $newEntryLabelSql = "
        INSERT INTO entrylabels (entryid, labelid)
             VALUES (:entryid, :labelid)";

        $newEntryLabelStatement = $connection->prepare($newEntryLabelSql);

        foreach ($entries as $entry) {
            if (!$newEntryStatement->execute([
                'date' => $entry[0],
                'grade' => $entry[1],
                'gradeindex' => $entry[2],
                'climbtype' => $entry[3],
                'ascenttype' => $entry[4],
                'attempts' => empty($entry[5]) ? 1 : $entry[5],
                'walltype' => $entry[6],
                'climbname' => $entry[7],
                'details' => $entry[8]
            ])) {
                throw new Exception("Error inserting new entry.");
            };

            $entryId = $connection->query($getNewestEntrySql, PDO::FETCH_OBJ);
            if (!$entryId) {
                throw new Exception("Error getting newest entry ID");
            }

            if (!$entryId->execute()) {
                throw new Exception("Error fetching entry ID");
            }

            $entryId = $entryId->fetchObject();

            $entryId = $entryId->id;

            for ($i = 9; $i < count($entry); $i++) {
                if (!array_key_exists($entry[$i], self::$labels)) {
                    // Create a new label first, if necessary.
                    if (!$newLabelStatement->execute([
                        'name' => $entry[$i]
                    ])) {
                        throw new Exception("Could not insert new label!");
                    };

                    // A new label exists, reload the labels array!
                    self::loadAllLabels();
                }

                // Create a new entrylabel row.
                $newEntryLabelStatement->execute([
                    'entryid' => $entryId,
                    'labelid' => self::$labels[$entry[$i]]
                ]);
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
        $connection = self::connect();

        $query = "
        SELECT *
          FROM label
        ";

        self::$labels = [];
        $result = $connection->query($query, PDO::FETCH_OBJ);

        foreach ($result as $label) {
            self::$labels[$label->name] = $label->id;
        }

        $connection = null;
    }
}
