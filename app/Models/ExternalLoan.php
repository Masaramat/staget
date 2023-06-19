<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalLoan extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_name', 'applicant_bvn', 'amount_applied', 'tenor', 'repayment_type', 'tenor_type', 'amount_approved', 'tenor_approved'];
}
