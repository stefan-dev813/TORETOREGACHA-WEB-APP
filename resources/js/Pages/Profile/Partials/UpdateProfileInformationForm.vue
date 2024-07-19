<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.value.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone
});

const verify_form = useForm({
    step_integer: 0,
    phone: "",
    code: "",
});

const submit = () => {
    verify_form.phone = form.phone;
    if (verify_form.step_integer == 0) {
        verify_form.post(route('register.phone.send'), {
            onFinish: () => {
                let result = usePage().props.value.flash;

                if (result.data) {
                    if (result.data.status == 1) {
                        verify_form.step_integer = 1;
                    }
                }

            },
        });
    } else if (verify_form.step_integer == 1) {
        verify_form.post(route('register.phone.verify'), {
            onFinish: () => {
                let result = usePage().props.value.flash;
                if (result.data) {
                    if (result.data.status == 1) {
                        verify_form.step_integer = 2;
                        
                    }
                    else {
                        verify_form.code = 0;
                        verify_form.step_integer = 0;
                    }
                }
            },
        });
    }
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium">プロフィール情報</h2>
            <p class="mt-1 text-sm">アカウントのプロフィール情報と電子メール アドレスを更新します。</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6 ">
            

            <div>
                <InputLabel for="name" value="名前" class=" pl-2" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="電子メールアドレス" class=" pl-2" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="email" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="phone" value="電話番号" class=" pl-2" />
                <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required
                    autocomplete="phone" />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>
            <!-- <div v-if="user.email_verified_at === null">

                <form @submit.prevent="submit">
                    <div v-if="verify_form.step_integer == 0">
                        <p class="text-sm mt-2 ">
                            電話番号は認証されていません。電話認証を行ってポイントを受け取ってください。
                        </p>

                        <div class="flex items-center justify-center pt-4">
                            <button type="submit" :class="{ 'opacity-25': verify_form.processing }"
                                :disabled="verify_form.processing"
                                class="inline-flex items-center px-10 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-900 active:bg-indigo-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                                認証へ進む
                            </button>
                        </div>
                    </div>
                    <div v-if="verify_form.step_integer == 1">
                        <div class="mb-8">
                            <InputLabel for="code" class="block text-md font-bold text-neutral-700 pl-4 mb-4"
                                value="smsに記載された4桁の認証コードを入力してください。" />
                            <input v-model="verify_form.code" id="code" type="tel"
                                class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300"
                                placeholder="認証コードを入力してください" />

                            <InputError class="mt-2" :message="verify_form.errors.code" />
                        </div>

                        <div class="flex items-center justify-center py-2">
                            <button type="submit" :class="{ 'opacity-25': verify_form.processing }"
                                :disabled="verify_form.processing"
                                class="inline-flex items-center px-10 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 active:bg-red-800 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                                確認
                            </button>
                        </div>
                    </div>
                </form>
            </div> -->

            <div class="flex gap-4 items-center">
                <div class="text-center border border-[#d9cd93] rounded-full p-0.5 w-fit">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="inline-block items-center px-10 py-1 bg-gradient-to-b from-[#d9cd93] to-[#70612a] rounded-full font-semibold text-md text-white uppercase tracking-widest hover:bg-indigo-900 active:bg-indigo-900">
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
