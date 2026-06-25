<x-ledger-layout :timeline="false">
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="font-display font-medium text-[32px] tracking-wide text-vellum">{{ $department->name }}</h1>
                <div class="text-[13px] text-vellum-muted mt-1.5 tracking-wide">
                    Department Code: <span class="font-mono text-brass font-semibold">{{ $department->code }}</span>
                </div>
            </div>

            <a href="{{ route('departments.index') }}" class="text-vellum-muted hover:text-brass transition text-sm flex items-center gap-1 font-semibold">
                ← Back to Departments
            </a>
        </div>
    </x-slot>

    @if(!empty($department->description))
        <div class="panel p-5 border border-hairline bg-surface rounded">
            <h4 class="text-xs font-semibold text-vellum-faint uppercase tracking-wider mb-2">Description</h4>
            <p class="text-sm text-vellum leading-relaxed">{{ $department->description }}</p>
        </div>
    @endif

    <x-slot name="ledgerHeader">
        <h2>Assigned Members ({{ $employees->count() }})</h2>
        <div class="meta font-mono text-[11px] text-vellum-faint">members in {{ $department->name }}</div>
    </x-slot>

    @php
        $headers = [
            ['label' => 'Employee ID', 'class' => ''],
            ['label' => 'Employee Name', 'class' => ''],
            ['label' => 'Department', 'class' => ''],
            ['label' => 'Assigned Manager', 'class' => ''],
            ['label' => 'Designation', 'class' => ''],
            ['label' => 'Phone', 'class' => 'hidden xl:table-cell'],
            ['label' => 'Email', 'class' => 'hidden lg:table-cell'],
            ['label' => 'Status', 'class' => ''],
            ['label' => 'Joining Date', 'class' => 'hidden md:table-cell'],
            ['label' => 'Actions', 'class' => 'text-right']
        ];
    @endphp

    <x-ledger-table :headers="$headers">
        @forelse($employees as $employee)
            <tr class="hover:bg-brass/[0.04] transition duration-150 text-[16px]">
                <!-- Employee ID -->
                <td class="py-4 px-4 font-mono text-[16px] text-brass select-all font-medium">
                    {{ $employee->employee_id ?? 'N/A' }}
                </td>
                
                <!-- Employee Name -->
                <td class="py-4 px-4 text-[18px] font-bold text-vellum">
                    <a href="{{ route('employees.show', $employee) }}" class="hover:text-brass transition-colors">{{ $employee->name }}</a>
                </td>

                <!-- Department -->
                <td class="py-4 px-4 text-[16px] text-vellum font-medium">
                    {{ $employee->department?->name ?? 'None' }}
                </td>

                <!-- Assigned Manager -->
                <td class="py-4 px-4 text-[16px] text-vellum-muted">
                    {{ $employee->manager?->name ?? 'None' }}
                </td>

                <!-- Designation -->
                <td class="py-4 px-4 text-[15px] md:text-[16px] text-vellum-muted">
                    {{ $employee->employeeProfile?->designation ?? 'None' }}
                </td>

                <!-- Phone -->
                <td class="py-4 px-4 text-[16px] text-vellum-muted font-mono select-all hidden xl:table-cell">
                    {{ $employee->phone ?? '—' }}
                </td>

                <!-- Email -->
                <td class="py-4 px-4 text-[15px] text-vellum-muted truncate max-w-[200px] select-all hidden lg:table-cell" title="{{ $employee->email }}">
                    {{ $employee->email }}
                </td>

                <!-- Status -->
                <td class="py-4 px-4">
                    <span class="tag {{ $employee->status === 'active' ? 'present' : 'absent' }} text-[11px] font-mono uppercase tracking-[0.8px] px-2.5 py-0.5 rounded border
                        @if($employee->status === 'active') bg-forest-bg text-forest border-transparent
                        @else bg-burgundy-bg text-burgundy border-transparent @endif">
                        {{ $employee->status }}
                    </span>
                </td>

                <!-- Joining Date -->
                <td class="py-4 px-4 text-[16px] text-vellum-muted font-mono hidden md:table-cell">
                    {{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '—' }}
                </td>

                <!-- Actions -->
                <td class="py-4 px-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('employees.show', $employee) }}"
                           class="px-3 py-1.5 bg-brass hover:bg-brass/90 text-canvas font-semibold rounded text-[11px] uppercase tracking-wider transition">
                            View
                        </a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('employees.edit', $employee) }}"
                               class="px-3 py-1.5 bg-surface-raised hover:bg-surface-raised/80 text-vellum border border-hairline rounded text-[11px] uppercase tracking-wider transition">
                                Edit
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="py-8 text-center text-vellum-faint border border-dashed border-hairline-strong rounded mt-1 text-[13px]">
                    No members assigned to this department.
                </td>
            </tr>
        @endforelse
    </x-ledger-table>
</x-ledger-layout>
