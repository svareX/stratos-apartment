<div class="flex min-h-screen w-full flex-col pt-2 sm:pt-6">
    <section class="flex flex-col px-8 py-10 md:px-14 md:py-16">
        <div class="w-full max-w-4xl">
            <div class="flux-skeleton mb-4 h-3 w-16 rounded md:mb-6"></div>
            <div
                class="flux-skeleton mb-4 h-10 w-48 rounded md:mb-6 md:w-72"
            ></div>
            <div
                class="flux-skeleton mb-10 h-5 w-full max-w-xl rounded md:mb-14"
            ></div>

            <div class="space-y-12">
                @for ($i = 0; $i < 3; $i++)
                    <div>
                        <div class="flux-skeleton mb-5 h-6 w-1/4 rounded"></div>
                        <div class="space-y-3">
                            <div class="flux-skeleton h-4 w-full rounded"></div>
                            <div class="flux-skeleton h-4 w-full rounded"></div>
                            <div class="flux-skeleton h-4 w-3/4 rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
</div>
