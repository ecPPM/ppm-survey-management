<div>
    <h2 class="font-semibold text-l text-gray-800 leading-tight">
        Upload Enumerator File
    </h2>

    <div class="flex flex-row gap-3 my-3">
        <input id="file" type="file" wire:model="file" class="file-input file-input-bordered w-full max-w-xs my-3"/>
        <button wire:click="upload" class="btn btn-primary my-auto" wire:loading.attr="disabled">Upload File</button>
    </div>

    @error('file') <span class="error text-red-500">{{ $message }}</span> @enderror

    <!-- Loading indicator -->
    {{-- <div wire:loading wire:target="file">Uploading...</div> --}}
</div>
