<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeImportService;
use App\Models\ImportLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __construct(
        protected EmployeeImportService $importService
    ) {}

    /**
     * Display the employee import form and upload history.
     */
    public function showUploadForm()
    {
        $history = ImportLog::with('runByUser')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.import-employees', compact('history'));
    }

    /**
     * Handle the file upload and execute the employee import process.
     */
    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,csv,txt',
                'max:5120', // Max 5MB
            ],
        ]);

        $file = $request->file('file');
        
        // Store uploaded file temporarily
        $filename = $file->getClientOriginalName();
        $tempPath = 'temp-imports/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->put($tempPath, file_get_contents($file->getRealPath()));
        $absolutePath = storage_path('app/private/' . $tempPath);
        if (!file_exists($absolutePath)) {
            // Laravel 10/11 compatibility fallback for storage path structure
            $absolutePath = storage_path('app/' . $tempPath);
        }

        try {
            $result = $this->importService->import($absolutePath);

            // Save ImportLog
            ImportLog::create([
                'filename' => $filename,
                'run_by_user_id' => auth()->id(),
                'rows_processed' => $result['rows_processed'],
                'created_count' => $result['created'],
                'updated_count' => $result['updated'],
                'error_count' => count($result['errors']),
                'errors' => $result['errors'],
            ]);

            // Clean up temporary file
            Storage::disk('local')->delete($tempPath);

            return redirect()->back()->with([
                'success' => 'Employee import process completed.',
                'import_results' => $result,
            ]);

        } catch (\Throwable $e) {
            // Clean up temporary file
            Storage::disk('local')->delete($tempPath);

            return redirect()->back()->withErrors([
                'file' => 'Failed to import employees: ' . $e->getMessage(),
            ]);
        }
    }
}
