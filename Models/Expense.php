<?php

namespace Plugins\accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Expense extends Model
{
    protected $fillable = [
        'title', 'amount', 'category', 'expense_date', 'notes', 'status', 'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public static array $categories = [
        'Travel'          => 'Travel',
        'Office Supplies' => 'Office Supplies',
        'Software'        => 'Software',
        'Food & Beverage' => 'Food & Beverage',
        'Equipment'       => 'Equipment',
        'Other'           => 'Other',
    ];

    public static array $statuses = [
        'draft'    => ['label' => 'Draft',    'color' => 'secondary'],
        'approved' => ['label' => 'Approved', 'color' => 'green'],
        'rejected' => ['label' => 'Rejected', 'color' => 'red'],
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
