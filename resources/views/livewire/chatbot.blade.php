<div
    x-data="{ show: false, open: @entangle('isOpen') }"
    x-init="setTimeout(() => show = true, 30)"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end"
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
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; margin-right: 0; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-4px); }
        }
    </style>

    <div
        x-show="open"
        x-transition
        class="bg-white shadow-xl border border-gray-200 rounded-lg w-80 sm:w-96 flex flex-col mb-4 overflow-hidden"
        style="height: 500px; display: none;"
    >
        <div class="bg-navy text-white px-4 py-3 flex justify-between items-center">
            <span class="font-bold">Support Bot</span>
            <button @click="open = false" class="text-white hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div id="chat-container"
            x-on:scroll-to-bottom.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
            x-effect="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
            class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-3">
            @foreach($messages as $index => $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                    wire:key="msg-wrapper-{{ $index }}-{{ strlen($msg['content']) }}"> {{-- Dynamický klíč vynutí refresh při změně délky --}}

                    <div
                        class="px-4 py-2 rounded-lg max-w-[85%] {{ $msg['role'] === 'user' ? 'bg-purple text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-none' }}"
                        @if($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            x-data="{
                                fullText: @js($msg['content']),
                                displayText: '',
                                index: 0,
                                isTyping: true,
                                init() {
                                    this.checkStatus();
                                },
                                checkStatus() {
                                    if (this.fullText.length > 0) {
                                        // Máme text, přestaneme ukazovat tečky a píšeme
                                        setTimeout(() => {
                                            this.isTyping = false;
                                            this.type();
                                        }, 400);
                                    } else {
                                        // Text je prázdný, stále čekáme (ukazujeme tečky)
                                        this.isTyping = true;
                                    }
                                },
                                type() {
                                    if (this.index < this.fullText.length) {
                                        let batchSize = Math.floor(Math.random() * 5) + 2;
                                        let nextChunk = this.fullText.slice(this.index, this.index + batchSize);
                                        this.displayText += nextChunk;
                                        this.index += nextChunk.length;

                                        let delay = Math.floor(Math.random() * 20) + 10;
                                        setTimeout(() => this.type(), delay);

                                        $nextTick(() => {
                                            let container = document.getElementById('chat-container');
                                            container.scrollTop = container.scrollHeight;
                                        });
                                    }
                                }
                            }"
                            {{-- Sledujeme změnu fullText z Livewire --}}
                            x-effect="fullText = @js($msg['content']); if(fullText.length > 0 && isTyping) { checkStatus(); }"
                        @endif
                    >
                        @if($msg['role'] === 'assistant' && ($msg['should_type'] ?? false))
                            <template x-if="isTyping">
                                <div class="flex items-center h-5">
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                </div>
                            </template>
                            <span x-show="!isTyping" x-text="displayText"></span>
                        @else
                            <span>{{ $msg['content'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-3 bg-white border-t border-gray-200">
            <form wire:submit="sendMessage" class="flex gap-2">
                <input
                    type="text"
                    wire:model="userInput"
                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-purple focus:border-purple text-sm text-gray-800"
                    placeholder="Type a message..."
                >
                <button
                    type="submit"
                    class="bg-purple text-white px-4 py-2 rounded-md hover:bg-purpleMid transition-colors flex items-center justify-center"
                    wire:loading.attr="disabled"
                >
                    <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <svg wire:loading wire:target="sendMessage" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <button
        x-show="show"
        x-transition
        @click="open = !open"
        class="w-14 h-14 bg-purple text-white rounded-full shadow-lg flex items-center justify-center hover:scale-105 transition-transform"
        style="display: none;"
    >
        <svg x-show="!open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
