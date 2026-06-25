<x-workflow-layout>
    <x-slot name="header">
        <h1 class="font-display font-medium text-[32px] tracking-wide text-vellum">{{ __('Employee Import') }}</h1>
        <div class="text-[13px] text-vellum-muted mt-1.5 tracking-wide">
            Upload organizational directories or update personnel databases
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Error & Success Notifications -->
        @if ($errors->any())
            <div class="bg-burgundy-bg border border-burgundy/30 text-burgundy px-4 py-3 rounded-md shadow-sm">
                <ul class="list-disc pl-5 text-xs font-mono">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-forest-bg border border-forest/30 text-forest px-4 py-3 rounded-md shadow-sm font-medium text-sm font-mono">
                {{ session('success') }}
            </div>
        @endif

        <!-- Upload Section -->
        <div class="border-b border-hairline pb-6">
            <h3 class="text-lg font-semibold text-brass mb-2 font-display">Import File</h3>
            <p class="text-xs text-vellum-muted mb-4 leading-relaxed">
                Upload a Microsoft Excel (`.xlsx`) or CSV (`.csv`) spreadsheet to import new employees or update existing ones. The system matches records by employee ID or email.
            </p>

            <form action="{{ route('admin.import.handle') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="file" class="block text-xs font-semibold text-vellum-muted uppercase tracking-wider mb-2">Spreadsheet File (Max 5MB)</label>
                    <input type="file" name="file" id="file" required
                           class="w-full text-sm text-vellum file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-brass/10 file:text-brass hover:file:bg-brass/20 border border-hairline rounded p-2 bg-surface-raised focus:outline-none">
                </div>

                <x-primary-button type="submit" class="w-full flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Start Import Process
                </x-primary-button>
            </form>
        </div>

        <!-- Import Results (Conditional) -->
        @if (session('import_results'))
            @php $results = session('import_results'); @endphp
            <div class="border-b border-hairline pb-6 space-y-6">
                <div class="flex items-center justify-between border-b border-hairline pb-3">
                    <h3 class="text-lg font-semibold text-brass font-display">Import Summary</h3>
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold uppercase tracking-wider rounded bg-forest-bg text-forest">
                        Done
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-surface-raised p-4 rounded border border-hairline text-center">
                        <span class="text-[10px] text-vellum-faint uppercase font-semibold tracking-wider">Processed</span>
                        <h4 class="text-2xl font-bold text-vellum mt-1 font-mono">{{ $results['rows_processed'] }}</h4>
                    </div>
                    <div class="bg-surface-raised p-4 rounded border border-hairline text-center">
                        <span class="text-[10px] text-vellum-faint uppercase font-semibold tracking-wider">Created</span>
                        <h4 class="text-2xl font-bold text-forest mt-1 font-mono">{{ $results['created'] }}</h4>
                    </div>
                    <div class="bg-surface-raised p-4 rounded border border-hairline text-center">
                        <span class="text-[10px] text-vellum-faint uppercase font-semibold tracking-wider">Updated</span>
                        <h4 class="text-2xl font-bold text-brass mt-1 font-mono">{{ $results['updated'] }}</h4>
                    </div>
                    <div class="bg-surface-raised p-4 rounded border border-hairline text-center">
                        <span class="text-[10px] text-vellum-faint uppercase font-semibold tracking-wider">Failed</span>
                        <h4 class="text-2xl font-bold text-burgundy mt-1 font-mono">{{ count($results['errors']) }}</h4>
                    </div>
                </div>

                <!-- Imported Employees Results Table -->
                @php
                    $importedUsers = \App\Models\User::where('created_at', '>=', now()->subMinutes(2))
                        ->orWhere('updated_at', '>=', now()->subMinutes(2))
                        ->orderBy('updated_at', 'desc')
                        ->get();
                @endphp

                @if ($importedUsers->count() > 0)
                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold text-vellum font-display">Successfully Imported / Updated Employees</h4>
                        <div class="overflow-x-auto border border-hairline rounded">
                            <table class="w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-surface-raised border-b border-hairline uppercase text-[10px] tracking-wider text-vellum-muted font-semibold">
                                        <th class="py-2 px-3 text-left">Employee ID</th>
                                        <th class="py-2 px-3 text-left">Name</th>
                                        <th class="py-2 px-3 text-left">Email</th>
                                        <th class="py-2 px-3 text-center">Status</th>
                                        <th class="py-2 px-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-hairline">
                                    @foreach ($importedUsers as $u)
                                        <tr class="hover:bg-brass/[0.04] transition duration-150">
                                            <td class="py-2 px-3 text-left font-mono text-brass">{{ $u->employee_id ?? 'N/A' }}</td>
                                            <td class="py-2 px-3 text-left text-vellum font-semibold">{{ $u->name }}</td>
                                            <td class="py-2 px-3 text-left text-vellum-muted">{{ $u->email }}</td>
                                            <td class="py-2 px-3 text-center">
                                                <span class="px-2 py-0.5 text-[9px] font-mono font-semibold uppercase tracking-wider rounded {{ $u->status === 'active' ? 'bg-forest-bg text-forest' : 'bg-burgundy-bg text-burgundy' }}">
                                                    {{ $u->status }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 text-right">
                                                <a href="{{ route('employees.show', $u) }}"
                                                   class="inline-flex items-center px-2 py-0.5 bg-brass text-canvas rounded text-[10px] font-semibold uppercase tracking-wider hover:bg-brass/90 transition duration-150">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (count($results['errors']) > 0)
                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold text-vellum font-display">Errors and Warnings Detail</h4>
                        <div class="overflow-x-auto max-h-60 overflow-y-auto border border-hairline rounded">
                            <table class="w-full text-xs text-left">
                                <thead class="sticky top-0 bg-surface-raised">
                                    <tr class="border-b border-hairline uppercase text-[10px] tracking-wider text-vellum-muted font-semibold">
                                        <th class="py-2 px-3 w-16 text-left">Row</th>
                                        <th class="py-2 px-3 text-left">Reason / Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-hairline">
                                    @foreach ($results['errors'] as $error)
                                        <tr class="hover:bg-brass/[0.04] transition duration-150">
                                            <td class="py-2 px-3 font-mono text-vellum-muted">{{ $error['row'] }}</td>
                                            <td class="py-2 px-3 text-burgundy font-semibold text-[11px]">{{ $error['reason'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-forest-bg text-forest border border-forest/10 rounded text-xs font-semibold font-mono">
                        Excellent! The spreadsheet file imported completely without any warnings or rows skipped.
                    </div>
                @endif
            </div>
        @endif

        <!-- Import History Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-brass font-display">Recent Imports (Last 10 Runs)</h3>
            
            <div class="overflow-x-auto border border-hairline rounded">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-surface-raised border-b border-hairline uppercase text-[10px] tracking-wider text-vellum-muted font-semibold">
                            <th class="py-2 px-3 text-left">Filename</th>
                            <th class="py-2 px-3 text-left">Imported By</th>
                            <th class="py-2 px-3 text-left">Date & Time</th>
                            <th class="py-2 px-3 text-right font-mono">Processed</th>
                            <th class="py-2 px-3 text-right font-mono">Created</th>
                            <th class="py-2 px-3 text-right font-mono">Updated</th>
                            <th class="py-2 px-3 text-center">Errors</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse ($history as $log)
                            <tr class="hover:bg-brass/[0.04] transition duration-150">
                                <td class="py-2 px-3 text-left text-vellum font-semibold truncate max-w-xs" title="{{ $log->filename }}">
                                    {{ $log->filename }}
                                </td>
                                <td class="py-2 px-3 text-left text-vellum-muted">
                                    {{ $log->runByUser ? $log->runByUser->name : 'System/CLI' }}
                                </td>
                                <td class="py-2 px-3 text-left text-vellum-muted font-mono">
                                    {{ $log->created_at->timezone('Asia/Kolkata')->format('Y-m-d h:i A') }}
                                </td>
                                <td class="py-2 px-3 text-right font-mono font-bold text-vellum">
                                    {{ $log->rows_processed }}
                                </td>
                                <td class="py-2 px-3 text-right font-mono text-forest font-semibold">
                                    {{ $log->created_count }}
                                </td>
                                <td class="py-2 px-3 text-right font-mono text-brass font-semibold">
                                    {{ $log->updated_count }}
                                </td>
                                <td class="py-2 px-3 text-center">
                                    @if ($log->error_count > 0)
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-semibold uppercase tracking-wider rounded bg-burgundy-bg text-burgundy">
                                            {{ $log->error_count }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-semibold uppercase tracking-wider rounded bg-forest-bg text-forest">
                                            0
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 px-3 text-center text-vellum-faint">
                                    No employee imports have been recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-workflow-layout>
