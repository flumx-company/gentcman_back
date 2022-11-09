<?php


namespace Gentcmen\Http\Services;


use Gentcmen\Models\Discount;
use Illuminate\Database\Eloquent\Model;

class Discounts
{
    /**
     * Create discounts.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  int  $quantity
     * @param  int  $amount
     * @param \Illuminate\Support\Carbon|null $expires_at
     * @return array
     */
    public function create(Model $model, int $quantity, int $amount, \Illuminate\Support\Carbon $expires_at = null): array
    {
        return array_map(function () use ($model, $amount, $expires_at) {
            return Discount::create([
                'discountable_id' => $model->getKey(),
                'discountable_type' => $model->getMorphClass(),
                'amount' => $amount,
                'expires_at' => $expires_at,
            ]);
        }, range(1, $quantity));
    }
}
