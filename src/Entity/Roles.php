<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: Relations::class, orphanRemoval:true)]
    private Collection $relations;

    #[ORM\Column(length: 255)]
    private ?string $roleName = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $isAdmin = 0;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Relations>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relations $relation): static
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
            $relation->setRole($this);
        }

        return $this;
    }

    public function removeRelation(Relations $relation): static
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getRole() === $this) {
                $relation->setRole(null);
            }
        }

        return $this;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): static
    {
        $this->roleName = $roleName;

        return $this;
    }
}
