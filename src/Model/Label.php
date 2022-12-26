<?php

namespace ClimbingLogbook\Model;

use ValueError;

class Label
{
    private int $id;

    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        if ($id < 0) {
            throw new ValueError("Invalid id value!");
        }

        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        if (empty($name)) {
            throw new ValueError("Invalid name!");
        }

        $this->name = $name;
    }
}
