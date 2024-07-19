<template>
    <AdminLayout>
        <Head title="ログイン"/>
        <div class="pt-6 md:px-2 px-4">
            <h1 class="mb-2 text-lg font-bold">ログイン</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <div class="mb-6">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">メールアドレス</label>
                    <input v-model="form.email" id="text1" type="email" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="例) user@Toretoreoripa.com"/>
                    <div v-if="errors.email" class="text-red-500 text-sm mt-1">
                        {{ errors.email }}
                    </div>
                </div>

                <div class="mb-2">
                    <label  for="text2" class="block font-medium text-sm text-neutral-700 mb-1">パスワード</label>
                    <input v-model="form.password" id="text2" :type="passwordFieldType" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="半角英数字6～20文字"/>
                    <div v-if="errors.password" class="text-red-500 text-sm mt-1">
                        {{ errors.password }}
                    </div>
                </div>
                <div class="block mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" @click="switchVisibility()" class="rounded border-neutral-300 text-neutral-600 shadow-sm focus:ring-neutral-500"/>
                        <span class="ml-2 text-sm ">パスワードを表示</span>
                    </label>
                </div>


                <div class="mb-6 text-center">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-block items-center px-14 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        保存
                    </button>
                </div>
                <div class="mb-6 flex items-center justify-center mt-5">
                    <Link :href="route('register')" class="underline text-sm text-cyan-500 hover:text-cyan-700 active:text-cyan-700 ">新規会員登録はこちら</Link>
                </div>
            </form>

        
        </div>
        
    </AdminLayout>

</template>
<script>
    import axios from 'axios';
    import Checkbox from '@/Components/Checkbox.vue';
    import AdminLayout from '@/Layouts/Admin.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    
    import TextInput from '@/Components/TextInput.vue';
    import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
    import { ref } from 'vue';


    export default {
        components: { Checkbox, Head, AdminLayout, InputError, InputLabel, TextInput, Link },
        data: () => ({
            passwordFieldType: "password",
        }),
        props: {
            errors: Object
        },
        methods: {
            submit () {
                    this.form.post(route('login'), {
                        onFinish: () => {
                            this.form.reset('password');
                        },
                    });
            },
            
            switchVisibility () {
                this.passwordFieldType = this.passwordFieldType==="password"?"text":"password";
            },
        },
        setup() {
            const form = useForm( {
                email:'',
                password:'',
            })

            return { form }
        },
        mounted(){
            
        },
    }
</script>