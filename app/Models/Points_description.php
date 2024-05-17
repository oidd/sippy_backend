<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Points_description extends Model
{
    use HasFactory;

    protected $table = 'points_description';

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }

    public function shouldShowToUser(User $user): bool
    {
        if (($this->preferrable != null) && ($user->gender != $this->preferrable))
            return false;

        if (($this->min_preferable_age != null) && ($user->age < $this->min_preferable_age))
            return false;

        if (($this->max_preferable_age != null) && ($user->age > $this->max_preferable_age))
            return false;

        return true;
    }
}
