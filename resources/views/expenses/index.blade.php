@extends('layouts.app')

@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('page-actions')
@can('accounting.manage')
<a href="{{ route('accounting.expenses.create') }}" class="btn btn-primary">
    <i class="ti ti-plus me-1"></i>Add Expense
</a>
@endcan
@endsection

@section('content')
<div class="card anim-fadein">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>By</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody class="anim-stagger">
                @forelse($expenses as $expense)
                @php $status = \Plugins\accounting\Models\Expense::$statuses[$expense->status]; @endphp
                <tr>
                    <td>
                        <a href="{{ route('accounting.expenses.show', $expense) }}" class="text-body fw-medium">
                            {{ $expense->title }}
                        </a>
                    </td>
                    <td><span class="badge bg-azure-lt">{{ $expense->category }}</span></td>
                    <td class="text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                    <td class="fw-medium">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $status['color'] }}-lt">{{ $status['label'] }}</span>
                    </td>
                    <td class="text-muted small">{{ $expense->creator->name ?? '-' }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('accounting.expenses.show', $expense) }}" class="dropdown-item">
                                    <i class="ti ti-eye me-2"></i>Detail
                                </a>
                                @can('accounting.manage')
                                <a href="{{ route('accounting.expenses.edit', $expense) }}" class="dropdown-item">
                                    <i class="ti ti-edit me-2"></i>Edit
                                </a>
                                @if($expense->status === 'draft')
                                <form action="{{ route('accounting.expenses.update-status', $expense) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="dropdown-item text-green">
                                        <i class="ti ti-check me-2"></i>Approve
                                    </button>
                                </form>
                                <form action="{{ route('accounting.expenses.update-status', $expense) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="dropdown-item text-red">
                                        <i class="ti ti-x me-2"></i>Reject
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('accounting.expenses.destroy', $expense) }}" method="POST"
                                      onsubmit="return confirm('Hapus expense ini?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger">
                                        <i class="ti ti-trash me-2"></i>Hapus
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada expense.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection
