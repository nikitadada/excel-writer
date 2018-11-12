<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document
 */
class Data
{
    /**
     * @Mongo\Id(strategy="INCREMENT")
     */
    protected $id;

    /**
     * @var \DateTime
     * @Mongo\Field(type="date")
     */
    protected $date;

    /**
     * @Mongo\Field(type="float")
     */
    protected $value;

    /**
     * @Mongo\Field(type="float")
     */
    protected $fee;


    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }


}