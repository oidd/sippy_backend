<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Point extends Model
{
    use HasFactory, BroadcastsEvents;

    protected $fillable = [
        'geom',
        'is_house',
        'user_id',
        'chunk_id'
    ];

    protected $appends = ['longitude', 'latitude'];

    protected $hidden = ['geom'];

    public function getLongitudeAttribute()
    {
        return DB::select('select ST_X(?)', [$this->geom])[0]->st_x;
    }

    public function getLatitudeAttribute()
    {
        return DB::select('select ST_Y(?)', [$this->geom])[0]->st_y;
    }


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
