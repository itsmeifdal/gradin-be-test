<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Courier API',
    description: 'REST API for managing courier master data.',
)]
#[OA\Server(url: '/', description: 'Current application server')]
#[OA\Tag(name: 'Couriers', description: 'Courier master data')]
#[OA\Schema(
    schema: 'Courier',
    type: 'object',
    required: ['id', 'name', 'phone', 'level', 'registered_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Budiono Hadi Agung'),
        new OA\Property(property: 'phone', type: 'string', example: '081234567890'),
        new OA\Property(property: 'level', type: 'integer', minimum: 1, maximum: 5, example: 2),
        new OA\Property(property: 'registered_at', type: 'string', format: 'date', example: '2026-11-01'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'CourierPayload',
    type: 'object',
    required: ['name', 'phone', 'level', 'registered_at'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Budiono Hadi Agung'),
        new OA\Property(property: 'phone', type: 'string', maxLength: 20, example: '081234567890'),
        new OA\Property(property: 'level', type: 'integer', minimum: 1, maximum: 5, example: 2),
        new OA\Property(property: 'registered_at', type: 'string', format: 'date', example: '2026-11-01'),
    ],
)]
#[OA\Schema(
    schema: 'CourierResource',
    type: 'object',
    required: ['data'],
    properties: [new OA\Property(property: 'data', ref: '#/components/schemas/Courier')],
)]
#[OA\Schema(
    schema: 'CourierPagination',
    type: 'object',
    required: ['current_page', 'data', 'per_page', 'total'],
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Courier'),
        ),
        new OA\Property(property: 'per_page', type: 'integer', example: 15),
        new OA\Property(property: 'total', type: 'integer', example: 30),
    ],
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    type: 'object',
    properties: [new OA\Property(property: 'message', type: 'string', example: 'Not Found')],
)]
#[OA\Schema(
    schema: 'ValidationError',
    type: 'object',
    required: ['message', 'errors'],
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string'),
            ),
        ),
    ],
)]
#[OA\Get(
    path: '/api/couriers',
    operationId: 'listCouriers',
    summary: 'List couriers',
    tags: ['Couriers'],
    parameters: [
        new OA\QueryParameter(
            name: 'search',
            description: 'Search the courier name. Every term must match.',
            schema: new OA\Schema(type: 'string', example: 'budi agung'),
        ),
        new OA\QueryParameter(
            name: 'level',
            description: 'Comma-separated courier levels.',
            schema: new OA\Schema(type: 'string', pattern: '^[1-5](,[1-5])*$'),
            example: '2,3',
        ),
        new OA\QueryParameter(
            name: 'sort',
            description: 'Sort by name (ascending) or registration date (newest first).',
            schema: new OA\Schema(type: 'string', enum: ['name', 'registered_at'], default: 'name'),
        ),
        new OA\QueryParameter(
            name: 'per_page',
            description: 'Items per page.',
            schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 15),
        ),
        new OA\QueryParameter(
            name: 'page',
            description: 'Page number.',
            schema: new OA\Schema(type: 'integer', minimum: 1, default: 1),
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Paginated courier list',
            content: new OA\JsonContent(ref: '#/components/schemas/CourierPagination'),
        ),
        new OA\Response(
            response: 422,
            description: 'Invalid query parameters',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
        ),
    ],
)]
#[OA\Post(
    path: '/api/couriers',
    operationId: 'createCourier',
    summary: 'Create a courier',
    tags: ['Couriers'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/CourierPayload'),
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Courier created',
            content: new OA\JsonContent(ref: '#/components/schemas/CourierResource'),
        ),
        new OA\Response(
            response: 422,
            description: 'Validation error',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
        ),
    ],
)]
#[OA\Get(
    path: '/api/couriers/{courier}',
    operationId: 'showCourier',
    summary: 'Show a courier',
    tags: ['Couriers'],
    parameters: [new OA\PathParameter(
        name: 'courier',
        description: 'Courier ID',
        schema: new OA\Schema(type: 'integer', minimum: 1),
    )],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Courier detail',
            content: new OA\JsonContent(ref: '#/components/schemas/CourierResource'),
        ),
        new OA\Response(
            response: 404,
            description: 'Courier not found',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
        ),
    ],
)]
#[OA\Put(
    path: '/api/couriers/{courier}',
    operationId: 'replaceCourier',
    summary: 'Update a courier',
    tags: ['Couriers'],
    parameters: [new OA\PathParameter(
        name: 'courier',
        description: 'Courier ID',
        schema: new OA\Schema(type: 'integer', minimum: 1),
    )],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/CourierPayload'),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Courier updated',
            content: new OA\JsonContent(ref: '#/components/schemas/CourierResource'),
        ),
        new OA\Response(
            response: 404,
            description: 'Courier not found',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
        ),
        new OA\Response(
            response: 422,
            description: 'Validation error',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
        ),
    ],
)]
#[OA\Patch(
    path: '/api/couriers/{courier}',
    operationId: 'updateCourier',
    summary: 'Update a courier',
    description: 'All courier fields are required.',
    tags: ['Couriers'],
    parameters: [new OA\PathParameter(
        name: 'courier',
        description: 'Courier ID',
        schema: new OA\Schema(type: 'integer', minimum: 1),
    )],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/CourierPayload'),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Courier updated',
            content: new OA\JsonContent(ref: '#/components/schemas/CourierResource'),
        ),
        new OA\Response(
            response: 404,
            description: 'Courier not found',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
        ),
        new OA\Response(
            response: 422,
            description: 'Validation error',
            content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
        ),
    ],
)]
#[OA\Delete(
    path: '/api/couriers/{courier}',
    operationId: 'deleteCourier',
    summary: 'Delete a courier',
    tags: ['Couriers'],
    parameters: [new OA\PathParameter(
        name: 'courier',
        description: 'Courier ID',
        schema: new OA\Schema(type: 'integer', minimum: 1),
    )],
    responses: [
        new OA\Response(response: 204, description: 'Courier deleted'),
        new OA\Response(
            response: 404,
            description: 'Courier not found',
            content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
        ),
    ],
)]
final class CourierApiDocumentation
{
    // OpenAPI metadata only. Runtime API logic remains in CourierController.
}
