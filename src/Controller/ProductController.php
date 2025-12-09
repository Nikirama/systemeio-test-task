<?php

namespace App\Controller;

use App\Attribute\MapRequestPayload;
use App\Dto\CalculatePriceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(#[MapRequestPayload] CalculatePriceRequest $request): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'message' => 'Test'
        ]);
    }
}
