@props(['active' => false])

@php
    $base =
        'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';
    $classes = $active
        ? 'border-background dark:border-foreground text-background dark:text-foreground focus:border-background dark:focus:border-foreground'
        : 'border-transparent text-background/60 dark:text-foreground/60 hover:text-background/80 dark:hover:text-foreground/80 hover:border-background/60 dark:hover:border-foreground/60 focus:text-background/80 dark:focus:text-foreground/80 focus:border-background/80 dark:focus:border-foreground/80';
@endphp

<a {{ $attributes->merge(['class' => "$base $classes"]) }}>
    {{ $slot }}
</a>
