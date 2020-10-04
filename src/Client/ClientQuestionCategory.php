<?php


namespace App\Client;

use JsonSerializable;
use App\Entity\QuestionCategory;

class ClientQuestionCategory implements JsonSerializable
{
    private QuestionCategory $questionCategory;

    /**
     * ClientQuestionCategory constructor.
     *
     * @param QuestionCategory $questionCategory
     */
    public function __construct(QuestionCategory $questionCategory)
    {
        $this->questionCategory = $questionCategory;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->questionCategory->getId();
    }

    /**
     * @return string
     */
    public function getQuestionCategory(): string
    {
        return $this->questionCategory->getCategory();
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->questionCategory->getIsActive();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $a = [];

        $a['id']       = $this->getId();
        $a['category'] = $this->getQuestionCategory();

        return $a;
    }
}