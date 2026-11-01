<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourierRequest;
use App\Http\Requests\UpdateCourierRequest;
use App\Models\Courier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'level' => [
                'sometimes',
                'string',
                'regex:/^[1-5](,[1-5])*$/',
            ],
            'sort' => ['sometimes', Rule::in(['name', 'registered_at'])],
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
        ]);

        $query = Courier::query();

        if (isset($filters['search'])) {
            $terms = preg_split('/\s+/', trim($filters['search']), flags: PREG_SPLIT_NO_EMPTY);

            foreach ($terms ?: [] as $term) {
                $query->where('name', 'like', "%{$term}%");
            }
        }

        if (isset($filters['level'])) {
            $query->whereIn('level', array_map('intval', explode(',', $filters['level'])));
        }

        $sort = $filters['sort'] ?? 'name';
        $direction = $sort === 'registered_at' ? 'desc' : 'asc';

        return response()->json(
            $query
                ->orderBy($sort, $direction)
                ->orderBy('id')
                ->paginate($filters['per_page'] ?? 15)
                ->withQueryString(),
        );
    }

    public function store(StoreCourierRequest $request): JsonResponse
    {
        $courier = Courier::create($request->validated());

        return response()->json(['data' => $courier], 201);
    }

    public function show(Courier $courier): JsonResponse
    {
        return response()->json(['data' => $courier]);
    }

    public function update(UpdateCourierRequest $request, Courier $courier): JsonResponse
    {
        $courier->update($request->validated());

        return response()->json(['data' => $courier->fresh()]);
    }

    public function destroy(Courier $courier): JsonResponse
    {
        $courier->delete();

        return response()->json(status: 204);
    }
}
