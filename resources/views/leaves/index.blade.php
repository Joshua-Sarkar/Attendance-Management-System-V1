<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-on-surface dark:text-on-surface leading-tight">
            {{ __('Leave Management') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-8">
        <!-- Session Notifications -->
        @if(session('success'))
            <div class="rounded-md bg-green-100 border border-green-300 text-green-700 px-4 py-3 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-100 border border-red-300 text-red-700 px-4 py-3 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Leave Summary Stats (Own Approved leaves this year) -->
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <h4 class="text-lg font-semibold text-on-surface">Your Approved Leave Summary (Current Year)</h4>
                <a href="{{ route('leaves.create') }}" 
                   class="bg-primary hover:bg-primary/95 text-on-primary font-semibold py-2.5 px-5 rounded-md transition duration-200 shadow-md">
                    + Apply for Leave
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4">
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">Casual Leave</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['casual_leave'] }} days</h3>
                </div>
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">Sick Leave</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['sick_leave'] }} days</h3>
                </div>
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">Paid Leave</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['paid_leave'] }} days</h3>
                </div>
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">Unpaid Leave</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['unpaid_leave'] }} days</h3>
                </div>
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">WFH</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['work_from_home'] }} days</h3>
                </div>
                <div class="bg-surface-container p-4 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-on-surface-variant text-xs font-semibold">Emergency</span>
                    <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $stats['emergency_leave'] }} days</h3>
                </div>
                <div class="bg-primary/10 p-4 rounded-lg border border-primary/20 flex flex-col justify-between">
                    <span class="text-primary text-xs font-bold">Total Approved</span>
                    <h3 class="text-2xl font-bold text-primary mt-2">{{ $stats['total_approved'] }} days</h3>
                </div>
            </div>
        </div>

        <!-- Manager/Admin Approval Queue -->
        @if(auth()->user()->role !== 'employee')
            <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-4">
                <h3 class="text-lg font-bold text-on-surface">Leave Request Approval Queue</h3>
                @if($pendingQueue->isEmpty())
                    <p class="text-sm text-on-surface-variant py-4 text-center">No pending leave requests to review.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                                    <th class="py-3 px-4">Employee</th>
                                    <th class="py-3 px-4">Leave Type</th>
                                    <th class="py-3 px-4">Date Range</th>
                                    <th class="py-3 px-4 text-center">Total Days</th>
                                    <th class="py-3 px-4">Reason</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingQueue as $request)
                                    <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition duration-150">
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-on-surface">{{ $request->user->name }}</div>
                                            <div class="text-xs text-on-surface-variant font-mono">{{ $request->user->employee_id }} ({{ ucfirst($request->user->role) }})</div>
                                        </td>
                                        <td class="py-3 px-4 font-medium text-on-surface capitalize">
                                            {{ str_replace('_', ' ', $request->leave_type) }}
                                        </td>
                                        <td class="py-3 px-4 text-on-surface">
                                            {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-center text-on-surface font-semibold">
                                            {{ $request->total_days }}
                                        </td>
                                        <td class="py-3 px-4 text-on-surface-variant max-w-xs truncate" title="{{ $request->reason }}">
                                            {{ $request->reason }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('leaves.show', $request) }}" class="text-primary hover:underline font-semibold text-xs">
                                                    View Details
                                                </a>
                                                <button onclick="openApproveModal({{ $request->id }})" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded text-xs transition">
                                                    Approve
                                                </button>
                                                <button onclick="openRejectModal({{ $request->id }})" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded text-xs transition">
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

        <!-- Personal Leave Request History -->
        <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-4">
            <h3 class="text-lg font-bold text-on-surface">Your Leave Applications</h3>
            @if($myLeaves->isEmpty())
                <p class="text-sm text-on-surface-variant py-4 text-center">You have not submitted any leave requests yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                                <th class="py-3 px-4">Date Range</th>
                                <th class="py-3 px-4">Leave Type</th>
                                <th class="py-3 px-4 text-center">Days</th>
                                <th class="py-3 px-4">Reason</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Notes/Feedback</th>
                                <th class="py-3 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myLeaves as $request)
                                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition duration-150">
                                    <td class="py-3 px-4 font-medium text-on-surface">
                                        {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-on-surface capitalize">
                                        {{ str_replace('_', ' ', $request->leave_type) }}
                                    </td>
                                    <td class="py-3 px-4 text-center text-on-surface">
                                        {{ $request->total_days }}
                                    </td>
                                    <td class="py-3 px-4 text-on-surface-variant max-w-xs truncate" title="{{ $request->reason }}">
                                        {{ $request->reason }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize
                                            @if($request->status === 'approved')
                                                bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($request->status === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @elseif($request->status === 'cancelled')
                                                bg-gray-100 text-gray-800 dark:bg-gray-700/30 dark:text-gray-300
                                            @else
                                                bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @endif
                                        ">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-on-surface-variant max-w-xs truncate">
                                        @if($request->status === 'approved')
                                            {{ $request->notes ?? '-' }}
                                        @elseif($request->status === 'rejected')
                                            {{ $request->rejection_reason ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('leaves.show', $request) }}" class="text-primary hover:underline font-semibold text-xs">
                                                View Logs
                                            </a>
                                            @if(in_array($request->status, ['pending', 'approved']))
                                                <form action="{{ route('leaves.cancel', $request) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                                    @csrf
                                                    <button type="submit" class="text-error hover:underline font-semibold text-xs">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Global History (Admin or Manager view of non-pending logs) -->
        @if(auth()->user()->role !== 'employee')
            <div class="glass-panel p-6 rounded-lg border border-outline-variant/30 space-y-4">
                <h3 class="text-lg font-bold text-on-surface">Leave Decisions History</h3>
                @if($historyQueue->isEmpty())
                    <p class="text-sm text-on-surface-variant py-4 text-center">No leave history recorded.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/30 text-on-surface-variant font-semibold">
                                    <th class="py-3 px-4">Employee</th>
                                    <th class="py-3 px-4">Leave Type</th>
                                    <th class="py-3 px-4">Date Range</th>
                                    <th class="py-3 px-4 text-center">Days</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Reviewed By</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyQueue as $request)
                                    <tr class="border-b border-outline-variant/20 hover:bg-surface-container-high/30 transition duration-150">
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-on-surface">{{ $request->user->name }}</div>
                                            <div class="text-xs text-on-surface-variant font-mono">{{ $request->user->employee_id }}</div>
                                        </td>
                                        <td class="py-3 px-4 capitalize text-on-surface">
                                            {{ str_replace('_', ' ', $request->leave_type) }}
                                        </td>
                                        <td class="py-3 px-4 text-on-surface">
                                            {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-center text-on-surface">
                                            {{ $request->total_days }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize
                                                @if($request->status === 'approved')
                                                    bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @elseif($request->status === 'cancelled')
                                                    bg-gray-100 text-gray-800 dark:bg-gray-700/30 dark:text-gray-300
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                @endif
                                            ">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-on-surface">
                                            {{ $request->approver?->name ?? 'System' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('leaves.show', $request) }}" class="text-primary hover:underline font-semibold text-xs">
                                                    View Details
                                                </a>
                                                @if(auth()->user()->role === 'admin' && $request->user_id !== auth()->id())
                                                    <button onclick="openOverrideModal({{ $request->id }}, '{{ $request->status }}')" class="bg-primary hover:bg-primary/95 text-on-primary font-semibold py-1 px-2.5 rounded text-xs transition shadow-sm">
                                                        Override
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-surface p-6 rounded-lg shadow-xl w-full max-w-md border border-outline-variant/30">
            <h3 class="text-lg font-bold text-on-surface mb-4">Approve Leave Request</h3>
            <form id="approveForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-on-surface-variant mb-1">Approver Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Add optional comments here..."
                                  class="w-full bg-surface-container border border-outline-variant/30 rounded-md text-on-surface px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('approveModal')" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-semibold py-2 px-4 rounded-md transition duration-200 border border-outline-variant/30">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200 shadow-md">
                            Confirm Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-surface p-6 rounded-lg shadow-xl w-full max-w-md border border-outline-variant/30">
            <h3 class="text-lg font-bold text-on-surface mb-4 text-error">Reject Leave Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-on-surface-variant mb-1">Rejection Reason (Required)</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required placeholder="State the reason for rejection..."
                                  class="w-full bg-surface-container border border-outline-variant/30 rounded-md text-on-surface px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('rejectModal')" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-semibold py-2 px-4 rounded-md transition duration-200 border border-outline-variant/30">
                            Cancel
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200 shadow-md">
                            Confirm Reject
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Override Modal -->
    <div id="overrideModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-surface p-6 rounded-lg shadow-xl w-full max-w-md border border-outline-variant/30">
            <h3 class="text-lg font-bold text-on-surface mb-4">Admin Override Decision</h3>
            <form id="overrideForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="override_status" class="block text-sm font-medium text-on-surface-variant mb-1">Override Status</label>
                        <select name="override_status" id="override_status" required
                                class="w-full bg-surface-container border border-outline-variant/30 rounded-md text-on-surface px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none">
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label for="override_notes" class="block text-sm font-medium text-on-surface-variant mb-1">Override Reason / Notes (Required)</label>
                        <textarea name="override_notes" id="override_notes" rows="3" required placeholder="Explain why this decision was overridden..."
                                  class="w-full bg-surface-container border border-outline-variant/30 rounded-md text-on-surface px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('overrideModal')" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-semibold py-2 px-4 rounded-md transition duration-200 border border-outline-variant/30">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:bg-primary/95 text-on-primary font-semibold py-2 px-4 rounded-md transition duration-200 shadow-md">
                            Apply Override
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id) {
            document.getElementById('approveForm').action = '/leaves/' + id + '/approve';
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = '/leaves/' + id + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function openOverrideModal(id, currentStatus) {
            document.getElementById('overrideForm').action = '/leaves/' + id + '/override';
            const statusSelect = document.getElementById('override_status');
            statusSelect.value = currentStatus === 'approved' ? 'rejected' : 'approved';
            document.getElementById('overrideModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-app-layout>
