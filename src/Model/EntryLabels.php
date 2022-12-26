<?php

namespace ClimbingLogbook\Model;

use ValueError;

class EntryLabels
{
    private int $entryid;

    private int $labelid;

    public function __construct(int $entryid, int $labelid)
    {
        $this->setEntryId($entryid);
        $this->setLabelId($labelid);
    }

    public function getEntryId()
    {
        return $this->entryid;
    }

    public function setEntryId(int $entryid)
    {
        if ($entryid < 0) {
            throw new ValueError("Invalid entry ID value!");
        }

        $this->entryid = $entryid;
    }

    public function getLabelId()
    {
        return $this->labelid;
    }

    public function setLabelId(int $labelid)
    {
        if ($labelid < 0) {
            throw new ValueError("Invalid label ID value!");
        }

        $this->labelid = $labelid;
    }
}
