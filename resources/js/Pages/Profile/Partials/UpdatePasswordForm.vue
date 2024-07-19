<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium">パスワードを更新する</h2>
            <p class="mt-1 text-sm">安全を確保するには、アカウントで長くてランダムなパスワードを使用するようにしてください。</p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="現在のパスワード"  class="pl-2" />
                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    />
                <InputError :message="form.errors.current_password" class="ml-2 mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="新しいパスワード"  class="pl-2"/>
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    />
                <InputError :message="form.errors.password" class="ml-2 mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="パスワードの確認" class="pl-2" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    />
                <InputError :message="form.errors.password_confirmation" class="ml-2 mt-2" />
            </div>

            <div class="flex gap-4 items-center">
                <div class="text-center border border-[#d9cd93] rounded-full p-0.5 w-fit">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-block items-center px-10 py-1 bg-gradient-to-b from-[#d9cd93] to-[#70612a] rounded-full font-semibold text-md text-white uppercase tracking-widest hover:bg-indigo-900 active:bg-indigo-900">
                        保存
                    </button>
                </div>
                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm">保存されました。</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
