<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-on-surface leading-tight">
            {{ __('Employee Import') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Error & Success Notifications -->
        @if ($errors->any())
            <div class="bg-error/10 border border-error text-error px-4 py-3 rounded-md shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-secondary/10 border border-secondary text-secondary px-4 py-3 rounded-md shadow-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left: Upload Card -->
            <div class="lg:col-span-1 glass-panel p-6 rounded-lg border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-on-surface mb-2">Import File</h3>
                    <p class="text-sm text-on-surface-variant mb-6">
                        Upload a Microsoft Excel (`.xlsx`) or CSV (`.csv`) spreadsheet to import new employees or update existing ones. The system matches records by employee ID or email.
                    </p>

                    <form action="{{ route('admin.import.handle') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="file" class="block text-sm font-medium text-on-surface-variant mb-2">Spreadsheet File (Max 5MB)</label>
                            <input type="file" name="file" id="file" required
                                   class="w-full text-sm text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-outline-variant/30 rounded-md p-2 bg-surface-container focus:outline-none">
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-on-primary font-semibold py-2 px-4 rounded-md transition duration-200 shadow-md flex items-center justify-center gap-2">
                            <!-- Import Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Start Import Process
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Import Results (Rendered if session has results) -->
            <div class="lg:col-span-2">
                @if (session('import_results'))
                    @php $results = session('import_results'); @endphp
                    <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-6">
                        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-4">
                            <h3 class="text-lg font-semibold text-on-surface">Import Summary</h3>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-secondary/15 text-secondary">
                                Done
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-surface-container-high p-4 rounded-lg border border-outline-variant/20 text-center">
                                <span class="text-xs text-on-surface-variant uppercase font-semibold">Processed</span>
                                <h4 class="text-2xl font-bold text-on-surface mt-1">{{ $results['rows_processed'] }}</h4>
                            </div>
                            <div class="bg-surface-container-high p-4 rounded-lg border border-outline-variant/20 text-center">
                                <span class="text-xs text-on-surface-variant uppercase font-semibold">Created</span>
                                <h4 class="text-2xl font-bold text-secondary mt-1">{{ $results['created'] }}</h4>
                            </div>
                            <div class="bg-surface-container-high p-4 rounded-lg border border-outline-variant/20 text-center">
                                <span class="text-xs text-on-surface-variant uppercase font-semibold">Updated</span>
                                <h4 class="text-2xl font-bold text-primary mt-1">{{ $results['updated'] }}</h4>
                            </div>
                            <div class="bg-surface-container-high p-4 rounded-lg border border-outline-variant/20 text-center">
                                <span class="text-xs text-on-surface-variant uppercase font-semibold">Failed / Warnings</span>
                                <h4 class="text-2xl font-bold text-error mt-1">{{ count($results['errors']) }}</h4>
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
                                <h4 class="text-sm font-semibold text-on-surface">Successfully Imported / Updated Employees</h4>
                                <div class="overflow-x-auto border border-outline-variant/30 rounded-md">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-surface-container-high">
                                            <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                                                <th class="py-2.5 px-4">Employee ID</th>
                                                <th class="py-2.5 px-4">Name</th>
                                                <th class="py-2.5 px-4">Email</th>
                                                <th class="py-2.5 px-4">Status</th>
                                                <th class="py-2.5 px-4 text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($importedUsers as $user)
                                                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition">
                                                    <td class="py-2.5 px-4 font-mono text-on-surface-variant">{{ $user->employee_id ?? 'N/A' }}</td>
                                                    <td class="py-2.5 px-4 text-on-surface font-medium">{{ $user->name }}</td>
                                                    <td class="py-2.5 px-4 text-on-surface-variant">{{ $user->email }}</td>
                                                    <td class="py-2.5 px-4 text-xs font-semibold">
                                                        <span class="inline-flex px-2 py-0.5 rounded-full {{ $user->status === 'active' ? 'bg-green-100/20 text-green-700' : 'bg-red-100/20 text-red-700' }}">
                                                            {{ ucfirst($user->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center">
                                                        <a href="{{ route('employees.show', $user) }}"
                                                           class="inline-block px-3 py-1 bg-primary text-on-primary rounded text-xs font-semibold hover:bg-primary/95 transition duration-150">
                                                            View Profile
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
                                <h4 class="text-sm font-semibold text-on-surface">Errors and Warnings Detail</h4>
                                <div class="overflow-x-auto max-h-60 overflow-y-auto border border-outline-variant/30 rounded-md">
                                    <table class="w-full text-sm text-left">
                                        <thead class="sticky top-0 bg-surface-container-high">
                                            <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                                                <th class="py-2.5 px-4 w-20">Row</th>
                                                <th class="py-2.5 px-4">Reason / Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['errors'] as $error)
                                                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition">
                                                    <td class="py-2.5 px-4 font-mono text-on-surface-variant">{{ $error['row'] }}</td>
                                                    <td class="py-2.5 px-4 text-error font-medium text-xs">{{ $error['reason'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-secondary/10 text-secondary border border-secondary/20 rounded-md text-sm">
                                Excellent! The spreadsheet file imported completely without any warnings or rows skipped.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 flex flex-col items-center justify-center text-center h-full min-h-[300px]">
                        <div class="w-16 h-16 rounded-full bg-primary/15 flex items-center justify-center text-primary mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-on-surface mb-1">Import Results View</h3>
                        <p class="text-sm text-on-surface-variant max-w-sm">
                            Submit a spreadsheet file in the upload pane on the left to see statistics, warnings, and result records.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Import History Section -->
        <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-4">
            <h3 class="text-lg font-semibold text-on-surface">Recent Imports (Last 10 Runs)</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                            <th class="py-3 px-4">Filename</th>
                            <th class="py-3 px-4">Imported By</th>
                            <th class="py-3 px-4">Date & Time</th>
                            <th class="py-3 px-4 text-center">Rows Processed</th>
                            <th class="py-3 px-4 text-center">Created</th>
                            <th class="py-3 px-4 text-center">Updated</th>
                            <th class="py-3 px-4 text-center">Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $log)
                            <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition duration-150">
                                <td class="py-3 px-4 text-on-surface font-medium truncate max-w-xs" title="{{ $log->filename }}">
                                    {{ $log->filename }}
                                </td>
                                <td class="py-3 px-4 text-on-surface-variant">
                                    {{ $log->runByUser ? $log->runByUser->name : 'System/CLI' }}
                                </td>
                                <td class="py-3 px-4 text-on-surface-variant font-mono">
                                    {{ $log->created_at->timezone('Asia/Kolkata')->format('Y-m-d h:i A') }}
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-on-surface">
                                    {{ $log->rows_processed }}
                                </td>
                                <td class="py-3 px-4 text-center text-secondary font-semibold">
                                    {{ $log->created_count }}
                                </td>
                                <td class="py-3 px-4 text-center text-primary font-semibold">
                                    {{ $log->updated_count }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($log->error_count > 0)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-error/15 text-error">
                                            {{ $log->error_count }}
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-secondary/15 text-secondary">
                                            0
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-on-surface-variant">
                                    No employee imports have been recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
