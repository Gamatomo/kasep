<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $table = 'journal_entries';
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function items()
    {
        return $this->hasMany(JournalItem::class, 'journal_entry_id');
    }
}
