<template>
    <Head title="ユーザー管理" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4 mb-6">  
            <h1 class="mb-2 text-lg font-bold">ユーザー管理</h1>
            <hr class="mb-6" />
            
            <div class="mb-6">
                <form @submit.prevent="submit()">
                    <div class="mb-8">
                        <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">メールアドレス<span class="text-red-500">*</span> </label>
                        <input v-model="form.email" id="text1" type="email" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                        <div v-if="errors.email" class="text-red-500 text-sm mt-1">
                            {{ errors.email }}
                        </div>
                    </div>
                    <div class="mb-8 text-center">
                        <button type="submit" class="inline-block items-center px-14 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                            発送リスト取得
                        </button>
                    </div>
                </form>
                
            </div>
        </div>

       
        
        
    </AdminLayout>
</template>

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { ChevronRightIcon } from "@heroicons/vue/24/outline"


export default {
    components: {Head, AdminLayout, Link, ChevronRightIcon},
    props: {
        errors: Object,
    },
    data() {
        return {
            
        }
    },
    setup(props) {
        const form = useForm( {
            email: '',
        })
        return { form }
    },
    methods: {
        submit() {
            this.form.post(route('admin.delivery.csv_post'), {
                onFinish: () => {
                    
                },
            });
        },

        
    },
}
</script>