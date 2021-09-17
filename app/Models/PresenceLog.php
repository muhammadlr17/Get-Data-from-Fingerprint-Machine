<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceLog extends Model
{
    use HasFactory;

    protected $fillable = ['pin','date_time','verified','status'];
}
