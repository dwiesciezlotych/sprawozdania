<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task", indexes={@ORM\Index(name="element_id", columns={"element_id"})})
 * @ORM\Entity
 */
class Task
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
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="complete_after_end_date", type="boolean", nullable=false)
     */
    private $completeAfterEndDate;

    /**
     * @var \SectionElements
     *
     * @ORM\ManyToOne(targetEntity="SectionElements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     * })
     */
    private $element;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCompleteAfterEndDate(): ?bool
    {
        return $this->completeAfterEndDate;
    }

    public function setCompleteAfterEndDate(bool $completeAfterEndDate): self
    {
        $this->completeAfterEndDate = $completeAfterEndDate;

        return $this;
    }

    public function getElement(): ?SectionElements
    {
        return $this->element;
    }

    public function setElement(?SectionElements $element): self
    {
        $this->element = $element;

        return $this;
    }


}
