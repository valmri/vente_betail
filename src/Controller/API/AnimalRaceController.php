<?php

namespace App\Controller\API;

use App\Entity\AnimalRace;
use App\Repository\AnimalRaceRepository;
use App\Repository\AnimalTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalRaceController extends AbstractController
{
    #[Route('/api/race/all', name: 'api_race_all', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function getRaces(Request $request, AnimalRaceRepository $animalRaceRepository, SerializerInterface $serializer): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);
        $data = [];
        if (isset($datas['id'])) {
            $listeRaces = $animalRaceRepository->findBy(['type' => (int)$datas['id']]);
            $data = $serializer->normalize($listeRaces, null, ['groups' => ['race_detail']]);
        }
        return $this->json($data, 200);
    }

    #[Route('/api/race/create', name: 'api_race_create', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function createRace(Request $request, AnimalTypeRepository $animalTypeRepository, AnimalRaceRepository $animalRaceRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);
        if (isset($datas['type'], $datas['race'])) {
            $type = $animalTypeRepository->find((int)$datas['type']['id']);

            $race = new AnimalRace();
            $race->setNom($datas['race']['nom']);

            $entityManager->persist($race);
            $entityManager->flush();

            $type->addRace($race);

            $entityManager->persist($type);
            $entityManager->flush();

            $result = $serializer->normalize($race, null, ['groups' => ['race_detail']]);

            return $this->json(['message' => 'Race ajouté avec succès', 'race' => $result], 200);
        } else {
            return $this->json(['message' => 'Données manquantes'], 400);
        }
    }
}
