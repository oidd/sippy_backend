<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
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


    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: fn () => DB::select('select ST_X(?)', [$this->geom])[0]->st_x,
        );
    }

    protected function latitude(): Attribute
    {
        return Attribute::make(
            get: fn () => DB::select('select ST_Y(?)', [$this->geom])[0]->st_y,
        );
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
        if (!$this->description()->exists())
            return false; // report this incident, because point cannot exist without its description

        $description = $this->description()->first();

        if ($this->user_id === $user->id)
            return true;

        if (($description->preferable_gender !== null) && ($user->gender != $description->preferable_gender))
            return false;

        if (($description->min_preferable_age !== null) && ($user->age < $description->min_preferable_age))
            return false;

        if (($description->max_preferable_age !== null) && ($user->age > $description->max_preferable_age))
            return false;

        return true;
    }
}
