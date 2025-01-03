@props([
    'name' => '',
    'description' => '',
    'weekdayPrice' => 0,
    'weekendPrice' => 0,
    'maxDiscount' => 0,
])

<li class="overflow-hidden border border-border bg-card text-card-foreground">
    <div class="grid md:grid-cols-12">
        <div class="relative grid aspect-square place-items-center bg-muted md:col-span-3 md:aspect-auto">
            <x-tabler-photo class="size-20 text-border" />
        </div>

        <div class="flex flex-col space-y-4 p-6 md:col-span-9">
            <div class="flex items-start justify-between">
                <div class="space-y-4">
                    <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <h2 class="order-2 font-serif text-lg font-semibold sm:order-1">
                            {{ $name }}
                        </h2>
                    </div>

                    <p>{{ $description }}</p>

                    <div>
                        <a
                            href='/'
                            class="text-sm text-muted-foreground underline-offset-2 hover:underline"
                        >
                            More Details
                        </a>
                    </div>

                    <x-room-types.list.price
                        :weekdayPrice="$weekdayPrice"
                        :weekendPrice="$weekendPrice"
                        :maxDiscount="$maxDiscount"
                    />

                    <div class="flex flex-1 flex-col justify-end">
                        <div class="flex items-center justify-end gap-4">
                            <x-primary-button>
                                Select dates
                            </x-primary-button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</li>
