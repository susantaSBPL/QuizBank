<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionChoiceRepository::class)
 */
class QuestionChoice
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
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
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
     */
    public function setChoiceText(string $choiceText)
    {
        $this->choiceText = $choiceText;
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
     */
    public function setIsRightChoice(bool $isRightChoice)
    {
        $this->isRightChoice = $isRightChoice;
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
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }
}
