<?php

namespace ClimbingLogbook\Controller;

use Exception;
use PDO;

/**
 * Handles communication with the database.
 */
class DatabaseController
{
    /**
     * Connect to the database.
     *
     * @return PDO A connection to the database.
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
     * Close the database connection.
     *
     * @param PDO $connection The database connection
     * @return void
     */
    private static function close(PDO &$connection): void
    {
        $connection = null;
    }

    /**
     * Insert values into a database table
     *
     * @param object $entry
     * @return void
     */
    public static function insert(object $entry)
    {
        switch (get_class($entry)) {
            case 'ClimbingLogbook\\Model\\Entry':
                $table = 'entry';
                $columns = [
                    'date',
                    'grade',
                    'gradeindex',
                    'climbtype',
                    'ascenttype',
                    'attempts',
                    'walltype',
                    'climbname',
                    'details'
                ];
                $values = [
                    $entry->getDate(),
                    $entry->getGrade(),
                    $entry->getGradeIndex(),
                    $entry->getClimbType(),
                    $entry->getAscentType(),
                    $entry->getAttempts(),
                    $entry->getWallType(),
                    $entry->getClimbName(),
                    $entry->getDetails(),
                ];
                break;
            case 'ClimbingLogbook\\Model\\Label':
                $table = 'label';
                $columns = ['name'];
                $values = [
                    $entry->getName(),
                ];
                break;
            case 'ClimbingLogbook\\Model\\EntryLabels':
                $table = 'entrylabels';
                $columns = [
                    'entryid',
                    'labelid',
                ];
                $values = [
                    $entry->getEntryId(),
                    $entry->getLabelId(),
                ];
        }

        $sql = "
        INSERT INTO " . $table . "(";

        for ($i = 0; $i < count($columns) - 1; $i++) {
            $sql .= $columns[$i] . ",";
        }

        $sql .= $columns[count($columns) - 1] . ") VALUES (";

        for ($i = 0; $i < count($values)-1; $i++) {
            $sql .= '?,';
        }

        $sql .= '?)';

        $connection = self::connect();

        $preparedStatement = $connection->prepare($sql);

        $result = $preparedStatement->execute($values);
        self::close($connection);

        if (!$result) {
            throw new Exception("Could not insert into database table!");
        }
    }

    /**
     * Update a table.
     *
     * @param string $table The table's name
     * @param array $columns The columns to be updated
     * @param array $values The values the columns will be updated with
     * @return void
     */
    public static function update(string $table, array $columns, array $values)
    {
        $sql = "
        UPDATE " . $table . "
           SET ";
    }

    /**
     * Select rows from the table.
     *
     * @param string $table The name of the table to select from.
     * @param array $columns The columns to return.
     * @param array $where The content of the WHERE clause
     * @return object
     */
    public static function select(string $table, array $columns, array $where): object
    {
        $connection = self::connect();

        $sql = "
        SELECT ";

        if (count($columns) === 0) {
            $sql .= '*';
        } else {
            for ($i = 0; $i < count($columns) - 1; $i++) {
                $sql .= $columns[$i] . ', ';
            }

            $sql .= $columns[count($columns) - 1];
        }

        $sql .= " FROM " . $table;

        if (count($where) > 0) {
            // TODO
        }

        $statement = $connection->query($sql);
       
        if (!$statement) {
            self::close($connection);
            throw new Exception("Could not query SQL statement!");
        }

        if (!$statement->execute()) {
            self::close($connection);
            throw new Exception("Could not execute the SQL statement!");
        }

        self::close($connection);
        return $statement;
    }
}
