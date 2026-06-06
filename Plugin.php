<?php

namespace Plugins\accounting;

use App\Core\MenuManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class Plugin extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'accounting');

        Route::middleware(['web', 'auth'])->group(__DIR__ . '/routes.php');

        if (app()->runningInConsole()) return;

        // ─── EVENT LISTENERS ──────────────────────────────────────────────────
        //
        // Accounting mendaftarkan listener ke event dari Purchasing.
        // Logika sama: class_exists() sebagai guard — kalau Purchasing
        // tidak diinstall, listener ini tidak akan pernah terdaftar.
        //
        // Ini berarti Accounting bisa berdiri sendiri tanpa Purchasing,
        // tapi kalau keduanya aktif, integrasi berjalan otomatis.

        if (class_exists(\Plugins\purchasing\Events\PurchaseOrderReceived::class)) {
            Event::listen(
                \Plugins\purchasing\Events\PurchaseOrderReceived::class,
                \Plugins\accounting\Listeners\CreateApInvoiceFromPo::class
            );
        }

        // ─────────────────────────────────────────────────────────────────────

        $this->app->booted(function () {
            $this->app->make(MenuManager::class)->add([
                'title'      => 'Accounting',
                'url'        => route('accounting.invoices.index'),
                'icon'       => 'ti ti-file-invoice',
                'order'      => 20,
                'active'     => 'accounting*',
                'permission' => 'accounting.view',
                'children'   => [
                    [
                        'title'      => 'Invoices',
                        'icon'       => 'ti ti-file-invoice',
                        'active'     => 'accounting/invoices*',
                        'permission' => 'accounting.view',
                        'children'   => [
                            ['title' => 'All Invoices',    'url' => route('accounting.invoices.index'),  'active' => 'accounting/invoices'],
                            ['title' => 'Create Invoice',  'url' => route('accounting.invoices.create'), 'active' => 'accounting/invoices/create', 'permission' => 'accounting.manage'],
                        ],
                    ],
                    [
                        'title'      => 'Expenses',
                        'icon'       => 'ti ti-receipt',
                        'active'     => 'accounting/expenses*',
                        'permission' => 'accounting.view',
                        'children'   => [
                            ['title' => 'All Expenses',   'url' => route('accounting.expenses.index'),  'active' => 'accounting/expenses'],
                            ['title' => 'Create Expense', 'url' => route('accounting.expenses.create'), 'active' => 'accounting/expenses/create', 'permission' => 'accounting.manage'],
                        ],
                    ],
                ],
            ]);
        });
    }
}
