<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question extends AbstractQuizBankEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionCategory", inversedBy="question", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private QuestionCategory $questionCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionType", inversedBy="question", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private QuestionType $questionType;

    /**
     * @ORM\Column(type="string")
     */
    private string $questionText;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive = true;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private DateTime $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return QuestionCategory
     */
    public function getQuestionCategory(): QuestionCategory
    {
        return $this->questionCategory;
    }

    /**
     * @param QuestionCategory $questionCategory
     *
     * @return $this
     */
    public function setQuestionCategory(QuestionCategory $questionCategory)
    {
        $this->questionCategory = $questionCategory;

        return $this;
    }

    /**
     * @return QuestionType
     */
    public function getQuestionType(): QuestionType
    {
        return $this->questionType;
    }

    /**
     * @param QuestionType $questionType
     *
     * @return $this
     */
    public function setQuestionType(QuestionType $questionType)
    {
        $this->questionType = $questionType;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    /**
     * @param string $questionText
     *
     * @return $this
     */
    public function setQuestionText(string $questionText)
    {
        $this->questionText = $questionText;

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
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

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
        $s = "Question {";
        $s = $s."}";

        return $s;
    }
}
