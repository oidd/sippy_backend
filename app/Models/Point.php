<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Point extends Model
{
    use HasFactory, BroadcastsEvents;

    protected $fillable = [
        'geom',
        'is_house',
        'user_id',
        'chunk_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chunk(): BelongsTo
    {
        return $this->belongsTo(Chunk::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(Points_description::class);
    }

//    public function broadcastOn(string $event): array
//    {
//        return
//    }
}
