<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class Categories
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
     * @ORM\OneToMany(targetEntity="Courses", mappedBy="category")
     */
    private $courses;
    
    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Use at your own risk!
     * @param int $id
     * @return \self
     */
    public function setId(int $id): self{
        $this->id = $id;
        
        return $this;
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

    /**
     * @return Collection|Courses[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }
    
    public function setCourses(ArrayCollection $courses): self
    {
        $this->courses = $courses;
        
        return $this;
    }
}
