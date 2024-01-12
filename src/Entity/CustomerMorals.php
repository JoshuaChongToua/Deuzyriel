<?php

namespace App\Entity;

use App\Repository\MoralCustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'customer_morals')]
#[ORM\Entity(repositoryClass: MoralCustomersRepository::class)]
class CustomerMorals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"customer_id")]
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
    private ?string $companyName = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères',
    )]
    #[ORM\Column(length: 255)]
    private ?string $companyType = null;


    #[Assert\Regex(
        pattern: '/^(?:(\d{9})|(\d{3}\s?\d{3}\s?\d{3}\s?\d{5})|(\d{3}\s?\d{3}\s?\d{5}\s?\d{2}))$/',
        match: true,
        message: 'Un numéro de Siren doit comporter au maximum 9 chiffres et un numéro de Siret 14 chiffres.',
    )]
    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(name:"contact_name",length: 255)]
    private ?string $contactName = null;

    #[ORM\Column(name:"contact_first_name",length: 255)]
    private ?string $contactFirstName = null;

    #[ORM\OneToMany(mappedBy: 'customer_morals', targetEntity: Donations::class, orphanRemoval: true)]
    private Customers $customerMoral;

    #[ORM\OneToOne(mappedBy: 'a', targetEntity: Donations::class, orphanRemoval: true)]
    private Collection $donations;

    public function __construct()
    {
        $this->donations = new ArrayCollection();
        $this->customerMoral = new Customers();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyType(): ?string
    {
        return $this->companyType;
    }

    public function setCompanyType(string $companyType): static
    {
        $this->companyType = $companyType;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    /**
     * @param string|null $contactName
     */
    public function setContactName(?string $contactName): void
    {
        $this->contactName = $contactName;
    }

    /**
     * @return string|null
     */
    public function getContactFirstName(): ?string
    {
        return $this->contactFirstName;
    }

    /**
     * @param string|null $contactFirstName
     */
    public function setContactFirstName(?string $contactFirstName): void
    {
        $this->contactFirstName = $contactFirstName;
    }




    /**
     * @return Collection<int, Donations>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donations $donation): static
    {
        if (!$this->donations->contains($donation)) {
            $this->donations->add($donation);
            $donation->setMoralCustomer($this);
        }

        return $this;
    }

    public function removeDonation(Donations $donation): static
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getMoralCustomer() === $this) {
                $donation->setMoralCustomer(null);
            }
        }

        return $this;
    }
}
