<div class="w-full min-h-screen flex flex-col pt-2 sm:pt-6">
    
    <section class="flex flex-col px-8 md:px-14 py-10 md:py-16">
        <div class="max-w-4xl w-full">
            <div class="flux-skeleton rounded h-3 w-16 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-10 w-64 md:w-96 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-5 w-full max-w-2xl mb-10 md:mb-14"></div>

            <div class="space-y-12">
                @for ($i = 0; $i < 4; $i++)
                    <div>
                        <div class="flux-skeleton rounded h-6 w-1/3 mb-5"></div>
                        <div class="space-y-3">
                            <div class="flux-skeleton rounded h-4 w-full"></div>
                            <div class="flux-skeleton rounded h-4 w-full"></div>
                            <div class="flux-skeleton rounded h-4 w-4/5"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <section class="flex flex-col px-8 md:px-14 py-12 md:py-20 bg-gray border-t border-border">
        <div class="max-w-4xl w-full">
            <div class="flux-skeleton rounded h-3 w-24 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-10 w-56 md:w-80 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-5 w-full max-w-xl mb-10 md:mb-14"></div>

            <div class="space-y-12">
                @for ($i = 0; $i < 3; $i++)
                    <div>
                        <div class="flux-skeleton rounded h-6 w-1/4 mb-5"></div>
                        <div class="space-y-3">
                            <div class="flux-skeleton rounded h-4 w-full"></div>
                            <div class="flux-skeleton rounded h-4 w-11/12"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
</div>