<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Chunk extends Model
{
    use HasFactory;

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public static function getChunkByCoordinates(string $x, string $y)
    {
        return DB::select(
            'SELECT chunks.id as id
        FROM chunks
        WHERE ST_Intersects(chunks.geom, ST_SetSRID(ST_MakePoint(?, ?), 4326));', [$x, $y])[0]->id;
    }
}
