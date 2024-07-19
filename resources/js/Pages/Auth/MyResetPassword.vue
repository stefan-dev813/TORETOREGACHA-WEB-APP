<template>
    <AdminLayout>
        <Head title="パスワードの再設定" />
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">パスワードの再設定</h1> 
            <hr class="mb-8" />

            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label  for="email" class="block font-medium text-sm text-neutral-700 mb-1">メールアドレス</label>
                    <input v-model="form.email" id="email" type="email" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="例) user@toretoreoripa.com" required/>
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>
                
                <div class="mb-6">
                    <label  for="password" class="block font-medium text-sm text-neutral-700 mb-1">パスワード</label>
                    <input v-model="form.password" id="password" type="password" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="半角英数字6～20文字" required/>
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="mb-6">
                    <label  for="password_confirmation" class="block font-medium text-sm text-neutral-700 mb-1">パスワードの認証</label>
                    <input v-model="form.password_confirmation" id="password_confirmation" type="password" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="半角英数字6～20文字" required/>
                    <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <div class="mb-6 text-center">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-block items-center px-10 py-2.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                        パスワードの再設定
                    </button>
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
            email: String,
            token: String,
        },
        methods: {
            submit () {
                this.form.post(route('password.store'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                });
            },
        },
        setup(props) {
            const form = useForm( {
                token: props.token,
                email: props.email,
                password: '',
                password_confirmation: '',
            })

            return { form }
        },
        mounted(){
            
        },
    }
</script>