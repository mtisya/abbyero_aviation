<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLogbookAnalysis extends Model
{
    protected $table = 'ai_logbook_analysis';

    protected $primaryKey = 'id';

    public $timestamps = false; // since you only have created_at

    protected $fillable = [
        'logbook_id',
        'summary',
        'feedback',
        'flags',
        'created_at'
    ];
}