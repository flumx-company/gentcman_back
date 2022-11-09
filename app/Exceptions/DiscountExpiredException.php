<?php


namespace Gentcmen\Exceptions;

use Gentcmen\Models\Discount;

class DiscountExpiredException
{
    protected $message = 'The discount is already expired.';

    /**
     * @var \Gentcmen\Models\Discount
     */
    protected $discount;

    /**
     * DiscountExpiredException constructor.
     *
     * @param  \Gentcmen\Models\Discount  $discount
     */
    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @param  \Gentcmen\Models\Discount  $discount
     * @return mixed
     */
    public static function create(Discount $discount)
    {
        return new static($discount);
    }
}
