<?php

namespace ClimbingLogbook\Model;

use ValueError;

/**
 * A class representation of the database table 'entry'.
 */
class Entry
{
    /**
     * The entry's database table ID.
     *
     * @var integer
     */
    private int $id;

    /**
     * The date of the entry.
     *
     * @var string
     */
    private string $date;

    /**
     * The grade of the climb
     *
     * @var string
     */
    private string $grade;

    /**
     * The internal index of the grade
     *
     * @var integer
     */
    private int $gradeIndex;

    /**
     * The climb's type: Boulder, Sport climb
     *
     * @var string
     */
    private string $climbType;

    /**
     * The ascent type: Onsight, Flash, Redpoint
     *
     * @var string
     */
    private string $ascentType;

    /**
     * The number of attempts to send the climb
     *
     * @var integer
     */
    private int $attempts;

    /**
     * The wall type: Slab, Vert, Overhang, Roof
     *
     * @var string
     */
    private string $wallType;

    /**
     * The climb's name
     *
     * @var string
     */
    private string $climbName;

    /**
     * Comments on the climb
     *
     * @var string
     */
    private string $details;

    /**
     * Construct a new Entry object
     *
     * @param int $id
     * @param string $date
     * @param string $grade
     * @param int $gradeIndex
     * @param string $climbType
     * @param string $ascentType
     * @param int $attempts
     * @param string $wallType
     * @param string $climbName
     * @param string $details
     */
    public function __construct(
        $id,
        $date,
        $grade,
        $gradeIndex,
        $climbType,
        $ascentType,
        $attempts,
        $wallType,
        $climbName,
        $details
    ) {
        $this->setId($id);
        $this->setDate($date);
        $this->setGrade($grade);
        $this->setGradeIndex($gradeIndex);
        $this->setClimbType($climbType);
        $this->setAscentType($ascentType);
        $this->setAttempts($attempts);
        $this->setWallType($wallType);
        $this->setClimbName($climbName);
        $this->setDetails($details);
    }

    /**
     * Get the value of the attribute 'id'.
     *
     * @return void
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of the attribute 'id'.
     *
     * @param integer $id
     * @return void
     */
    public function setId(int $id)
    {
        if ($id < 0) {
            throw new ValueError("id must be greater than 0!");
        }
        $this->id = $id;
    }

    /**
     * Get the value of the attribute 'date'.
     *
     * @return void
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of the attribute 'date'.
     *
     * @param string $date
     * @return void
     */
    public function setDate(string $date)
    {
        if (empty($date)) {
            throw new ValueError("Please enter a valid date!");
        }

        $this->date = $date;
    }

    /**
     * Get the value of the attribute 'grade'
     *
     * @return void
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set the value of the attribute 'grade'
     *
     * @param string $grade
     * @return void
     */
    public function setGrade(string $grade)
    {
        if (empty($grade)) {
            throw new ValueError("Invalid grade!");
        }

        $this->grade = $grade;
    }

    /**
     * Get the value of the attribute 'gradeIndex'
     *
     * @return void
     */
    public function getGradeIndex()
    {
        return $this->gradeIndex;
    }

    /**
     * Set the value of the attribute 'gradeIndex'
     *
     * @param integer $gradeIndex
     * @return void
     */
    public function setGradeIndex(int $gradeIndex)
    {
        if ($gradeIndex < 0) {
            throw new ValueError("Invalid gradeIndex value!");
        }

        $this->gradeIndex = $gradeIndex;
    }
    
    /**
     * Get the value of the attribute 'climbType'
     *
     * @return string
     */
    public function getClimbType(): string
    {
        return $this->climbType;
    }

    public function setClimbType(string $climbType)
    {
        if (empty($climbType)) {
            return new ValueError("Invalid climb type value!");
        }

        $this->climbType = $climbType;
    }

    /**
     * Get the value of the attribute 'ascentType'
     *
     * @return void
     */
    public function getAscentType()
    {
        return $this->ascentType;
    }

    /**
     * Set the value of the attribute 'ascentType'
     *
     * @param string $ascentType
     * @return void
     */
    public function setAscentType(string $ascentType)
    {
        if (empty($ascentType)) {
            throw new ValueError("Invalid ascent type!");
        }

        $this->ascentType = $ascentType;
    }

    /**
     * Get the value of the attribute 'attempts'
     *
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * Set the value of the attribute 'attempts'
     *
     * @param integer $attempts
     * @return void
     */
    public function setAttempts(int $attempts = 1)
    {
        if ($attempts < 0) {
            throw new ValueError("Invalid attempts value!");
        }
        $this->attempts = $attempts;
    }

    public function getWallType()
    {
        return $this->wallType;
    }

    public function setWallType(string $wallType)
    {
        if (empty($wallType)) {
            throw new ValueError("Invalid wall type!");
        }

        $this->wallType = $wallType;
    }

    public function getClimbName()
    {
        return $this->climbName;
    }

    public function setClimbName(string $climbName)
    {
        if (empty($climbName)) {
            throw new ValueError("Invalid climb name!");
        }

        $this->climbName = $climbName;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails(string $details)
    {
        $this->details = $details;
    }
}
