<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersCourses
 *
 * @ORM\Table(name="users_courses", indexes={@ORM\Index(name="course_id", columns={"course_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class UsersCourses
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
     * @var bool
     *
     * @ORM\Column(name="is_teacher", type="boolean", nullable=false)
     */
    private $isTeacher;

    /**
     * @var \Courses
     *
     * @ORM\ManyToOne(targetEntity="Courses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsTeacher(): ?bool
    {
        return $this->isTeacher;
    }

    public function setIsTeacher(bool $isTeacher): self
    {
        $this->isTeacher = $isTeacher;

        return $this;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): self
    {
        $this->course = $course;

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


}
