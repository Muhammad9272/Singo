<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\Processors\OrchardReportProcessor;
use App\Imports\UsersPaymentImport;
use App\Jobs\DepositUserWalletJob;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportController extends Controller
{
    public function index()
    {
        $imports = Import::latest()
            ->paginate()
            ->withQueryString();

        return view('admin.imports.index', compact('imports'));
    }

    public function create()
    {
        return view('admin.imports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|numeric',
            'rate' => 'required|numeric',
            'import_file' => 'required|file',
        ]);

        try {
            $uploadedPath = $request->file('import_file')
                ->storeAs('uploads/imports', uniqid() . '.' . $request->file('import_file')->getClientOriginalExtension());

            Import::create([
                'name' => $request->name,
                'user_id' => auth()->user()->id,
                'filepath' => $uploadedPath,
                'type' => $request->type,
                'rate' => $request->rate,
                'status' => Import::IMPORT_STATUS_PENDING,
            ]);

            return redirect()->route('admin.imports.index')->with('success', 'File uploaded successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function process(Import $import)
    {
        if ($import->status !== Import::IMPORT_STATUS_PENDING || (auth()->user()->type !== 3)) {
            abort(403);
        }

        return (new OrchardReportProcessor($import))->process()->render();
    }

    public function apply(Import $import)
    {
        if ($import->status !== Import::IMPORT_STATUS_PENDING || (auth()->user()->type !== 3)) {
            abort(403);
        }

        $processor = new OrchardReportProcessor($import);

        $import->update([
            'status' => Import::IMPORT_STATUS_QUEUED
        ]);

        DepositUserWalletJob::dispatch($import, $processor, auth()->user());

        return redirect()->route('admin.imports.index')->with('success', 'Import sent to queue for further processing.');
    }

    public function download(Import $import)
    {
        if ($import->filepath && Storage::exists($import->filepath)) {
            return Storage::download($import->filepath);
        }

        abort(404);
    }

    public function log(Import $import)
    {
        if ($import->log_filepath && Storage::exists($import->log_filepath)) {
            return Storage::download($import->log_filepath);
        }

        abort(404);
    }

    public function destroy(Import $import)
    {
        $import->delete();

        return redirect()->route('admin.imports.index')->with('success', 'Import removed.');
    }
}
