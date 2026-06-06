<?php

namespace Plugins\accounting;

use App\Core\MenuManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class Plugin extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'accounting');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        Route::middleware(['web', 'auth'])->group(__DIR__ . '/routes.php');

        if (app()->runningInConsole()) return;

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