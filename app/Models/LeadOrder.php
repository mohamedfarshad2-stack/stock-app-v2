<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadOrder extends Model
{
    protected $fillable = ['lead_id', 'status'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
