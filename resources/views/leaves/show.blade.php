<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-on-surface dark:text-on-surface leading-tight">
            {{ __('Leave Application Details') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-6">
        <!-- Back Link -->
        <div class="flex justify-between items-center">
            <a href="{{ route('leaves.index') }}" class="text-on-surface-variant hover:text-primary transition text-sm flex items-center gap-1 font-medium">
                ← Back to Leave Management
            </a>
            <span class="text-xs text-on-surface-variant">Request ID: #{{ $leaveRequest->id }}</span>
        </div>

        <!-- Details Card -->
        <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-outline-variant/30 pb-4 gap-4">
                <div>
                    <h3 class="text-xl font-bold text-on-surface capitalize">
                        {{ $leaveRequest->leave_type === 'complimentary' ? 'Birthday Leave' : ($leaveRequest->leave_type ? str_replace('_', ' ', $leaveRequest->leave_type) : 'Pending Classification') }}
                    </h3>
                    <p class="text-sm text-on-surface-variant mt-1">
                        Submitted by: <span class="font-semibold">{{ $leaveRequest->user->name }}</span> ({{ $leaveRequest->user->employee_id }})
                    </p>
                </div>
                <div>
                    <span class="inline-block px-3 py-1.5 rounded-full text-sm font-semibold capitalize
                        @if($leaveRequest->status === 'approved')
                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                        @elseif($leaveRequest->status === 'pending')
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                        @elseif($leaveRequest->status === 'cancelled')
                            bg-gray-100 text-gray-800 dark:bg-gray-700/30 dark:text-gray-300
                        @else
                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                        @endif
                    ">
                        {{ $leaveRequest->status }}
                    </span>
                </div>
            </div>

            <!-- Meta Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                <div>
                    <span class="text-on-surface-variant block uppercase tracking-wider text-xs font-semibold">Start Date</span>
                    <span class="text-on-surface font-medium text-base mt-1 block">{{ $leaveRequest->start_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-on-surface-variant block uppercase tracking-wider text-xs font-semibold">End Date</span>
                    <span class="text-on-surface font-medium text-base mt-1 block">{{ $leaveRequest->end_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-on-surface-variant block uppercase tracking-wider text-xs font-semibold">Total Days</span>
                    <span class="text-on-surface font-bold text-base mt-1 block">{{ $leaveRequest->total_days }} {{ $leaveRequest->total_days === 1 ? 'Day' : 'Days' }}</span>
                </div>
            </div>

            <!-- Reason Block -->
            <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20">
                <span class="text-on-surface-variant uppercase tracking-wider text-xs font-semibold block mb-2">Reason for Request</span>
                <p class="text-on-surface text-sm leading-relaxed whitespace-pre-wrap">{{ $leaveRequest->reason }}</p>
            </div>

            <!-- Notes or Rejection Reason -->
            @if($leaveRequest->status === 'approved' && $leaveRequest->notes)
                <div class="bg-green-50/50 dark:bg-green-900/10 p-4 rounded-lg border border-green-200/50">
                    <span class="text-green-800 dark:text-green-300 uppercase tracking-wider text-xs font-semibold block mb-2">Approval Notes</span>
                    <p class="text-green-700 dark:text-green-200 text-sm whitespace-pre-wrap">{{ $leaveRequest->notes }}</p>
                </div>
            @endif

            @if($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
                <div class="bg-red-50/50 dark:bg-red-900/10 p-4 rounded-lg border border-red-200/50">
                    <span class="text-red-800 dark:text-red-300 uppercase tracking-wider text-xs font-semibold block mb-2">Rejection Feedback</span>
                    <p class="text-red-700 dark:text-red-200 text-sm whitespace-pre-wrap">{{ $leaveRequest->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Audit Trail Timeline -->
        <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-6">
            <h3 class="text-lg font-bold text-on-surface pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Audit Trail Timeline
            </h3>

            <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-outline-variant/50">
                @foreach($logs as $log)
                    <div class="relative flex flex-col sm:flex-row sm:items-start gap-4">
                        <!-- Timeline Marker Dot -->
                        <span class="absolute -left-8 top-1.5 w-3 h-3 rounded-full border-2 border-surface flex items-center justify-center
                            @if($log->action === 'applied')
                                bg-yellow-500 ring-4 ring-yellow-500/10
                            @elseif($log->action === 'approved')
                                bg-green-500 ring-4 ring-green-500/10
                            @elseif($log->action === 'rejected')
                                bg-red-500 ring-4 ring-red-500/10
                            @elseif($log->action === 'cancelled')
                                bg-gray-500 ring-4 ring-gray-500/10
                            @elseif($log->action === 'overridden')
                                bg-primary ring-4 ring-primary/10
                            @endif
                        "></span>

                        <!-- Log Details -->
                        <div class="flex-1 bg-surface-container p-4 rounded-lg border border-outline-variant/20 space-y-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-b border-outline-variant/10 pb-2">
                                <span class="text-sm font-bold text-on-surface capitalize">
                                    Action: {{ $log->action }}
                                </span>
                                <span class="text-xs text-on-surface-variant font-mono">
                                    {{ $log->created_at->format('M d, Y @ h:i A') }}
                                </span>
                            </div>

                            <div class="text-xs text-on-surface-variant space-y-1">
                                <p>
                                    Acting User: <span class="font-semibold text-on-surface">{{ $log->user->name }}</span> ({{ ucfirst($log->user->role) }})
                                </p>
                                @if($log->from_status || $log->to_status)
                                    <p>
                                        Transition: 
                                        <span class="font-mono bg-surface-container-high px-1 rounded capitalize">{{ $log->from_status ?? 'None' }}</span>
                                        → 
                                        <span class="font-mono bg-surface-container-high px-1 rounded capitalize font-semibold text-on-surface">{{ $log->to_status }}</span>
                                    </p>
                                @endif
                            </div>

                            @if($log->notes)
                                <div class="text-xs italic text-on-surface-variant bg-surface-container-high/40 p-2.5 rounded border-l-2 border-primary/30 mt-2">
                                    "{{ $log->notes }}"
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
