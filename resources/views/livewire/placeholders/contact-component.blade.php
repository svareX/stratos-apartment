<div class="flex min-h-screen w-full flex-col pt-2 sm:pt-6">
    <section class="flex flex-col px-8 py-10 pb-12 md:px-14">
        <div class="flux-skeleton mb-4 h-3 w-16 rounded md:mb-6"></div>
        <div
            class="flux-skeleton mb-8 h-10 w-64 rounded md:mb-12 md:w-96"
        ></div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            @for ($i = 0; $i < 3; $i++)
                <div
                    class="border-border flex flex-col rounded-2xl border bg-white p-6 py-8 shadow-sm"
                >
                    <div class="flux-skeleton mb-4 h-12 w-12 rounded-xl"></div>
                    <div class="flux-skeleton mb-4 h-6 w-40 rounded"></div>
                    <div class="flux-skeleton mb-2 h-4 w-48 rounded"></div>
                    <div class="flux-skeleton mb-8 h-4 w-32 rounded"></div>
                    <div class="border-border mt-auto w-full border-t pt-4">
                        <div class="flux-skeleton h-4 w-24 rounded"></div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <section
        class="bg-gray border-border flex flex-col border-t px-8 py-12 md:px-14 md:py-16"
    >
        <div class="flux-skeleton mb-4 h-3 w-12 rounded md:mb-6"></div>
        <div class="flux-skeleton mb-4 h-10 w-72 rounded md:w-112.5"></div>
        <div class="flux-skeleton mb-8 h-4 w-64 rounded md:mb-10"></div>

        <div
            class="border-border w-full max-w-4xl overflow-hidden rounded-2xl border bg-white shadow-sm"
        >
            @for ($i = 0; $i < 5; $i++)
                <div
                    class="border-border flex items-center justify-between border-b px-6 py-6 last:border-0"
                >
                    <div class="flux-skeleton h-6 w-2/3 rounded md:w-1/2"></div>
                    <div class="flux-skeleton h-4 w-4 rounded opacity-20"></div>
                </div>
            @endfor
        </div>
    </section>
</div>
