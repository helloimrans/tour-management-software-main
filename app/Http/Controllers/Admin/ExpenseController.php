<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExpenseService;
use App\Services\TourService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;
    protected TourService $tourService;

    public function __construct(ExpenseService $expenseService, TourService $tourService)
    {
        $this->expenseService = $expenseService;
        $this->tourService = $tourService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->expenseService->datatable();
        }

        return view('admin.expense.index');
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('expense-create')) {
            abort(403, 'Unauthorized action.');
        }
        $data['tours'] = $this->tourService->getAll();
        return view('admin.expense.create', $data);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('expense-create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'integer', 'exists:tours,id'],
                'category' => ['required', 'string', 'max:191'],
                'description' => ['nullable', 'string'],
                'amount' => ['required', 'numeric', 'min:0'],
                'expense_date' => ['required', 'date'],
            ]);

            $this->expenseService->store($validatedData);

            return redirect()->route('expense.index')->with([
                'message' => 'Expense added successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to add expense. Please try again.'])->withInput();
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('expense-delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $this->expenseService->delete($id);

            return redirect()->route('expense.index')->with([
                'message' => 'Expense deleted successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete expense. Please try again.']);
        }
    }
}

