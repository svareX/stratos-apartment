<div class="max-w-4xl mx-auto px-8 py-14 min-h-screen">
    <div class="text-center mt-5 mb-14">
        <div class="mx-auto flux-skeleton rounded h-4 w-12 mb-3"></div>
        <div class="mx-auto flux-skeleton rounded h-12 w-80 md:w-112.5"></div>
    </div>

    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
        @for ($i = 0; $i < 6; $i++)
            <div class="border-b border-border last:border-0 px-6 py-6 flex items-center justify-between">
                <div class="flux-skeleton rounded h-6 w-2/3 md:w-1/2"></div>
                <div class="flux-skeleton rounded h-4 w-4 opacity-20"></div>
            </div>
        @endfor
    </div>
</div>