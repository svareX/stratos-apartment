<div
    x-data="{
        show: false,
        open: @entangle('isOpen'),
        showBubble: true,
        animateBubble: true,
        userInput: '',
        messageCount: @entangle('messageCount'),
        hasPlayedOpenSound: false,
        siteKey: '{{ config('services.recaptcha.site_key') }}',
        isSubmitting: false,

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

        playOpenSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                const ctx = new AudioCtx();
                const o = ctx.createOscillator();
                const g = ctx.createGain();
                o.type = 'sine';
                o.frequency.value = 740;
                g.gain.value = 0.0001;
                o.connect(g);
                g.connect(ctx.destination);
                o.start();
                g.gain.exponentialRampToValueAtTime(0.18, ctx.currentTime + 0.01);
                g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.22);
                setTimeout(() => { try { o.stop(); ctx.close(); } catch(e) {} }, 300);
            } catch (e) {
                // ignore if WebAudio not available or blocked
            }
        },

        async submitMessage() {
            let text = this.userInput || '';

            if (text.trim() === '' || this.isSubmitting) return;

            this.isSubmitting = true;

            let processSubmit = async (token) => {
                try {
                    await this.$wire.set('userInput', text);
                    await this.$wire.sendMessage(token);
                    this.userInput = '';
                } finally {
                    this.isSubmitting = false;
                }
            };

            if ((this.messageCount === 0 || this.messageCount % 5 === 0) && this.siteKey) {
                let resolved = false;

                if (window.grecaptcha) {
                    grecaptcha.ready(() => {
                        try {
                            grecaptcha.execute(this.siteKey, {action: 'submit'})
                                .then((token) => {
                                    if (!resolved) {
                                        resolved = true;
                                        processSubmit(token);
                                    }
                                })
                                .catch(() => {
                                    if (!resolved) {
                                        resolved = true;
                                        processSubmit(null);
                                    }
                                });
                        } catch (e) {
                            if (!resolved) {
                                resolved = true;
                                processSubmit(null);
                            }
                        }
                    });

                    setTimeout(() => {
                        if (!resolved) {
                            resolved = true;
                            processSubmit(null);
                        }
                    }, 2000);
                } else {
                    processSubmit(null);
                }
            } else {
                processSubmit(null);
            }
        },

        init() {
            if (typeof marked === 'undefined') {
                let script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
                document.head.appendChild(script);
            }

            if (typeof grecaptcha === 'undefined' && this.siteKey) {
                let recaptchaScript = document.createElement('script');
                recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=' + this.siteKey;
                document.head.appendChild(recaptchaScript);
            }

            setTimeout(() => this.show = true, 1);

            setTimeout(() => {
                if (!this.open) {
                    this.animateBubble = true;
                    this.showBubble = false;
                }
            }, 10000);

            if (this.open) this.showBubble = false;

            this.$watch('open', value => {
                if (value) {
                    // play a short beep once when opening the chat for the first time and there are no messages
                    if (!this.hasPlayedOpenSound && this.messageCount === 0) {
                        this.playOpenSound();
                        this.hasPlayedOpenSound = true;
                    }
                    this.closeBubbleInstantly();
                    this.scrollToBottom();
                    this.$nextTick(() => {
                        if (this.$refs.chatInput) {
                            this.$refs.chatInput.focus();
                        }
                    });
                }
            });
        }
    }"
    x-on:scroll-to-bottom.window="scrollToBottom()"
    class="fixed right-6 bottom-6 z-50 flex flex-col items-end"
