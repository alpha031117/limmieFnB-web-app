@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">Blog List</h1>

    @if ($blogs->count() > 0)
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>  <!-- New column -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approve/Reject</th>  <!-- New column -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($blogs as $blog)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $blog->name }}
                            @if($blog->hasInappropriateComment())
                                <span title="Inappropriate feedback detected" 
                                class="ml-2 text-red-600 font-bold text-xl leading-none select-none">!</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($blog->category) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $blog->Author->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($blog->is_approved)
                                <span class="text-green-600 font-semibold">Approved</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('blog.show', $blog->id) }}" 
                            class="inline-flex items-center px-3 py-1 bg-orange-600 text-white rounded hover:bg-orange-700 transition">
                                View
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <form action="{{ route('admin.blogs.updateApproval', $blog->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                @if($blog->is_approved)
                                    <button type="submit" name="approved" value="0" 
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                        onclick="return confirm('Are you sure you want to disapprove this blog?')">
                                        Disapprove
                                    </button>
                                @else
                                    <button type="submit" name="approved" value="1" 
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                        onclick="return confirm('Approve this blog?')">
                                        Approve
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $blogs->links() }}
    </div>

    @else
        <p class="text-gray-600">No blogs found.</p>
    @endif
</div>
@endsection
