<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'phone'];

    public function orders()
    {
        return $this->hasMany(LeadOrder::class);
    }
}
