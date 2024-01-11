<?php

namespace App\Entity;

use App\Repository\DonationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: DonationsRepository::class)]
class Donations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column]
    private ?float $donationAmount = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[ORM\Column(length: 15)]
    private ?string $donationCurrency = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;


    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;


    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    #[ORM\ManyToOne(inversedBy: 'donations')]
    private ?Customers $customer = null;

    #[ORM\ManyToOne(inversedBy: 'donations')]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCustomer(): ?Customers
    {
        return $this->customer;
    }

    public function setCustomer(?Customers $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getDonationAmount(): ?float
    {
        return $this->donationAmount;
    }

    public function setDonationAmount(float $donationAmount): static
    {
        $this->donationAmount = $donationAmount;

        return $this;
    }

    public function getDonationCurrency(): ?string
    {
        return $this->donationCurrency;
    }

    public function setDonationCurrency(string $donationCurrency): static
    {
        $this->donationCurrency = $donationCurrency;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}