>
    <style>
        .typing-dot {
            animation: typing 1.4s infinite;
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: #4b5563;
            margin-right: 2px;
        }
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
            margin-right: 0;
        }
        @keyframes typing {
            0%,
            60%,
            100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-4px);
            }
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .ai-avatar-gradient {
            background: linear-gradient(135deg, #4b2ea2, #00c9a7);
        }
        .chat-markdown p {
            margin-bottom: 0.5em;
        }
        .chat-markdown p:last-child {
            margin-bottom: 0;
        }
        .chat-markdown strong {
            font-weight: 700;
            color: inherit;
        }
        .chat-markdown em {
            font-style: italic;
        }
        .chat-markdown ul {
            list-style-type: disc;
            padding-left: 1.25em;
            margin-top: 0.25em;
            margin-bottom: 0.5em;
        }
        .chat-markdown li {
            margin-bottom: 0.15em;
        }
        .grecaptcha-badge {
            visibility: hidden;
        }
    </style>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="mb-4 flex w-80 flex-col overflow-hidden rounded-2xl border border-[#E2DCF5] bg-white shadow-2xl sm:w-96"
        style="height: 500px; display: none"
    >
        <div
            @click="open = false"
            class="hover:bg-opacity-95 flex cursor-pointer items-center justify-between bg-[#1A0A3B] px-4 py-4 text-white transition-all"
        >
            <div class="flex items-center gap-2">
                <div
                    class="h-2 w-2 animate-pulse rounded-full bg-green-400"
                ></div>
                <span
                    class="font-bold tracking-wide"
                    >{{ __('Support bot') }}</span
                >
            </div>
            <button class="text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>

        <div
            id="chat-container"
            x-effect="scrollToBottom()"
            class="flex flex-1 flex-col gap-3 overflow-y-auto bg-[#F5F3FA] p-4"
        >
            @if (empty($messages))
                <div class="flex justify-start">
                    <div class="px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm bg-white border border-[#E2DCF5] text-[#1C1530] rounded-bl-none">
                        <div
                            class="chat-markdown break-words"
                            x-data="{ text: @js(__('Hello, how can I help you?')) }"
                            x-html="typeof marked !== 'undefined' ? marked.parse(text) : text"
                        ></div>
                    </div>
                </div>
            @endif
            @foreach ($messages as $index => $msg)
                <div
                    class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                    wire:key="msg-wrapper-{{ $index }}-{{ strlen($msg['content']) }}"
                >
                    <div
                        class="px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm {{ $msg['role'] === 'user' ? 'bg-[#4B2EA2] text-white rounded-br-none' : 'bg-white border border-[#E2DCF5] text-[#1C1530] rounded-bl-none' }}"
                        @if ($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            x-data="{
                                fullText: @js($msg['content']),
                                displayText: '',
                                index: 0,
                                isTyping: true,
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
                            x-effect="
                                fullText = @js($msg['content']);
                                if (fullText.length > 0 && !hasStartedTypingEffect) {
                                    hasStartedTypingEffect = true;
                                    setTimeout(() => {
                                        isTyping = false;
                                        type();
                                    }, Math.floor(Math.random() * 300) + 300);
                                }
                            "
                        @endif
                    >
                        @if ($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            <template x-if="isTyping">
                                <div class="flex h-5 items-center">
                                    <span class="typing-dot"></span
                                    ><span class="typing-dot"></span
                                    ><span class="typing-dot"></span>
                                </div>
                            </template>
                            <div
                                x-show="!isTyping && displayText.length > 0"
                                class="chat-markdown break-words"
                                x-html="
                                    typeof marked !== 'undefined'
                                        ? marked.parse(displayText)
                                        : displayText
                                "
                            ></div>
                        @else
                            <div
                                class="chat-markdown break-words"
                                x-data="{ text: @js($msg['content']) }"
                                x-html="typeof marked !== 'undefined' && '{{ $msg['role'] }}' === 'assistant' ? marked.parse(text) : text"
                            ></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-[#E2DCF5] bg-white p-4">
            <form
                @submit.prevent="submitMessage()"
                class="flex items-end gap-2 rounded-xl border border-[#E2DCF5] bg-[#F5F3FA] p-2 transition-colors focus-within:border-[#4B2EA2]"
            >
                <textarea
                    x-ref="chatInput"
                    x-model="userInput"
                    rows="2"
                    @keydown.enter.prevent="submitMessage()"
                    class="hide-scrollbar flex-1 resize-none border-none bg-transparent px-2 py-1 text-sm text-[#1C1530] focus:ring-0 focus:outline-none"
                    placeholder="{{ __('Type your message...') }}"
                ></textarea>
                <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="hover:bg-opacity-90 my-auto cursor-pointer rounded-lg bg-[#4B2EA2] p-2 text-white transition-all disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg x-show="!isSubmitting" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                    <svg
                        x-show="isSubmitting"
                        class="h-5 w-5 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        style="display: none"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
            <div class="mt-2 text-center text-[10px] text-[#7A7090]">
                {{ __('Press Enter to send') }}
            </div>
        </div>
    </div>

    <div
        x-show="showBubble && !open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave.duration.300ms="animateBubble ? 'transition ease-in duration-300' : ''"
        x-transition:leave-start="animateBubble ? 'opacity-100 translate-y-0' : ''"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="mr-1 mb-3 max-w-[180px] rounded-[18px] rounded-br-[4px] border border-[#E2DCF5] bg-white p-4 text-[13px] leading-relaxed text-[#1C1530] shadow-xl"
    >
        <strong class="mb-1 block text-[12px] text-[#4B2EA2]"
            >✨ {{ __('Travel Guide') }}</strong
        >
        {{ __('Need help with anything? Ask me!') }}
    </div>

    <button
        x-show="show"
        x-transition
        @click="open = !open"
        class="ai-avatar-gradient group flex h-[44px] w-[44px] cursor-pointer items-center justify-center rounded-full text-white shadow-lg transition-all hover:scale-110"
        style="display: none"
    >
        <span
            x-show="!open"
            class="text-[20px] transition-transform group-hover:rotate-12"
            >🤖</span
        >
        <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
</div>
