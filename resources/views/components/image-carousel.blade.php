@props(['images' => []])

<div x-data="{ 
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
    @keydown.escape.window="if(lightboxOpen) closeLightbox()"
    @keydown.arrow-right.window="if(lightboxOpen) nextImage()"
    @keydown.arrow-left.window="if(lightboxOpen) prevImage()"
    x-intersect.once.margin.-100px="shown = true" 
    class="w-full">
    
    <div class="relative group w-full px-[10px]">
        
        <button @click="scrollPrev" class="absolute left-[20px] top-1/2 -translate-y-1/2 z-10 bg-white/90 text-primary p-3 rounded-full shadow-lg hover:bg-white hover:scale-105 transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        <div x-ref="slider" @scroll.passive="handleScroll" class="flex justify-start gap-md overflow-x-auto snap-x snap-mandatory no-scrollbar py-4 scroll-smooth">
            <template x-for="item in displayItems" :key="item.uniqueId">
                <div x-data="{ loaded: false }" class="relative">
                    <div x-show="!loaded" x-transition class="flux-skeleton rounded h-[300px] w-[400px] shrink-0"></div>
                    <img @click="openLightbox(item.originalIndex)"
                         @load="loaded = true"
                         :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                         :style="`transition-delay: ${item.originalIndex * 100}ms;`"
                         class="transition-all duration-700 ease-out h-[300px] w-[400px] object-cover rounded-xl snap-center snap-always cursor-pointer hover:opacity-90 hover:shadow-lg shrink-0" 
                         :src="item.src" 
                         :alt="'Gallery image ' + (item.originalIndex + 1) + ' of ' + images.length">
                </div>
            </template>
        </div>

        <button @click="scrollNext" class="absolute right-[20px] top-1/2 -translate-y-1/2 z-10 bg-white/90 text-primary p-3 rounded-full shadow-lg hover:bg-white hover:scale-105 transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>

    <template x-teleport="body">
              <div x-show="lightboxOpen" 
          x-transition.opacity.duration.300ms
          @click.self="closeLightbox"
          class="fixed inset-0 z-[100] flex items-center justify-center lightbox-backdrop backdrop-blur-md"
          style="display: none;">
            
            <button @click="closeLightbox" class="absolute top-6 right-6 sm:top-10 sm:right-10 text-white/70 hover:text-white transition-colors z-50 p-2 bg-black/20 hover:bg-black/40 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <button @click="prevImage" class="absolute left-4 sm:left-10 text-white/70 hover:text-white transition-colors z-50 p-4 bg-black/20 hover:bg-black/40 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

              <img :src="images[activeIndex]" 
                  class="lightbox-img shadow-2xl" 
                  :alt="'Fullscreen image ' + (activeIndex + 1) + ' of ' + images.length">

            <button @click="nextImage" class="absolute right-4 sm:right-10 text-white/70 hover:text-white transition-colors z-50 p-4 bg-black/20 hover:bg-black/40 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
            
            <div class="absolute bottom-10 text-white/70 font-mono tracking-widest bg-black/40 px-4 py-2 rounded-full pointer-events-none">
                <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    </template>
</div>