<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersTasks
 *
 * @ORM\Table(name="users_tasks", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="grade_teacher_id", columns={"grade_teacher_id"}), @ORM\Index(name="task_id", columns={"task_id"})})
 * @ORM\Entity
 */
class UsersTasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=true)
     */
    private $path;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="complete_date", type="datetime", nullable=true)
     */
    private $completeDate;

    /**
     * @var float|null
     *
     * @ORM\Column(name="grade_value", type="float", precision=10, scale=0, nullable=true)
     */
    private $gradeValue;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grade_description", type="string", length=300, nullable=true)
     */
    private $gradeDescription;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="grade_create_date", type="datetime", nullable=true)
     */
    private $gradeCreateDate;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grade_teacher_id", referencedColumnName="id")
     * })
     */
    private $gradeTeacher;

    /**
     * @var \Task
     *
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private $task;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCompleteDate(): ?\DateTimeInterface
    {
        return $this->completeDate;
    }

    public function setCompleteDate(?\DateTimeInterface $completeDate): self
    {
        $this->completeDate = $completeDate;

        return $this;
    }

    public function getGradeValue(): ?float
    {
        return $this->gradeValue;
    }

    public function setGradeValue(?float $gradeValue): self
    {
        $this->gradeValue = $gradeValue;

        return $this;
    }

    public function getGradeDescription(): ?string
    {
        return $this->gradeDescription;
    }

    public function setGradeDescription(?string $gradeDescription): self
    {
        $this->gradeDescription = $gradeDescription;

        return $this;
    }

    public function getGradeCreateDate(): ?\DateTimeInterface
    {
        return $this->gradeCreateDate;
    }

    public function setGradeCreateDate(?\DateTimeInterface $gradeCreateDate): self
    {
        $this->gradeCreateDate = $gradeCreateDate;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGradeTeacher(): ?Users
    {
        return $this->gradeTeacher;
    }

    public function setGradeTeacher(?Users $gradeTeacher): self
    {
        $this->gradeTeacher = $gradeTeacher;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }


}
