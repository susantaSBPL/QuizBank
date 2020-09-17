<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserQuestionAnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=UserQuestionAnswerRepository::class)
 */
class UserQuestionAnswer extends AbstractQuizBankEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userQuestionAnswer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="userQuestionAnswer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Question $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionChoice", inversedBy="userQuestionAnswer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private QuestionChoice $questionChoice;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isRightChoice = false;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
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
     * @return QuestionChoice
     */
    public function getQuestionChoice(): QuestionChoice
    {
        return $this->questionChoice;
    }

    /**
     * @param QuestionChoice $questionChoice
     *
     * @return $this
     */
    public function setQuestionChoice(QuestionChoice $questionChoice)
    {
        $this->questionChoice = $questionChoice;

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
        $s = "UserQuestionAnswer {";
        $s = $s."}";

        return $s;
    }
}
