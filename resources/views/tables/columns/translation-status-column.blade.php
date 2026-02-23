@php
    $record = $getRecord();
    $statuses = $getColumn()->getTranslationStatusForRecord($record);
    $showPercentage = $getColumn()->getShowPercentage();
@endphp

<div class="flex flex-wrap gap-1">
    @foreach ($statuses as $locale => $info)
        <span @class([
            'inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
            'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/30' => $info['color'] === 'success',
            'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30' => $info['color'] === 'warning',
            'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400 dark:ring-danger-400/30' => $info['color'] === 'danger',
            'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30' => $info['color'] === 'gray',
        ])>
            @if ($info['flag'])
                <span>{{ $info['flag'] }}</span>
            @endif

            <span>{{ strtoupper($locale) }}</span>

            @if ($showPercentage)
                <span class="opacity-75">({{ $info['percentage'] }}%)</span>
            @endif
        </span>
    @endforeach
</div>
