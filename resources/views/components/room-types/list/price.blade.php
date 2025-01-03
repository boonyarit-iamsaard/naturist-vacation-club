@props([
    'weekdayPrice' => 0,
    'weekendPrice' => 0,
    'maxDiscount' => 0,
])

@php
    $weekdayPrice = number_format($weekdayPrice / 100);
    $weekendPrice = number_format($weekendPrice / 100);
@endphp

<div class="text-right">
    <div class="space-y-1">
        <div class="text-sm text-muted-foreground">From</div>
        <div class="text-2xl font-semibold">
            {{ $weekdayPrice }}
            <span class="ml-1 text-base font-normal text-muted-foreground">
                THB
            </span>
        </div>
        <div class="text-sm text-muted-foreground">per night</div>
    </div>

    <div class="mt-4 space-y-1 text-right text-sm">
        <div>
            • Weekday rates from {{ $weekdayPrice }} THB
        </div>
        <div>
            • Weekend rates from {{ $weekendPrice }} THB
        </div>
        <div class="text-muted-foreground">
            * Member discounts up to {{ $maxDiscount }}% available
        </div>
    </div>
</div>
