@extends('layouts.app')

@section('content')
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
                            @click="openModal({{ $recipe->id }}, '{{ addslashes($recipe->name) }}')"
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
        
    @else
        <p class="text-gray-600">You have not created any recipes yet.</p>
    @endif

    <!-- Delete Confirmation Modal (Single Instance) -->
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
                    <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete the recipe <strong x-text="recipeName"></strong>? This action cannot be undone.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button @click="closeModal" type="button" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 font-semibold">
                    Cancel
                </button>

                <form :action="`/recipes/${recipeId}`" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 font-semibold">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteModal() {
        return {
            isOpen: false,
            recipeId: null,
            recipeName: '',
            openModal(id, name) {
                this.recipeId = id;
                this.recipeName = name;
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
                this.recipeId = null;
                this.recipeName = '';
            }
        }
    }
</script>
@endsection
