
<template> 
    <AdminLayout>
        <Head title="パスワードリセット" />
        <div class="pt-6 md:px-20 px-4 bg-custom">  
            <h1 class="mb-10 text-xl text-center font-bold">パスワードリセット</h1>

            <div v-if="status" class="mb-4 font-medium text-sm w-full text-center text-green-600">
                {{ status }}
            </div>

            <div class="pl-4 mb-3 text-lg font-bold text-gray-600">
                登録時のメールアドレスを入力してください！
            </div>

            

            <form @submit.prevent="submit">
                <div class="mb-6">
                    <!-- <label  for="email" class="block font-medium text-sm text-neutral-600 mb-1">メールアドレス</label> -->
                    <input v-model="form.email" id="email" type="email" class="h-12 shadow-xl rounded-xl w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="例) user@toretoreoripa.com"/>
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>


                <div class="flex items-center justify-center mt-4 pb-7">
                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-block items-center px-10 py-2.5 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                        パスワードリセットメールを送信
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script>
    import AdminLayout from '@/Layouts/Admin.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
    


    export default {
        components: {  Head, AdminLayout, InputError, InputLabel, TextInput, Link, PrimaryButton },
        data: () => ({
            passwordFieldType: "password",
        }),
        props: {
            status: String,
        },
        methods: {
            submit () {
                    this.form.post(route('password.email'), {
                        onFinish: () => {
                            
                        },
                    });
            },
        },
        setup() {
            const form = useForm( {
                email:'',
            })

            return { form }
        },
        mounted(){
            
        },
    }
</script>
