<section
    class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
>
    <section class="flex justify-end items-center gap-2">
        @if(!empty($user))
            <button type="button" wire:click="logout" class="px-2 py-1 rounded-sm bg-teal-400 text-black">
                {{ Lang::get('livewire.button_logout') }}
            </button>
        @else
            <input type="email" wire:model.live="email" class="bg-black">
            <input type="password" wire:model.live="password" class="bg-black">

            <button type="button" wire:click="login" class="px-2 py-1 rounded-sm bg-teal-400 text-black">
                {{ Lang::get('livewire.button_login') }}
            </button>
        @endif
    </section>
</section>

