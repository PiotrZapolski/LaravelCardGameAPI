<?php

namespace App\Services;

use App\Models\Card;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CardsService
{
    /**
     * @return Card
     */
    public function getNewCard(): Card
    {
        $user = auth('sanctum')->user();
        $user->load(['level', 'cards']);

        if ($user->isNewCardAllowed() === false) {
            throw new AccessDeniedHttpException('You have reached the limit of cards for your level');
        }

        /** @var Card $card */
        $card = Card::query()
            ->inRandomOrder()
            ->first();

        $user->cards()->attach($card->id);

        return $user->cards()->where('cards.id', $card->id)->first();
    }
}
