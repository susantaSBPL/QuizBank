<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Entity;


/**
 * Adds functionality to  object toString().
 */
abstract class AbstractQuizBankEntity
{

    /**
     * Called to provide a string representation of the Entity to be used
     * when auditing the system.
     *
     * @return string
     */
    public function toString()
    {
        return '';
    }

    /**
     * Override to provide a string representation of the Entity to be use
     * when auditing the system.
     *
     * @return string
     */
    protected function toAuditString()
    {
        return "WARNING NEED TO IMPLEMENT ENTITY TO STRING ".get_class($this);
    }
}
