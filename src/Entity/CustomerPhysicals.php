<?php

namespace App\Entity;

use App\Repository\PhysicalCustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Table(name: 'customer_physicals')]

#[ORM\Entity(repositoryClass: PhysicalCustomersRepository::class)]
class CustomerPhysicals extends CustomerType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "customer_id")]
    private ?int $id = null;


    #[Assert\NotBlank(message:"Ce champs est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le prénom ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^[0-9a-zA-Z-_ áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i',
        match: true,
        message: 'Le prénom doit contenir uniquement des lettres, des chiffres le tiret du milieu de l\'undescore.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;


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
    private ?string $lastName = null;

    #[ORM\Column(name: "birthdate" ,type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;


    #[Assert\NotNull(message:"Vous devez faire un choix dans la liste.")]
    #[ORM\Column(type:"string", columnDefinition:"ENUM('male', 'female', 'non-binary', 'other')")]
    private ?string $gender = null;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {

    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getDateBirthday(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setDateBirthday(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCustomer(): Customers
    {
        return $this->customers;
    }


}
