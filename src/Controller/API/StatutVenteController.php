<?php

namespace App\Controller\API;

use App\Repository\StatutVenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class StatutVenteController extends AbstractController
{
    #[Route('/api/statuts', name: 'api_statuts_vente', methods: 'POST')]
    #[IsGranted(new Expression('"ROLE_ADMIN" in role_names and (is_authenticated())'))]
    public function getAllStatusVente(StatutVenteRepository $statutVenteRepository, SerializerInterface $serializer): JsonResponse
    {
        $statuts_vente = $statutVenteRepository->findAll();
        $data = $serializer->normalize($statuts_vente, null, ['groups' => ['statut_detail']]);
        return $this->json($data, 200);
    }
}
