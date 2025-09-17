<div class="flex gap-1">
    <span style="color: red; font-weight: bold;">TEST: {{ $record->id }}</span>
    
    <a href="{{ $viewUrl }}" 
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors font-bold"
       title="View">
        👁️
    </a>
    
    <a href="{{ $editUrl }}" 
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-green-600 rounded hover:bg-green-700 transition-colors font-bold"
       title="Edit">
        ✏️
    </a>
    
    <button onclick="alert('DELETE BUTTON CLICKED!')" 
    class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-600 rounded hover:bg-red-700 transition-colors font-bold"
    title="Delete">
        🗑️
    </button>
</div>
