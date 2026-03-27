<div class="w-full min-h-screen flex flex-col pt-2 sm:pt-6">
    
    <section class="flex flex-col px-8 md:px-14 py-10 pb-12">
        <div class="flux-skeleton rounded h-3 w-16 mb-4 md:mb-6"></div>
        <div class="flux-skeleton rounded h-10 w-64 md:w-96 mb-8 md:mb-12"></div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @for ($i = 0; $i < 3; $i++)
                <div class="flex flex-col p-6 py-8 rounded-2xl bg-white border border-border shadow-sm">
                    <div class="flux-skeleton rounded-xl h-12 w-12 mb-4"></div>
                    <div class="flux-skeleton rounded h-6 w-40 mb-4"></div>
                    <div class="flux-skeleton rounded h-4 w-48 mb-2"></div>
                    <div class="flux-skeleton rounded h-4 w-32 mb-8"></div>
                    <div class="mt-auto pt-4 border-t border-border w-full">
                        <div class="flux-skeleton rounded h-4 w-24"></div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <section class="flex flex-col px-8 md:px-14 py-12 md:py-16 bg-gray border-t border-border">
        <div class="flux-skeleton rounded h-3 w-12 mb-4 md:mb-6"></div>
        <div class="flux-skeleton rounded h-10 w-72 md:w-112.5 mb-4"></div>
        <div class="flux-skeleton rounded h-4 w-64 mb-8 md:mb-10"></div>

        <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden w-full max-w-4xl">
            @for ($i = 0; $i < 5; $i++)
                <div class="border-b border-border last:border-0 px-6 py-6 flex items-center justify-between">
                    <div class="flux-skeleton rounded h-6 w-2/3 md:w-1/2"></div>
                    <div class="flux-skeleton rounded h-4 w-4 opacity-20"></div>
                </div>
            @endfor
        </div>
    </section>
</div>