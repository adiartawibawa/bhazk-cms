<div class="w-full flex justify-center">
    @if ($getIcon())
        <x-icon :name="$getIcon()" class="w-5 h-5" />
    @else
        <x-icon name="heroicon-o-question-mark-circle" class="w-5 h-5 text-gray-400" />
    @endif
</div>
