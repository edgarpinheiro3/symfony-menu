<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity:"App\Entity\Prato", mappedBy:"categoria")]
    private $prato;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->prato = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Prato>
     */
    public function getPrato(): Collection
    {
        return $this->prato;
    }

    public function addPrato(Prato $prato): static
    {
        if (!$this->prato->contains($prato)) {
            $this->prato->add($prato);
            $prato->setCategoria($this);
        }

        return $this;
    }

    public function removePrato(Prato $prato): static
    {
        if ($this->prato->removeElement($prato)) {
            // set the owning side to null (unless already changed)
            if ($prato->getCategoria() === $this) {
                $prato->setCategoria(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
