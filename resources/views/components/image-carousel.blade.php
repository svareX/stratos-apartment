@props (['images' => []])

<div
    x-data="{ 
        shown: false,
        images: {{ Js::from($images) }},
        get displayItems() {
            let items = [];
            for(let i = 0; i < 7; i++) {
                this.images.forEach((img, idx) => {
                    items.push({ src: img, originalIndex: idx, uniqueId: i + '-' + idx });
                });
            }
            return items;
        },
        lightboxOpen: false,
        activeIndex: 0,
        init() {
            this.$nextTick(() => {
                setTimeout(() => {
                    let s = this.$refs.slider;
                    let img = s.querySelector('img');
                    if(img) {
                        let itemWidth = img.offsetWidth + 24;
                        let setWidth = itemWidth * this.images.length;
                        s.classList.remove('scroll-smooth');
                        s.scrollLeft = setWidth * 3;
                        s.classList.add('scroll-smooth');
                    }
                }, 100);
            });
        },
        scrollNext() { 
            let s = this.$refs.slider;
            let img = s.querySelector('img');
            if(!img) return;
            
            let itemWidth = img.offsetWidth + 24;
            let setWidth = itemWidth * this.images.length;
            
            if (s.scrollLeft > setWidth * 4) {
                s.classList.remove('scroll-smooth');
                s.scrollLeft -= setWidth;
                s.getBoundingClientRect();
                s.classList.add('scroll-smooth');
            }
            
            s.scrollBy({ left: itemWidth, behavior: 'smooth' });
        },
        scrollPrev() { 
            let s = this.$refs.slider;
            let img = s.querySelector('img');
            if(!img) return;
            
            let itemWidth = img.offsetWidth + 24;
            let setWidth = itemWidth * this.images.length;
            
            if (s.scrollLeft < setWidth * 2) {
                s.classList.remove('scroll-smooth');
                s.scrollLeft += setWidth;
                s.getBoundingClientRect();
                s.classList.add('scroll-smooth');
            }
            
            s.scrollBy({ left: -itemWidth, behavior: 'smooth' });
        },
        handleScroll() {
            let s = this.$refs.slider;
            let img = s.querySelector('img');
            if (!img) return;
            
            let itemWidth = img.offsetWidth + 24;
            let setWidth = itemWidth * this.images.length;

            if (s.scrollLeft <= setWidth) {
                s.classList.remove('scroll-smooth');
                s.scrollLeft += setWidth * 3;
                s.classList.add('scroll-smooth');
            } else if (s.scrollLeft >= s.scrollWidth - setWidth) {
                s.classList.remove('scroll-smooth');
                s.scrollLeft -= setWidth * 3;
                s.classList.add('scroll-smooth');
            }
        },
        openLightbox(index) {
            this.activeIndex = index;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
        },
        nextImage() {
            this.activeIndex = (this.activeIndex === this.images.length - 1) ? 0 : this.activeIndex + 1;
        },
        prevImage() {
            this.activeIndex = (this.activeIndex === 0) ? this.images.length - 1 : this.activeIndex - 1;
        }
    }"
    @keydown.escape.window="if (lightboxOpen) closeLightbox();"
    @keydown.arrow-right.window="if (lightboxOpen) nextImage();"
    @keydown.arrow-left.window="if (lightboxOpen) prevImage();"
    x-intersect.once.margin.-100px="shown = true"
    class="w-full"
>
    <div class="group relative w-full px-[10px]">
        <button
            @click="scrollPrev"
            class="text-primary absolute top-1/2 left-[20px] z-10 -translate-y-1/2 cursor-pointer rounded-full bg-white/90 p-3 opacity-0 shadow-lg transition-all group-hover:opacity-100 hover:scale-105 hover:bg-white"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        <div
            x-ref="slider"
            @scroll.passive="handleScroll"
            class="gap-md no-scrollbar flex snap-x snap-mandatory justify-start overflow-x-auto scroll-smooth py-4"
        >
            <template x-for="item in displayItems" :key="item.uniqueId">
                <div x-data="{ loaded: false }" class="relative">
                    <div
                        x-show="!loaded"
                        x-transition
                        class="flux-skeleton h-[300px] w-[400px] shrink-0 rounded"
                    ></div>
                    <img
                        @click="openLightbox(item.originalIndex)"
                        @load="loaded = true"
                        :class="loaded
                            ? 'opacity-100 scale-100'
                            : 'opacity-0 scale-95'"
                        :style="`transition-delay: ${item.originalIndex * 100}ms;`"
                        class="h-[300px] w-[400px] shrink-0 cursor-pointer snap-center snap-always rounded-xl object-cover transition-all duration-700 ease-out hover:opacity-90 hover:shadow-lg"
                        :src="item.src"
                        :alt="'Gallery image ' +
                        (item.originalIndex + 1) +
                        ' of ' +
                        images.length"
                    />
                </div>
            </template>
        </div>

        <button
            @click="scrollNext"
            class="text-primary absolute top-1/2 right-[20px] z-10 -translate-y-1/2 cursor-pointer rounded-full bg-white/90 p-3 opacity-0 shadow-lg transition-all group-hover:opacity-100 hover:scale-105 hover:bg-white"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>

    <template x-teleport="body">
        <div
            x-show="lightboxOpen"
            x-transition.opacity.duration.300ms
            @click.self="closeLightbox"
            class="lightbox-backdrop fixed inset-0 z-[100] flex items-center justify-center backdrop-blur-md"
            style="display: none"
        >
            <button
                @click="closeLightbox"
                class="absolute top-6 right-6 z-50 cursor-pointer rounded-full bg-black/20 p-2 text-white/70 transition-colors hover:bg-black/40 hover:text-white sm:top-10 sm:right-10"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <button
                @click="prevImage"
                class="absolute left-4 z-50 cursor-pointer rounded-full bg-black/20 p-4 text-white/70 transition-colors hover:bg-black/40 hover:text-white sm:left-10"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <img
                :src="images[activeIndex]"
                class="lightbox-img shadow-2xl"
                :alt="'Fullscreen image ' +
                (activeIndex + 1) +
                ' of ' +
                images.length"
            />

            <button
                @click="nextImage"
                class="absolute right-4 z-50 cursor-pointer rounded-full bg-black/20 p-4 text-white/70 transition-colors hover:bg-black/40 hover:text-white sm:right-10"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <div
                class="pointer-events-none absolute bottom-10 rounded-full bg-black/40 px-4 py-2 font-mono tracking-widest text-white/70"
            >
                <span x-text="activeIndex + 1"></span> /
                <span x-text="images.length"></span>
            </div>
        </div>
    </template>
</div>
