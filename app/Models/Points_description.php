<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Points_description extends Model
{
    use HasFactory;

    protected $table = 'points_description';

    protected $fillable = [
        'point_id',
        'preferable_gender',
        'starts_at',
        'min_preferable_age',
        'max_preferable_age',
    ];

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }
}
