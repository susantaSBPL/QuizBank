<?php


namespace App\Client;

use JsonSerializable;
use App\Entity\QuestionType;


class ClientQuestionType implements JsonSerializable
{
    private QuestionType $questionType;

    /**
     * ClientQuestionType constructor.
     *
     * @param QuestionType $questionType
     */
    public function __construct(QuestionType $questionType)
    {
        $this->questionType = $questionType;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->questionType->getId();
    }

    /**
     * @return string
     */
    public function getQuestionType(): string
    {
        return $this->questionType->getType();
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->questionType->getIsActive();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $a = [];

        $a['id'] = $this->getId();
        $a['type'] = $this->getQuestionType();

        return $a;
    }
}