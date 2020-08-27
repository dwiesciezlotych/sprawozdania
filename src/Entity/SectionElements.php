<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SectionElements
 *
 * @ORM\Table(name="section_elements", uniqueConstraints={@ORM\UniqueConstraint(name="previous_element_id_uq", columns={"previous_element_id"})}, indexes={@ORM\Index(name="previous_element_id", columns={"previous_element_id"}), @ORM\Index(name="section_id", columns={"section_id"})})
 * @ORM\Entity
 */
class SectionElements
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
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var \SectionElements
     *
     * @ORM\ManyToOne(targetEntity="SectionElements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="previous_element_id", referencedColumnName="id")
     * })
     */
    private $previousElement;

    /**
     * @var \Sections
     *
     * @ORM\ManyToOne(targetEntity="Sections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreviousElement(): ?self
    {
        return $this->previousElement;
    }

    public function setPreviousElement(?self $previousElement): self
    {
        $this->previousElement = $previousElement;

        return $this;
    }

    public function getSection(): ?Sections
    {
        return $this->section;
    }

    public function setSection(?Sections $section): self
    {
        $this->section = $section;

        return $this;
    }


}
