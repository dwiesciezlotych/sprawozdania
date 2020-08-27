<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sections
 *
 * @ORM\Table(name="sections", uniqueConstraints={@ORM\UniqueConstraint(name="previous_section_id_uq", columns={"previous_section_id"})}, indexes={@ORM\Index(name="previous_section_id", columns={"previous_section_id"}), @ORM\Index(name="course_id", columns={"course_id"})})
 * @ORM\Entity
 */
class Sections
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

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
     * @var \Sections
     *
     * @ORM\ManyToOne(targetEntity="Sections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="previous_section_id", referencedColumnName="id")
     * })
     */
    private $previousSection;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPreviousSection(): ?self
    {
        return $this->previousSection;
    }

    public function setPreviousSection(?self $previousSection): self
    {
        $this->previousSection = $previousSection;

        return $this;
    }


}
