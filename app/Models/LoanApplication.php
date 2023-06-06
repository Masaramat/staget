<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = ['amount_applied', 'tenor', 'repayment_type', 'tenor_type', 'amount_approved', 'tenor_approved'];
}
