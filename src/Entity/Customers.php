<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Table(name: 'customers')]
#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'customer_id', type:"integer")]
    private ?int $id = null;

    #[Assert\NotNull(message:"Vous devez faire un choix dans la liste.")]
    #[ORM\Column(type:"string", columnDefinition:"ENUM('physical', 'moral')")]
    private ?string $customerType = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(name: 'organization_id', referencedColumnName: 'organization_id')]
    private ?Organization $organization = null;

    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[Assert\Length(
        max: 180,
        maxMessage: 'L\'email ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Email(
        message: 'l\'email {{ value }} n\'est pas valide.',
    )]
    #[ORM\Column(length: 180,unique:true)]
    private ?string $email = null;

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
        message: 'Le nom de la ville doit contenir uniquement des lettres et le tiret du milieu de l\'undescore.',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom de la ville ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

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


    private ?string $tel = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;


    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotNull(message:"Indiquez si vous demeuré à l'adresse inscrite.")]
    #[ORM\Column]
    private ?bool $isNPAI = null;

    #[ORM\OneToMany(mappedBy: 'customers', targetEntity: Donations::class, orphanRemoval: true)]
    private Collection $donations;

    public function __construct()
    {
        $this->donations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getCustomerType(): ?string
    {
        return $this->customerType;
    }

    /**
     * @param string|null $type
     */
    public function setCustomerType(?string $type): void
    {
        $this->customerType = $type;
    }

    /**
     * @return int|null
     */
    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
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
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
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
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    public function isIsNPAI(): ?bool
    {
        return $this->isNPAI;
    }

    public function setIsNPAI(bool $isNPAI): static
    {
        $this->isNPAI = $isNPAI;

        return $this;
    }
    /**
     * @return Collection
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    /**
     * @param Collection $donations
     */
    public function setDonations(Collection $donations): void
    {
        $this->donations = $donations;
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