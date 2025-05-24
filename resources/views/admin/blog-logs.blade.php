@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">Blog Modification Logs</h1>

    @if ($logs->count() > 0)
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blog</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($logs as $log)
                    <tr x-data="{ isOpen: false }">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->subject->name ?? 'Deleted Blog' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->causer->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-semibold capitalize">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @php
                                $changes = $log->properties->toArray();
                            @endphp
                            @if(isset($changes['attributes']))
                                <ul class="list-disc ml-4">
                                    @foreach ($changes['attributes'] as $field => $value)
                                        @php
                                            $oldValue = $changes['old'] ?? [];
                                            $oldFieldValue = $oldValue[$field] ?? 'N/A';
                                        @endphp
                                        <li><strong>{{ ucfirst($field) }}:</strong> <em>{{ $oldFieldValue }}</em> → <strong>{{ $value }}</strong></li>
                                    @endforeach
                                </ul>
                            @else
                                No details available
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($log->subject) {{-- Ensure subject (recipe) still exists --}}
                            <button 
                                @click="isOpen = true"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded transition cursor-pointer"
                                type="button"
                            >
                                Undo Last Change
                            </button>

                            <!-- Confirmation Modal -->
                            <div
                            x-show="isOpen"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
                            style="display: none;"
                            aria-labelledby="modal-title" role="dialog" aria-modal="true"
                        >
                            <div @click.away="isOpen = false" class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                                <div class="flex items-center space-x-4">
                                    <div class="rounded-full bg-yellow-100 p-3">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M12 9v3m0 4h.01M5.07 19H18.93A2 2 0 0020 17.93V6.07A2 2 0 0018.93 5H5.07A2 2 0 004 6.07v11.86A2 2 0 005.07 19z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Undo Change</h3>
                                        <p class="mt-2 text-sm text-gray-600">Are you sure you want to undo this change?</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        @click="isOpen = false" 
                                        type="button" 
                                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 font-semibold"
                                    >
                                        Cancel
                                    </button>

                                    <form action="{{ route('admin.recipes.undo', $log->subject_id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700 font-semibold">
                                            Undo
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                            @else
                                <span class="text-gray-400 italic">Blog deleted</span>
                            @endif
                        </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>

    @else
        <p class="text-gray-600">No modification logs found.</p>
    @endif
</div>
@endsection
