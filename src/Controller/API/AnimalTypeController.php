<?php

namespace App\Controller\API;

use App\Entity\AnimalType;
use App\Repository\AnimalTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalTypeController extends AbstractController
{
    #[Route('/api/types', name: 'api_animal_types', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function getTypes(AnimalTypeRepository $animalTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $listeTypes = $animalTypeRepository->findAll();
        $data = $serializer->normalize($listeTypes, null, ['groups' => ['type_detail']]);

        return $this->json($data, 200);
    }

    #[Route('/api/type/create', name: 'api_type_create', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function createRace(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);
        if (isset($datas['type'])) {

            $type = new AnimalType();
            $type->setNom($datas['type']['nom']);

            $entityManager->persist($type);
            $entityManager->flush();

            $result = $serializer->normalize($type, null, ['groups' => ['type_detail']]);

            return $this->json(['message' => 'Race ajouté avec succès', 'type' => $result], 200);
        } else {
            return $this->json(['message' => 'Données manquantes'], 400);
        }
    }
}
