<?php

namespace App\Entity;

use App\Repository\OrganizationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'organizations')]
#[ORM\Entity(repositoryClass: OrganizationsRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'organization_id', type:"integer")]
    private ?int $id = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^[0-9a-zA-Z-_ áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i',
        match: true,
        message: 'Le nom doit contenir uniquement des lettres, des chiffres le tiret du milieu de l\'undescore.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $organizationName = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[Assert\Regex(
        pattern: "/^\d+\s[0-9A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/",
        match: true,
        message: 'l\'adresse peut contenir seulement des lettres majuscules, des lettres minuscules et des caractères spéciaux et numéro de la rue.',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'adresse ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $address = null;


    #[Assert\Length(
        max: 20,
        maxMessage: 'Le code postal ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/',
        match: true,
        message: 'Le code postal doit contenir que des chiffres.',
    )]
    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 20)]
    private ?string $zip = null;


    #[Assert\Regex(
        pattern: '/^[a-zA-Z-_ áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i',
        match: true,
        message: 'Le nom du Pays doit contenir uniquement des lettres et le tiret du milieu de l\'undescore.',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom du Pays ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[Assert\Length(
        max: 20,
        maxMessage: 'Le tel ne doit pas dépasser {{ limit }} caractères',
    )]

    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 20)]
    private ?string $tel = null;

    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;



    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;


    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Relations::class, orphanRemoval: true)]
    private Collection $relations;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: CustomerPhysicals::class, orphanRemoval: true)]
    private Collection $physicalCustomers;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: CustomerMorals::class, orphanRemoval: true)]
    private Collection $moralCustomers;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: CustomerMorals::class, orphanRemoval: true)]
    private Collection $customers;
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Project::class, orphanRemoval: true)]
    private Collection $projects;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $isAdmin = 0;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
        $this->physicalCustomers = new ArrayCollection();
        $this->moralCustomers = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganizationName(): ?string
    {
        return $this->organizationName;
    }

    public function setOrganizationName(string $organizationName): static
    {
        $this->organizationName = $organizationName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
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
            $relation->setOrganization($this);
        }

        return $this;
    }

    public function removeRelation(Relations $relation): static
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getOrganization() === $this) {
                $relation->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomerPhysicals>
     */
    public function getPhysicalCustomers(): Collection
    {
        return $this->physicalCustomers;
    }

    public function addPhysicalCustomer(CustomerPhysicals $physicalCustomer): static
    {
        if (!$this->physicalCustomers->contains($physicalCustomer)) {
            $this->physicalCustomers->add($physicalCustomer);
            $physicalCustomer->setOrganization($this);
        }

        return $this;
    }

    public function removePhysicalCustomer(CustomerPhysicals $physicalCustomer): static
    {
        if ($this->physicalCustomers->removeElement($physicalCustomer)) {
            // set the owning side to null (unless already changed)
            if ($physicalCustomer->getOrganization() === $this) {
                $physicalCustomer->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomerMorals>
     */
    public function getMoralCustomers(): Collection
    {
        return $this->moralCustomers;
    }

    public function addMoralCustomer(CustomerMorals $moralCustomer): static
    {
        if (!$this->moralCustomers->contains($moralCustomer)) {
            $this->moralCustomers->add($moralCustomer);
            $moralCustomer->setOrganization($this);
        }

        return $this;
    }

    public function removeMoralCustomer(CustomerMorals $moralCustomer): static
    {
        if ($this->moralCustomers->removeElement($moralCustomer)) {
            // set the owning side to null (unless already changed)
            if ($moralCustomer->getOrganization() === $this) {
                $moralCustomer->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setOrganization($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getOrganization() === $this) {
                $project->setOrganization(null);
            }
        }

        return $this;
    }

    public function getCustomer(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Project $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setOrganization($this);
        }

        return $this;
    }

    public function removeCustomer(Project $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getOrganization() === $this) {
                $customer->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @param string|null $siret
     */
    public function setSiret(?string $siret): void
    {
        $this->siret = $siret;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @param string|null $tel
     */
    public function setTel(?string $tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getIsAdmin(): ?int
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(int $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function __toString()
    {
        return $this->getOrganizationName();
    }
}
