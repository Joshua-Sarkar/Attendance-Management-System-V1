<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Profile — ') }} {{ $user->name }}
            </h2>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('employees.edit', $user) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition duration-150">
                    Edit Profile
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Core Information Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-indigo-200">
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }} | {{ $user->employee_id ?? 'No Employee ID' }}</p>
                            <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->status }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 bg-white">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</span>
                        <span class="text-sm font-medium text-gray-900 capitalize">{{ $user->role }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->department?->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Joining Date</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->joining_date?->format('Y-m-d') ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Reporting Manager</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->manager?->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned Admin</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->admin?->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Profile Sections -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 space-y-8">
                
                <!-- 1. Personal -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </span>
                        Section "Personal"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Father's Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->father_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Mother's Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->mother_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Gender</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->gender ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Date of Birth</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->date_of_birth?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Marital Status</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->marital_status ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Date of Marriage</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->date_of_marriage?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Nationality</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->nationality ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Blood Group</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->blood_group ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Personal Email</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->personal_email ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Mobile No</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->mobile_no ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 2. Government IDs -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.333 0 4 .667 4 2V17H6v-1c0-1.333 2.667-2 4-2z" /></svg>
                        </span>
                        Section "Government IDs"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">PF UAN</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->pf_uan ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Passport No</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->passport_no ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Aadhar Card</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->aadhar_card ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">PAN</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->pan ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">PF No</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->pf_no ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">ESI Number</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->esi_number ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Date of Gratuity</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->date_of_gratuity?->format('Y-m-d') ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 3. Employment -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </span>
                        Section "Employment"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Payroll Type</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->payroll_type ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Contract End Date</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->contract_end_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Office Landline</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->office_landline ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Leave Rule</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->leave_rule ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Shift</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->shift ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Designation</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->designation ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Grade</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->grade ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Employee Type</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->employee_type ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Company</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->company ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Location</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->location ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Biometric ID</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->biometric_id ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Hiring Source</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->hiring_source ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Source of Verification</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->source_of_verification ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">City Type</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->city_type ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Notice Days</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->notice_days ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">State Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->state_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Joining Date</span><p class="text-sm font-medium text-gray-900">{{ $user->joining_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 4. Current Address -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </span>
                        Section "Current Address"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Address Line 1</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_address1 ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Address Line 2</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_address2 ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Country</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_country ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">State</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_state ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">City</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_city ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Zip Code</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->current_zip ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 5. Permanent Address -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </span>
                        Section "Permanent Address"
                    </h4>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->employeeProfile?->same_as_current_address ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $user->employeeProfile?->same_as_current_address ? 'Same as Current Address' : 'Different Address' }}
                        </span>
                    </div>
                    
                    @if(!$user->employeeProfile?->same_as_current_address)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><span class="text-xs text-gray-500">Address Line 1</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_address1 ?? 'N/A' }}</p></div>
                            <div><span class="text-xs text-gray-500">Address Line 2</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_address2 ?? 'N/A' }}</p></div>
                            <div><span class="text-xs text-gray-500">Country</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_country ?? 'N/A' }}</p></div>
                            <div><span class="text-xs text-gray-500">State</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_state ?? 'N/A' }}</p></div>
                            <div><span class="text-xs text-gray-500">City</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_city ?? 'N/A' }}</p></div>
                            <div><span class="text-xs text-gray-500">Zip Code</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->permanent_zip ?? 'N/A' }}</p></div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600">Same as current address details.</p>
                    @endif
                </div>

                <!-- 6. Bank Details -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        Section "Bank Details"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Payment Type</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->payment_type ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Bank Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->bank_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Account Holder Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->account_holder_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Account No</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->account_no ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">IFSC Code</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->ifsc_code ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 7. Emergency Contact -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </span>
                        Section "Emergency Contact"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Emergency Contact Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->emergency_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Relationship</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->emergency_relationship ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Address</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->emergency_address ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Email Address</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->emergency_email ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Mobile Phone</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->emergency_mobile ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 8. Education -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                        </span>
                        Section "Education"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Degree Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->degree_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Institution Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->institution_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Passing Year</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->passing_year ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Percentage</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->percentage ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 9. Previous Employment -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </span>
                        Section "Previous Employment"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Previous Company Name</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->previous_company_name ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Job Title</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->previous_job_title ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">From Date</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->previous_from_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">To Date</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->previous_to_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                    </div>
                </div>

                <!-- 10. Tenure -->
                <div>
                    <h4 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 p-1.5 rounded-md mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </span>
                        Section "Tenure"
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-xs text-gray-500">Probation Period</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->probation_period ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Probation Confirm Date</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->probation_confirm_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Separation Date</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->separation_date?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Last Working Day</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->last_working_day?->format('Y-m-d') ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Previous Experience (Years)</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->previous_year_experience ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Years Completed</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->years_completed ?? 'N/A' }}</p></div>
                        <div><span class="text-xs text-gray-500">Overall Experience (Years)</span><p class="text-sm font-medium text-gray-900">{{ $user->employeeProfile?->overall_year_experience ?? 'N/A' }}</p></div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
