@props(['title' => 'Policy','tagline' => null])

<div class="py-6 bg-gray-50 border-b">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">{{ $title }}</h1>
            @if($tagline)
                <p class="mt-2 text-sm text-gray-500">{{ $tagline }}</p>
            @endif
        </div>
    </div>
</div>

<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto grid lg:grid-cols-4 gap-8">
            <!-- TOC slot (optional) -->
            <aside class="lg:col-span-1 sticky top-20 hidden lg:block">
                @isset($toc)
                    <div class="bg-white border rounded-lg p-4 shadow-sm">
                        {{ $toc }}
                    </div>
                @endisset
            </aside>

            <!-- Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 prose prose-slate max-w-none">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
