<?php

namespace App\Models;

use Database\Factories\CourierFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $level
 * @property Carbon $registered_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'phone', 'level', 'registered_at'])]
class Courier extends Model
{
    /** @use HasFactory<CourierFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'registered_at' => 'date',
        ];
    }
}
