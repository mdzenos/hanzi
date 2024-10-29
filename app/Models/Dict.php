<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dict extends Model
{
    protected $table = 'dict';

    protected $fillable = [
        'id_hanzi',
        'hanzi',
        'pinyin',
        'hanviet',
        'mean',
        'rank',
        'sound',
        'from',
        'count'
    ];
}
