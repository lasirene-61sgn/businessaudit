<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audit extends Model
{
    protected $fillable = [
        'user_id', 'business_name', 'owner_name', 'business_type', 
        'industry', 'years_in_operation', 'no_of_employees', 
        'auditor_name', 'audit_date', 'additional_notes', 
        'status', 'total_score', 'total_xp', 'pdf_path'
    ];

    /**
     * Link back to the logged-in customer panel user profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    /**
     * Fetch all submitted choices/answers for this audit attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AuditAnswer::class);
    }
}
