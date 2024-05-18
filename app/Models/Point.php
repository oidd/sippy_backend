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
    use HasFactory;

    protected $fillable = [
        'geom',
        'category_id',
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

    public function shouldShowToUser(User $user): bool
    {
        $description = $this->description()->first();

        if ($this->user_id === $user->id)
            return true;

//        dump([$description, $user]);

        if (($description->preferable_gender !== null) && ($user->gender != $description->preferable_gender))
            return false;

        if (($description->min_preferable_age !== null) && ($user->age < $description->min_preferable_age))
            return false;

        if (($description->max_preferable_age !== null) && ($user->age > $description->max_preferable_age))
            return false;

        return true;
    }

//    public function broadcastOn(string $event): array
//    {
//        return
//    }
}
