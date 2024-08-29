<div class="flex flex-col gap-y-2">
    <label class="text-sm text-gray-400 {{ $getLabel() ?? '' }}">{{ $getLabel() }}</label>
    <hr />
    <div {{ $attributes }}>
        {{ $getChildComponentContainer() }}
    </div>
</div>
