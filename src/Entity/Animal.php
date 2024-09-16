<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal_detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal_detail'])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(['animal_detail'])]
    private ?int $age = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['animal_detail'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['animal_detail'])]
    private ?float $prixTTC = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[Groups(['animal_detail'])]
    private ?AnimalType $type = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[Groups(['animal_detail'])]
    private ?StatutVente $statut = null;

    /**
     * @var Collection<int, AnimalPhoto>
     */
    #[ORM\OneToMany(targetEntity: AnimalPhoto::class, mappedBy: 'animal', cascade: ['persist'])]
    #[Groups(['animal_detail'])]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[Groups(['animal_detail'])]
    private ?AnimalRace $race = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixTTC(): ?float
    {
        return $this->prixTTC;
    }

    public function setPrixTTC(float $prixTTC): static
    {
        $this->prixTTC = $prixTTC;

        return $this;
    }

    public function getType(): ?AnimalType
    {
        return $this->type;
    }

    public function setType(?AnimalType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?StatutVente
    {
        return $this->statut;
    }

    public function setStatut(?StatutVente $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, AnimalPhoto>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(AnimalPhoto $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setAnimal($this);
        }

        return $this;
    }

    public function removePhoto(AnimalPhoto $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnimal() === $this) {
                $photo->setAnimal(null);
            }
        }

        return $this;
    }

    public function getRace(): ?AnimalRace
    {
        return $this->race;
    }

    public function setRace(?AnimalRace $race): static
    {
        $this->race = $race;

        return $this;
    }
}
