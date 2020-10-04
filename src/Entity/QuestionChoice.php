<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionChoiceRepository::class)
 */
class QuestionChoice extends AbstractQuizBankEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="questionChoice", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Question $question;

    /**
     * @ORM\Column(type="string")
     */
    private string $choiceText;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isRightChoice = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive = true;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     *
     * @return $this
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string
     */
    public function getChoiceText(): string
    {
        return $this->choiceText;
    }

    /**
     * @param string $choiceText
     *
     * @return $this
     */
    public function setChoiceText(string $choiceText)
    {
        $this->choiceText = $choiceText;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRightChoice(): bool
    {
        return $this->isRightChoice;
    }

    /**
     * @param bool $isRightChoice
     *
     * @return $this
     */
    public function setIsRightChoice(bool $isRightChoice)
    {
        $this->isRightChoice = $isRightChoice;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Represent the Entity as a string.
     *
     * @return string
     */
    public function toAuditString()
    {
        // Represent the Entity as a string $s
        $s = "QuestionChoice {";
        $s = $s."}";

        return $s;
    }
}
