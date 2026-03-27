<div class="w-full min-h-screen flex flex-col pt-2 sm:pt-6">
    <section class="flex flex-col px-8 md:px-14 py-10 md:py-16">
        <div class="max-w-4xl w-full">
            <div class="flux-skeleton rounded h-3 w-16 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-10 w-48 md:w-72 mb-4 md:mb-6"></div>
            <div class="flux-skeleton rounded h-5 w-full max-w-xl mb-10 md:mb-14"></div>

            <div class="space-y-12">
                @for ($i = 0; $i < 3; $i++)
                    <div>
                        <div class="flux-skeleton rounded h-6 w-1/4 mb-5"></div>
                        <div class="space-y-3">
                            <div class="flux-skeleton rounded h-4 w-full"></div>
                            <div class="flux-skeleton rounded h-4 w-full"></div>
                            <div class="flux-skeleton rounded h-4 w-3/4"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
</div>