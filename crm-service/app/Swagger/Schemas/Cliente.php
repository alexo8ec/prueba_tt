<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Cliente",
 *     type="object",
 *     required={"nombre","apellido","email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan"),
 *     @OA\Property(property="apellido", type="string", example="Pérez"),
 *     @OA\Property(property="email", type="string", example="juan@email.com"),
 *     @OA\Property(property="telefono", type="string", example="0999999999")
 * )
 */