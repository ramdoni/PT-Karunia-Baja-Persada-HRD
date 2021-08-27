<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalReclas extends Model
{
    use HasFactory;

    protected $table = 'journal_reclass';
    
    public function coa()
    {
        return $this->hasOne('\App\Models\Coa','id','coa_id');
    }
}
