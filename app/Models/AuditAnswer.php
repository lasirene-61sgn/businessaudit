<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditAnswer extends Model
{
    protected $fillable = ['audit_id', 'question_id', 'option_id', 'notes'];

    /**
     * The master audit session parent.
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the question associated with this response.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Access the specific chosen option metadata (Score, XP, Suggestions).
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
