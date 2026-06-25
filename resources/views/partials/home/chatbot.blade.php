@php
    $chatbotService = app(\App\Services\OrasiChatbotService::class);
    $chatWelcome = 'Selamat datang. Perkenalkan, saya <strong>Sasi</strong>, asisten informasi Portal Orasi Ilmiah Guru Besar Universitas Mulawarman. Saya dapat membantu menelusuri agenda, profil guru besar, statistik, video, dokumen, serta pencarian berdasarkan kata kunci.';
    $chatSuggestions = $chatbotService->welcomeSuggestions();
@endphp

<link href="{{ asset('css/orasi-chatbot.css') }}" rel="stylesheet" type="text/css">

<div
    id="orasi-chatbot"
    class="orasi-chatbot"
    aria-live="polite"
    data-endpoint="{{ route('home.chatbot') }}"
    data-csrf="{{ csrf_token() }}"
    data-avatar="{{ asset('avatar-chat.png') }}"
    data-welcome="{{ $chatWelcome }}"
    data-suggestions='@json($chatSuggestions)'
>
    <div class="orasi-chatbot__panel" data-chat-panel role="dialog" aria-label="Chat Sasi">
        <div class="orasi-chatbot__header">
            <img
                class="orasi-chatbot__header-avatar"
                data-chat-avatar
                src="{{ asset('avatar-chat.png') }}"
                alt="Avatar Sasi"
                width="42"
                height="42"
            >
            <div class="orasi-chatbot__header-text">
                <strong>Sasi</strong>
                <span>Asisten informasi Orasi Ilmiah Guru Besar Universitas Mulawarman</span>
            </div>
            <button type="button" class="orasi-chatbot__close" data-chat-close aria-label="Tutup chat">&times;</button>
        </div>

        <div class="orasi-chatbot__messages" data-chat-messages>
            <div class="orasi-chatbot__typing" data-chat-typing hidden>Mengetik...</div>
        </div>

        <div class="orasi-chatbot__suggestions" data-chat-suggestions></div>

        <form class="orasi-chatbot__form" data-chat-form autocomplete="off">
            <input
                type="text"
                class="orasi-chatbot__input"
                data-chat-input
                placeholder="Ketik kata kunci atau pertanyaan..."
                maxlength="500"
                aria-label="Pertanyaan untuk Sasi"
            >
            <button type="submit" class="orasi-chatbot__send" data-chat-send>Kirim</button>
        </form>
    </div>

    <button type="button" class="orasi-chatbot__nudge" data-chat-toggle aria-label="Buka chat Sasi">
        <strong>Sasi</strong>
        <span>Klik untuk bantuan informasi.</span>
    </button>

    <button type="button" class="orasi-chatbot__toggle" data-chat-toggle aria-label="Buka chat Sasi">
        <img data-chat-avatar src="{{ asset('avatar-chat.png') }}" alt="Buka chat Sasi" width="64" height="64">
    </button>
</div>

<script src="{{ asset('js/orasi-chatbot.js') }}" defer></script>
