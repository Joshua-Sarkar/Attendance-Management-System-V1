<x-app-layout wide>
    <x-slot name="header">
        <h1 class="font-display font-medium text-[26px] tracking-wide text-vellum">Attendance History</h1>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Present Count -->
                <div class="bg-surface p-6 rounded border border-hairline flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-vellum-muted text-xs font-semibold uppercase tracking-wider">Days Present</p>
                            <p class="text-3xl font-bold text-forest-light mt-2 font-display">{{ $present_count }}</p>
                        </div>
                        <div class="text-forest-light/20 text-3xl font-mono">✓</div>
                    </div>
                </div>

                <!-- Absent Count -->
                <div class="bg-surface p-6 rounded border border-hairline flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-vellum-muted text-xs font-semibold uppercase tracking-wider">Days Absent</p>
                            <p class="text-3xl font-bold text-burgundy-light mt-2 font-display">{{ $absent_count }}</p>
                        </div>
                        <div class="text-burgundy-light/20 text-3xl font-mono">✗</div>
                    </div>
                </div>

                <!-- Late Count -->
                <div class="bg-surface p-6 rounded border border-hairline flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-vellum-muted text-xs font-semibold uppercase tracking-wider">Days Late</p>
                            <p class="text-3xl font-bold text-cognac-light mt-2 font-display">{{ $late_count }}</p>
                        </div>
                        <div class="text-cognac-light/20 text-3xl font-mono">⏱</div>
                    </div>
                </div>

            </div>

            <!-- Attendance Table -->
            <div class="panel space-y-4">
                <div class="panel-head flex items-center justify-between mb-4">
                    <h2 class="font-display font-medium text-[16px] text-vellum">Last 30 Days</h2>
                    <div class="meta font-mono text-[11px] text-vellum-faint">attendance log</div>
                </div>
                
                @if ($history->count() > 0)
                    <div class="overflow-x-auto">
                          <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-surface-raised/55 border-b border-hairline uppercase text-[11px] tracking-wider text-vellum-muted font-semibold">
                                    <th class="py-3.5 px-5 text-left">Date</th>
                                    <th class="py-3.5 px-5 text-left">Day</th>
                                    <th class="py-3.5 px-5 text-left">Check In</th>
                                    <th class="py-3.5 px-5 text-left">Check Out</th>
                                    <th class="py-3.5 px-5 text-right">Hours</th>
                                    <th class="py-3.5 px-5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $record)
                                    <tr class="border-b border-hairline/50 hover:bg-brass/[0.04] transition duration-150">
                                        <td class="py-3.5 px-5 text-left text-vellum font-medium font-mono">
                                            {{ $record->date->format('M d, Y') }}
                                        </td>
                                        <td class="py-3.5 px-5 text-left text-vellum-muted">
                                            {{ $record->date->format('l') }}
                                        </td>
                                        <td class="py-3.5 px-5 text-left text-vellum font-mono">
                                            @if ($record->check_in_time)
                                                <span>{{ $record->check_in_time->format('h:i A') }}</span>
                                            @else
                                                <span class="text-vellum-faint italic font-sans">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-left text-vellum font-mono">
                                            @if ($record->check_out_time)
                                                <span>{{ $record->check_out_time->format('h:i A') }}</span>
                                            @else
                                                <span class="text-vellum-faint italic font-sans">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-right text-vellum font-mono font-semibold">
                                            @if ($record->check_in_time && $record->check_out_time)
                                                <span>{{ number_format($record->check_in_time->diffInHours($record->check_out_time), 1) }}h</span>
                                            @else
                                                <span class="text-vellum-faint italic font-sans font-normal">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-center">
                                            <span class="tag {{ $record->status }}">
                                                {{ str_replace('_', ' ', $record->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-vellum-muted text-base">No attendance records found.</p>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center text-brass hover:underline font-semibold text-sm">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
