<template>
    <AdminLayout>
        <Head title="ログイン"/>
        <div class="pt-6 md:px-16 px-4 md:mx-0 mx-4">  
            <h1 class="underline underline-offset-[12px] mb-10 text-center text-xl font-bold">&nbsp;&nbsp;&nbsp;ログイン&nbsp;&nbsp;&nbsp;</h1> 
            <form @submit.prevent="submit()">
                <div class="mb-6">
                    <label  for="text1" class="block font-medium text-md font-bold text-neutral-700 pl-4 mb-4">メールアドレス</label>
                    <input v-model="form.email" id="text1" type="email" class="rounded-xl w-full h-12 border-neutral-300 shadow-xs focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="例) user@ara-llc.co.jp"/>
                    <div v-if="errors.email" class="text-red-500 text-sm mt-1">
                        {{ errors.email }}
                    </div>
                </div>

                <div class="mb-10">
                    <label  for="text2" class="block font-medium text-lg font-bold text-neutral-700 pl-4 mb-4">パスワード</label>
                    <input v-model="form.password" id="text2" :type="passwordFieldType" class="rounded-xl w-full h-12 shadow-xs border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="半角英数字6～20文字"/>
                    <div v-if="errors.password" class="text-red-500 text-sm mt-1">
                        {{ errors.password }}
                    </div>
                </div>
                <div class="block mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" @click="switchVisibility()" class="w-6 h-6 rounded border-neutral-300 text-neutral-600 shadow-sm focus:ring-neutral-500"/>
                        <span class="ml-2 text-lg">パスワードを表示</span>
                    </label>
                </div>


                <div class="mb-8 text-center">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-block items-center px-10 py-1.5 bg-[#60493d] hover:bg-[#896858] font-semibold text-md text-white uppercase tracking-widest">
                        ログイン
                    </button>
                </div>
                <div class="flex gap-6 justify-center">
                    <div class="mb-8 flex items-center justify-center mt-5">
                        <Link :href="route('password.request')" class="underline underline-offset-4 text-sm text-black hover:text-blue-500 active:text-blue-700 font-[600]">パスワードをお忘れの方はこちら</Link>
                    </div>
                    <div class="mb-8 flex items-center justify-center mt-5">
                        <Link :href="route('register')" class="underline underline-offset-4 text-sm text-black hover:text-blue-500 active:text-blue-700 font-[600]">新規会員登録はこちら</Link>
                    </div>
                </div>
            </form>

        
        </div>
        
    </AdminLayout>

</template>
<script>
    import AdminLayout from '@/Layouts/Admin.vue';
    import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
    
    export default {
        components: {  Head, AdminLayout, Link },
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