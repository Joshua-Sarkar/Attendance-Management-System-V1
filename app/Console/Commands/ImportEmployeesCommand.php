<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmployeeImportService;

class ImportEmployeesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import employees and profiles from a .xlsx/csv file';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected EmployeeImportService $importService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        try {
            $result = $this->importService->import($filePath);
        } catch (\Exception $e) {
            $this->error("Failed to import employees: " . $e->getMessage());
            return 1;
        }

        $this->info("Import process complete.");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Rows Processed', $result['rows_processed']],
                ['Users Created', $result['created']],
                ['Users Updated', $result['updated']],
                ['Errors Count', count($result['errors'])],
            ]
        );

        if (count($result['errors']) > 0) {
            $this->warn("Warnings / Errors:");
            $this->table(
                ['Row', 'Reason'],
                array_map(fn($e) => [$e['row'], $e['reason']], $result['errors'])
            );
        }

        return 0;
    }
}
