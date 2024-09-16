<?php

namespace App\Entity;

use App\Repository\AnimalTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalTypeRepository::class)]
class AnimalType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['type_detail', 'animal_detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['type_detail', 'animal_detail'])]
    private ?string $nom = null;

    /**
     * @var Collection<int, AnimalRace>
     */
    #[ORM\OneToMany(targetEntity: AnimalRace::class, mappedBy: 'type')]
    private Collection $race;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'type')]
    private Collection $animals;

    public function __construct()
    {
        $this->race = new ArrayCollection();
        $this->animals = new ArrayCollection();
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

    /**
     * @return Collection<int, AnimalRace>
     */
    public function getRace(): Collection
    {
        return $this->race;
    }

    public function addRace(AnimalRace $race): static
    {
        if (!$this->race->contains($race)) {
            $this->race->add($race);
            $race->setType($this);
        }

        return $this;
    }

    public function removeRace(AnimalRace $race): static
    {
        if ($this->race->removeElement($race)) {
            // set the owning side to null (unless already changed)
            if ($race->getType() === $this) {
                $race->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setType($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getType() === $this) {
                $animal->setType(null);
            }
        }

        return $this;
    }
}
