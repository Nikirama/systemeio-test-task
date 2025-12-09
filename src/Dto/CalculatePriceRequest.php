<?php

namespace App\Dto;

use App\Validator\Constraints\TaxNumberConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CalculatePriceRequest
{
    public function __construct(
        #[Assert\Type('integer')]
        public int $productId,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        /**
         * Мне сначала хотелось создать табличку TaxRate, брать оттуда данные для определённой страны и валидировать во время обработки запроса
         * Но из формулировки задания кажется валидацию надо прямо так запарно проводить)
         * Всё же как будто в реальном проекте логики, завязанной на налогах, было бы столько, что и отдельная таблица понадобилась бы и сервис(ы)
         */
        #[TaxNumberConstraint]
        public string $taxNumber,

        #[Assert\Type('string')]
        public ?string $couponCode
    ) {}
}
