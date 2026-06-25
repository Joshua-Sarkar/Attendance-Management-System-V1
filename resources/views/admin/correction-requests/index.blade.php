<x-ledger-layout :timeline="false">
    <x-slot name="header">
        <div>
            <h1 class="font-display font-medium text-[32px] tracking-wide text-vellum">Correction Requests</h1>
            <div class="text-[13px] text-vellum-muted mt-1.5 tracking-wide">
                Review and resolve workforce profile correction requests
            </div>
        </div>
    </x-slot>

    <!-- Session Notifications -->
    @if(session('success'))
        <div class="rounded bg-forest-bg border border-hairline text-forest px-4 py-3 text-sm mb-6">
            {{ session('success') }}
        </div>
    @endif

    <x-slot name="ledgerHeader">
        <h2>All Correction Requests</h2>
        <div class="meta font-mono text-[11px] text-vellum-faint font-medium">requests log</div>
    </x-slot>

    @php
        $headers = [
            ['label' => 'Employee ID', 'class' => ''],
            ['label' => 'Employee Name', 'class' => ''],
            ['label' => 'Department', 'class' => ''],
            ['label' => 'Field', 'class' => ''],
            ['label' => 'Request Message', 'class' => ''],
            ['label' => 'Status', 'class' => 'text-center'],
            ['label' => 'Submitted Date', 'class' => 'hidden md:table-cell'],
            ['label' => 'Resolution Note / Action', 'class' => 'text-right']
        ];
    @endphp

    <x-ledger-table :headers="$headers">
        @forelse($requests as $req)
            <tr class="hover:bg-brass/[0.04] transition duration-150 text-[16px]">
                <!-- Employee ID -->
                <td class="py-4 px-4 font-mono text-[16px] text-brass select-all font-medium">
                    {{ $req->requester->employee_id ?? 'N/A' }}
                </td>

                <!-- Employee Name -->
                <td class="py-4 px-4 text-[18px] font-bold text-vellum">
                    <a href="{{ route('employees.show', $req->requester) }}" class="hover:text-brass transition-colors">
                        {{ $req->requester->name }}
                    </a>
                </td>

                <!-- Department -->
                <td class="py-4 px-4 text-[16px] text-vellum font-medium">
                    {{ $req->requester->department?->name ?? 'None' }}
                </td>

                <!-- Field -->
                <td class="py-4 px-4">
                    <span class="tag present text-[11px] font-mono uppercase tracking-[0.8px] px-2.5 py-0.5 rounded border bg-surface-raised text-vellum border-hairline">
                        {{ $req->field }}
                    </span>
                </td>

                <!-- Message -->
                <td class="py-4 px-4 text-[16px] text-vellum-muted max-w-xs whitespace-pre-line leading-relaxed">
                    {{ $req->message }}
                </td>

                <!-- Status -->
                <td class="py-4 px-4 text-center">
                    <span class="tag text-[11px] font-mono uppercase tracking-[0.8px] px-2.5 py-0.5 rounded border
                        @if($req->status === 'pending') bg-cognac-bg text-cognac border-transparent
                        @else bg-forest-bg text-forest border-transparent @endif">
                        {{ $req->status }}
                    </span>
                </td>

                <!-- Submitted Date -->
                <td class="py-4 px-4 text-[16px] text-vellum-muted font-mono hidden md:table-cell">
                    {{ $req->created_at->format('Y-m-d h:i A') }}
                </td>

                <!-- Resolution / Form -->
                <td class="py-4 px-4 text-right">
                    <div class="inline-block text-left w-64">
                        @if($req->status === 'pending')
                            <form method="POST" action="{{ route('admin.corrections.resolve', $req) }}" class="space-y-2">
                                @csrf
                                <textarea name="admin_note" rows="2" placeholder="Add resolution note..."
                                          class="w-full text-xs bg-surface-raised border border-hairline rounded px-2.5 py-2 text-vellum placeholder-vellum-faint focus:outline-none focus:border-brass/50"></textarea>
                                <x-primary-button type="submit" class="!h-[32px] !py-1 text-[10px] w-full justify-center">
                                    Resolve & Mark Complete
                                </x-primary-button>
                            </form>
                        @else
                            <div class="text-xs text-vellum-muted bg-surface-raised p-2.5 rounded border border-hairline text-left">
                                <div class="font-semibold text-vellum">Resolution details:</div>
                                <div class="italic text-vellum-muted mt-1">"{{ $req->admin_note ?? 'No notes provided' }}"</div>
                                <div class="mt-2 text-[10px] text-vellum-faint border-t border-dashed border-hairline pt-1">
                                    By {{ $req->resolver?->name ?? 'Admin' }} on {{ $req->resolved_at?->format('Y-m-d h:i A') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-8 text-center text-vellum-faint border border-dashed border-hairline-strong rounded mt-1 text-[13px]">
                    No profile correction requests found.
                </td>
            </tr>
        @endforelse
    </x-ledger-table>
</x-ledger-layout>
