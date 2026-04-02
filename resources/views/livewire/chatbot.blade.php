<div
    x-data="{
        show: false,
        open: @entangle('isOpen'),
        showBubble: true,
        animateBubble: true,
        userInput: @entangle('userInput'),

        scrollToBottom() {
            this.$nextTick(() => {
                let container = document.getElementById('chat-container');
                if (container) container.scrollTop = container.scrollHeight;
            });
        },

        closeBubbleInstantly() {
            this.animateBubble = false;
            this.$nextTick(() => { this.showBubble = false; });
        },

        init() {
            // Načtení externího parseru pro Markdown
            if (typeof marked === 'undefined') {
                let script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
                document.head.appendChild(script);
            }

            // Zobrazení tlačítka
            setTimeout(() => this.show = true, 1);

            // Automatické skrytí bubliny po 10s
            setTimeout(() => {
                if (!this.open) {
                    this.animateBubble = true;
                    this.showBubble = false;
                }
            }, 10000);

            // Pokud je chat už otevřený, bublinu neukazovat
            if (this.open) this.showBubble = false;

            // Sledování otevírání chatu
            this.$watch('open', value => {
                if (value) {
                    this.closeBubbleInstantly();
                    this.scrollToBottom();
                }
            });
        }
    }"
    x-on:scroll-to-bottom.window="scrollToBottom()"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end"
>
    <style>
        .typing-dot {
            animation: typing 1.4s infinite;
            display: inline-block;
            width: 4px; height: 4px;
            border-radius: 50%;
            background-color: #4b5563;
            margin-right: 2px;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; margin-right: 0; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-4px); }
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .ai-avatar-gradient { background: linear-gradient(135deg, #4B2EA2, #00C9A7); }

        /* Styly pro parsování Markdownu uvnitř bublin */
        .chat-markdown p { margin-bottom: 0.5em; }
        .chat-markdown p:last-child { margin-bottom: 0; }
        .chat-markdown strong { font-weight: 700; color: inherit; }
        .chat-markdown em { font-style: italic; }
        .chat-markdown ul { list-style-type: disc; padding-left: 1.25em; margin-top: 0.25em; margin-bottom: 0.5em; }
        .chat-markdown li { margin-bottom: 0.15em; }
    </style>

    {{-- Chatovací okno --}}
    <div x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="bg-white shadow-2xl rounded-2xl w-80 sm:w-96 flex flex-col mb-4 overflow-hidden border border-[#E2DCF5]"
        style="height: 500px; display: none;">

        <div @click="open = false" class="bg-[#1A0A3B] text-white px-4 py-4 flex justify-between items-center cursor-pointer hover:bg-opacity-95 transition-all">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="font-bold tracking-wide">{{ __('Support bot') }}</span>
            </div>
            <button class="text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div id="chat-container" x-effect="scrollToBottom()" class="flex-1 p-4 overflow-y-auto bg-[#F5F3FA] flex flex-col gap-3">
            @foreach($messages as $index => $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                     wire:key="msg-wrapper-{{ $index }}-{{ strlen($msg['content']) }}">

                    <div class="px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm {{ $msg['role'] === 'user' ? 'bg-[#4B2EA2] text-white rounded-br-none' : 'bg-white border border-[#E2DCF5] text-[#1C1530] rounded-bl-none' }}"
                        @if($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            x-data="{
                                fullText: @js($msg['content']),
                                displayText: '',
                                index: 0,
                                isTyping: true, // Startujeme s tečkami
                                hasStartedTypingEffect: false,
                                type() {
                                    if (this.index < this.fullText.length) {
                                        let batchSize = Math.floor(Math.random() * 7) + 2;
                                        let nextChunk = this.fullText.slice(this.index, this.index + batchSize);
                                        this.displayText += nextChunk;
                                        this.index += nextChunk.length;

                                        let delay = Math.floor(Math.random() * 20) + 5;
                                        if (nextChunk.includes('.') || nextChunk.includes('?') || nextChunk.includes('!')) delay = 250;
                                        setTimeout(() => this.type(), delay);

                                        this.$nextTick(() => {
                                            let container = document.getElementById('chat-container');
                                            if(container) container.scrollTop = container.scrollHeight;
                                        });
                                    }
                                }
                            }"
                            {{-- NOVÁ LOGIKA: Reagujeme na příchod textu z PHP --}}
                            x-effect="
                                fullText = @js($msg['content']);
                                // Pokud text dorazil a ještě nezačala animace psaní:
                                if (fullText.length > 0 && !hasStartedTypingEffect) {
                                    hasStartedTypingEffect = true;
                                    // Krátká estetická pauza, než tečky zmizí (0.3-0.6s)
                                    setTimeout(() => {
                                        isTyping = false; // Schováme tečky, ukážeme Markdown div
                                        type();          // Spustíme 'vyťukávání'
                                    }, Math.floor(Math.random() * 300) + 300);
                                }
                            "
                        @endif
                    >
                        @if($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            <template x-if="isTyping">
                                <div class="flex items-center h-5">
                                    <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
                                </div>
                            </template>
                            {{-- Přidána podmínka x-show="!isTyping && displayText.length > 0" --}}
                            <div x-show="!isTyping && displayText.length > 0" class="chat-markdown break-words" x-html="typeof marked !== 'undefined' ? marked.parse(displayText) : displayText"></div>
                        @else
                            <div class="chat-markdown break-words" x-data="{ text: @js($msg['content']) }" x-html="typeof marked !== 'undefined' && '{{ $msg['role'] }}' === 'assistant' ? marked.parse(text) : text"></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4 bg-white border-t border-[#E2DCF5]">
            <form wire:submit="sendMessage" class="flex items-end gap-2 bg-[#F5F3FA] rounded-xl p-2 border border-[#E2DCF5] focus-within:border-[#4B2EA2] transition-colors">
                <textarea wire:model="userInput" rows="2" x-on:keydown.enter.prevent="$wire.sendMessage()" class="flex-1 bg-transparent border-none focus:ring-0 focus:outline-none text-sm text-[#1C1530] resize-none hide-scrollbar py-1 px-2" placeholder="{{ __('Type your message...') }}"></textarea>
                <button type="submit" class="my-auto bg-[#4B2EA2] text-white p-2 rounded-lg hover:bg-opacity-90 transition-all disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed" wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="sendMessage" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                    <svg wire:loading wire:target="sendMessage" class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
            <div class="text-[10px] text-[#7A7090] mt-2 text-center">{{ __('Press Enter to send') }}</div>
        </div>
    </div>

    {{-- Uvítací bublina --}}
    <div x-show="showBubble && !open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave.duration.300ms="animateBubble ? 'transition ease-in duration-300' : ''"
        x-transition:leave-start="animateBubble ? 'opacity-100 translate-y-0' : ''"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="bg-white border border-[#E2DCF5] rounded-[18px] rounded-br-[4px] p-4 shadow-xl max-w-[180px] text-[13px] leading-relaxed text-[#1C1530] mb-3 mr-1">
        <strong class="text-[#4B2EA2] text-[12px] block mb-1">✨ {{ __('Travel Guide') }}</strong>
        {{ __('Need help with anything? Ask me!') }}
    </div>

    {{-- Floating avatar --}}
    <button x-show="show" x-transition @click="open = !open" class="w-[44px] h-[44px] ai-avatar-gradient text-white rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-all group cursor-pointer" style="display: none;">
        <span x-show="!open" class="text-[20px] transition-transform group-hover:rotate-12">🤖</span>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
</div>
