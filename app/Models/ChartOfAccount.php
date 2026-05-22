<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';
    protected $guarded = [];

    public function journalItems()
    {
        return $this->hasMany(JournalItem::class, 'account_id');
    }
}
