@extends('layouts.app')

@section('content')
@if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)"
        x-transition 
        class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex items-center space-x-3"
        role="alert"
    >
        <!-- Icon -->
        <svg class="h-6 w-6 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        <!-- Text -->
        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">Successfully saved!</p>
            <p class="text-sm text-gray-500">{{ session('success') }}</p>
        </div>

        <!-- Close button -->
        <button 
            @click="show = false"
            class="text-gray-400 hover:text-gray-600 focus:outline-none"
            aria-label="Close notification"
        >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
@endif

<div class="container mx-auto px-4 py-8" x-data="deleteModal()">
    <h1 class="text-3xl font-bold mb-6 text-orange-600">My Recipes</h1>

    @if ($recipes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($recipes as $recipe)
                <div class="bg-white rounded-lg shadow p-4 group hover:shadow-lg transition relative">
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="block">
                        <div class="aspect-[4/3] rounded mb-4 overflow-hidden">
                            @if ($recipe->image_url)
                                <img src="{{ asset('storage/recipes/' . basename($recipe->image_url)) }}" alt="{{ $recipe->name }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" />
                            @else
                                <div class="bg-gray-200 flex items-center justify-center text-gray-400 text-xl h-full">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg mb-1">{{ $recipe->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2 max-w-full overflow-hidden break-words line-clamp-3">{{ $recipe->description }}</p>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>{{ ucfirst($recipe->category) }}</span>
                        </div>
                    </a>

                    @if(auth()->check() && (auth()->id() == $recipe->chef_id))
                        <a href="{{ route('recipes.edit', $recipe->id) }}" 
                           class="absolute bottom-3 right-20 bg-orange-600 text-white text-xs px-3 py-1 rounded hover:bg-orange-700 transition"
                           title="Edit Recipe">
                            Edit
                        </a>

                        <!-- Delete Button triggers modal -->
                        <button type="button"
                            @click="openModal({{ $recipe->id }})"
                            class="absolute bottom-3 right-3 bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-700 transition"
                            title="Delete Recipe">
                            Delete
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $recipes->links() }}  {{-- pagination links --}}
        </div>

        <!-- Delete Confirmation Modal -->
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
        <div @click.away="closeModal" class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center space-x-4">
                <div class="rounded-full bg-red-100 p-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12 9v3m0 4h.01M5.07 19H18.93A2 2 0 0020 17.93V6.07A2 2 0 0018.93 5H5.07A2 2 0 004 6.07v11.86A2 2 0 005.07 19z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Delete Recipe</h3>
                    <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete this recipe? This action cannot be undone.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button @click="closeModal" type="button" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 font-semibold">
                    Cancel
                </button>

                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 font-semibold">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
        <p class="text-gray-600">You have not created any recipes yet.</p>
    @endif

</div>

<script>
    function deleteModal() {
        return {
            isOpen: false,
            recipeId: null,
            openModal(id) {
                this.recipeId = id;
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
                this.recipeId = null;
            }
        }
    }
</script>
@endsection
