<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">
                Capture your thoughts. Male a plan
            </p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left">
                <p>What's the idea?</p>
            </x-card>

        </header>

        <div>
            <a href="/idea" class="btn {{
                    in_array(request()->get('status'), App\IdeaStatus::values())
                    ? 'btn-outlined' : ''}}">
                All
                <span class="text-xs pl-3"> {{ $statusCounts->get('all') }}</span>
            </a>
            @foreach(App\IdeaStatus::cases() as $status)
                <a href="/idea?status={{ $status->value }}"
                   class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }}
                    <span class="text-xs pl-3"> {{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea->id) }}">
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time. </p>
                    </x-card>
                @endforelse
            </div>
        </div>

        <!-- modal -->
        <x-modal
            name="create-idea"
            title="New Idea"
            :show="$errors->hasAny(['title', 'description', 'status'])"
        >
            <form x-data="{
                        status: @js(old('status', 'pending')),
                        newLink: '',
                        links: []
                    }"
                  id="create-idea"
                  method="post"
                  action="{{ route('idea.store') }}">
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter an idea"
                        autofocus
                        required
                    />

                    <div class="space-y-2">
                        <label for="status" class="label">Status</label>
                        <div class="flex gap-x-3">
                            @foreach(\App\IdeaStatus::cases() as $status)
                                <button
                                    type="button"
                                    @click="status = @js($status->value)"
                                    data-test="button-status-{{ $status->value }}"
                                    class="btn flex-1 h-10"
                                    :class="{'btn-outlined': status !== @js($status->value)}"
                                >{{ $status->label() }}</button>
                            @endforeach
                            <input type="hidden" name="status" :value="status">
                        </div>

                        <x-form.error name="status"/>
                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        phoceholder="Description your idea..."
                    />

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links">
                                <div class="flex gap-x-2 items-center">
                                    <input name="links[]" x-model="link" class="input">
                                    <button
                                        type="button"
                                        @click="links.splice(index, 1);"
                                        aria-label="Remove link"
                                    >
                                        <x-icons.close class="form-muted-icon" />
                                    </button>
                                </div>
                            </template>

                            <div class="flex gap-x-2 items-center">
                                <input
                                    x-model="newLink"
                                    type="url"
                                    id="new-link"
                                    data-test="new-link"
                                    placeholder="https://example.com"
                                    autocomplete="url"
                                    class="input flex-1"
                                    spellcheck="false">
                                <button
                                    type="button"
                                    data-test="submit-new-link-button"
                                    @click="links.push(newLink); newLink = ''"
                                    :disabled="newLink.trim().length === 0"
                                    aria-label="Add a new link"
                                >
                                    <x-icons.plus class="form-muted-icon" />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-2 items-center">
                        <button type="button"
                                @click="$dispatch('close-modal', 'create-idea')"
                                class="btn btn-outlined text-red-500">Cancel
                        </button>
                        <button
                            form="create-idea"
                            type="submit"
                            data-test="submit-create-idea-button"
                            class="btn">Create
                        </button>
                    </div>

                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
