<?php

namespace Plugins\accounting\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\accounting\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('creator')->latest()->paginate(20);
        return view('accounting::expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Expense::$categories;
        return view('accounting::expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|in:' . implode(',', array_keys(Expense::$categories)),
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string|max:1000',
        ]);

        Expense::create([
            'title'        => $request->title,
            'amount'       => $request->amount,
            'category'     => $request->category,
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
            'status'       => 'draft',
            'created_by'   => auth()->id(),
        ]);

        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense berhasil dibuat.');
    }

    public function show(Expense $expense)
    {
        return view('accounting::expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::$categories;
        return view('accounting::expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|in:' . implode(',', array_keys(Expense::$categories)),
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $expense->update($request->only('title', 'amount', 'category', 'expense_date', 'notes'));

        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense berhasil diupdate.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('accounting.expenses.index')
            ->with('success', 'Expense berhasil dihapus.');
    }

    public function updateStatus(Request $request, Expense $expense)
    {
        $request->validate(['status' => 'required|in:approved,rejected,draft']);
        $expense->update(['status' => $request->status]);

        $label = Expense::$statuses[$request->status]['label'];
        return back()->with('success', "Expense ditandai sebagai {$label}.");
    }
}
