<?php

namespace Plugins\accounting\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_email',
        'issue_date',
        'due_date',
        'status',
        'notes',
        // Kolom integrasi: mencatat asal invoice (null = dibuat manual)
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->qty * $item->price);
    }

    /**
     * Apakah invoice ini dibuat otomatis dari plugin lain (bukan manual)?
     */
    public function isFromIntegration(): bool
    {
        return ! is_null($this->source_type);
    }
}
