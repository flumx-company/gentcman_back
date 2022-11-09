<?php

use Gentcmen\Http\Interfaces\HasCoverImage;
use Gentcmen\Models\User;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Create access token for user
 *
 * @return string
 */
function getAccessToken(User $user, string $tokenName = 'Application login'): string
{
    return  $user->createToken($tokenName)->accessToken;
}

/**
 * @Note Uses only for development!!
 * Return cookie details
 * @param $token
 * @return array
 */

#[ArrayShape(['name' => "string", 'value' => "", 'minutes' => "int", 'path' => "null", 'domain' => "null", 'secure' => "null", 'httponly' => "bool", 'samesite' => "bool"])]
function getCookieDetails($token): array
{
    return [
        'name' => '_token',
        'value' => $token,
        'minutes' => 1440,
        'path' => null,
        'domain' => null,
        // 'secure' => true, // for production
        'secure' => null, // for localhost
        'httponly' => true,
        'samesite' => true,
    ];
}

/**
 * @param $value
 * @return json
 */
function convertToDbJson($value)
{
    return gettype($value) === 'string' ? json_decode($value) : $value;
}

/**
 * Get the validation rules for image files.
 */
function getImageValidationRules(): string
{
    return 'image|mimes:jpeg,png,gif,webp,svg';
}

/**
 * Convert provided class name to snake case
 * @param mixed $value
 * @return string
 */

function classNameToSnakeCase(mixed $value): string
{
    return Str::snake(class_basename($value));
}

/**
 * Assign image information to provided model
 * @param HasCoverImage $model
 * @param $imageDetails
 * @return HasCoverImage
 */

function assignImage(HasCoverImage $model, $imageDetails): HasCoverImage
{
    list('name' => $name, 'path' => $path, 'url' => $url) = $imageDetails;
    $model->name = $name;
    $model->path = $path;
    $model->url = $url;
    return $model;
}
