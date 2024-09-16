<?php

namespace App\Controller\API;

use App\Entity\Animal;
use App\Repository\AnimalRaceRepository;
use App\Repository\AnimalRepository;
use App\Repository\AnimalTypeRepository;
use App\Repository\StatutVenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalController extends AbstractController
{

    #[Route('/api/animal', name: 'api_animal', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function getAnimalById(Request $request, AnimalRepository $animalRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['id'])) {
            $animal = $animalRepository->find((int)$data['id']);
            $data = $serializer->normalize($animal, null, ['groups' => ['animal_detail']]);

            return $this->json($data, 200);
        } else {
            return $this->json(['message' => 'Données manquantes'], 400);
        }
    }

    #[Route('/api/animal/all', name: 'api_animal_all', methods: 'POST')]
    public function getAnimalAll(AnimalRepository $animalRepository, SerializerInterface $serializer): JsonResponse
    {
        $listeAnimaux = $animalRepository->findAll();
        $data = $serializer->normalize($listeAnimaux, null, ['groups' => ['animal_detail']]);

        return $this->json($data, 200);
    }

    #[Route('/api/animal/create', name: 'api_animal_create', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function createAnimal(
        Request $request,
        EntityManagerInterface $entityManager,
        AnimalTypeRepository $animalTypeRepository,
        AnimalRaceRepository $animalRaceRepository,
        StatutVenteRepository $statutVenteRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['type'], $data['race'], $data['nom'], $data['age'], $data['description'], $data['prixTTC'], $data['statut'])) {
            return $this->json(['message' => 'Données manquantes'], 400);
        }

        $type = $animalTypeRepository->find($data['type']);
        $race = $animalRaceRepository->find($data['race']);
        $statutVente = $statutVenteRepository->find($data['statut']);

        if (!$type) {
            return new JsonResponse(['message' => 'Type non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (!$race) {
            return new JsonResponse(['message' => 'Race non trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        $animal = new Animal();
        $animal->setNom($data['nom'])
            ->setAge($data['age'])
            ->setDescription($data['description'])
            ->setPrixTTC($data['prixTTC'])
            ->setType($type)
            ->setRace($race)
            ->setStatut($statutVente);

        // Persister et sauvegarder
        $entityManager->persist($animal);
        $entityManager->flush();

        $datas = $serializer->normalize($animal, null, ['groups' => ['animal_detail']]);

        return new JsonResponse(['message' => 'Animal ajouté avec succès', 'animal' => $datas], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/animal/edit', name: 'api_animal_edit', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function editAnimal(
        Request $request,
        EntityManagerInterface $entityManager,
        AnimalTypeRepository $animalTypeRepository,
        AnimalRaceRepository $animalRaceRepository,
        StatutVenteRepository $statutVenteRepository,
        AnimalRepository $animalRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['type'], $data['race'], $data['nom'], $data['age'], $data['description'], $data['prixTTC'], $data['statut'], $data['id'])) {
            return $this->json(['message' => 'Données manquantes'], 200);
        }

        $type = $animalTypeRepository->find((int)$data['type']);
        $race = $animalRaceRepository->find((int)$data['race']);
        $statut = $statutVenteRepository->find((int)$data['statut']);

        if (!$type) {
            return $this->json(['message' => 'Type non trouvé'], 400);
        }

        if (!$race) {
            return $this->json(['message' => 'Race non trouvée'], 400);
        }

        $animal = $animalRepository->find($data['id']);
        $animal->setNom($data['nom'])
            ->setAge($data['age'])
            ->setDescription($data['description'])
            ->setPrixTTC($data['prixTTC'])
            ->setType($type)
            ->setRace($race)
            ->setStatut($statut);

        $entityManager->persist($animal);
        $entityManager->flush();

        $datas = $serializer->normalize($animal, null, ['groups' => ['animal_detail']]);

        return $this->json(['message' => 'Animal mis à jour avec succès', 'animal' => $datas], 200);
    }

    #[Route('/api/animal/delete', name: 'api_animal_delete', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function supprimerAnimal(
        Request $request,
        AnimalRepository $animalRepository,
        EntityManagerInterface $entityManager
    ) {
        $data = json_decode($request->getContent(), true);

        if (isset($data['id'])) {
            $animal = $animalRepository->find((int)$data['id']);
            $entityManager->remove($animal);
            $entityManager->flush();

            return $this->json(['message' => 'Animal supprimé avec succès'], 200);
        } else {
            return $this->json(['message' => 'Erreur durant la suppression'], 400);
        }
    }
}
