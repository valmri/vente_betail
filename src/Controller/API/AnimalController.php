<?php

namespace App\Controller\API;

use App\Entity\Animal;
use App\Entity\AnimalPhoto;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        $nom = $request->get('nom');
        $age = $request->get('age');
        $description = $request->get('description');
        $prixTTC = $request->get('prixTTC');
        $type = $request->get('type');
        $race = $request->get('race');
        $statut = $request->get('statut');

        $type = $animalTypeRepository->find((int)$type);
        $race = $animalRaceRepository->find((int)$race);
        $statut = $statutVenteRepository->find((int)$statut);

        if (!$type) {
            return $this->json(['message' => 'Type non trouvé'], 400);
        }

        if (!$race) {
            return $this->json(['message' => 'Race non trouvée'], 400);
        }

        $animal = new Animal();
        $animal->setNom($nom)
            ->setAge($age)
            ->setDescription($description)
            ->setPrixTTC($prixTTC)
            ->setType($type)
            ->setRace($race)
            ->setStatut($statut);

        $entityManager->persist($animal);
        $entityManager->flush();

        /** @var UploadedFile[] $photos */
        $photos = $request->files->get('photos');
        if ($photos) {
            foreach ($photos as $photoFile) {
                $photo = new AnimalPhoto();
                $fileName = uniqid() . '.' . $photoFile->guessExtension();

                $photoFile->move($this->getParameter('photos_directory'), $fileName);

                $photo->setNom($fileName);
                $animal->addPhoto($photo);
            }
        }

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

        $id = $request->get('id');
        $nom = $request->get('nom');
        $age = $request->get('age');
        $description = $request->get('description');
        $prixTTC = $request->get('prixTTC');
        $type = $request->get('type');
        $race = $request->get('race');
        $statut = $request->get('statut');

        $animal = $animalRepository->find((int)$id);
        if (!$animal) {
            return $this->json(['message' => 'Animal non trouvée'], 400);
        }

        $type = $animalTypeRepository->find((int)$type);
        $race = $animalRaceRepository->find((int)$race);
        $statut = $statutVenteRepository->find((int)$statut);

        if (!$type) {
            return $this->json(['message' => 'Type non trouvé'], 400);
        }

        if (!$race) {
            return $this->json(['message' => 'Race non trouvée'], 400);
        }

        $animal->setNom($nom)
            ->setAge($age)
            ->setDescription($description)
            ->setPrixTTC($prixTTC)
            ->setType($type)
            ->setRace($race)
            ->setStatut($statut);

        /** @var UploadedFile[] $photos */
        $photos = $request->files->get('photos');
        if ($photos) {
            foreach ($photos as $photoFile) {
                $photo = new AnimalPhoto();
                $fileName = uniqid() . '.' . $photoFile->guessExtension();

                $photoFile->move($this->getParameter('photos_directory'), $fileName);

                $photo->setNom($fileName);
                $animal->addPhoto($photo);
            }
        }

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
