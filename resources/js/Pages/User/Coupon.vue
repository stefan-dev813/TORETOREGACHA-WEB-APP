<template>
    <Head title="ガチャ管理" />

    <AdminLayout>

        <div>
            <div class="text-center w-full p-4 mb-2 font-semibold">
                <h3>プレゼントポイントの獲得</h3>
            </div>
            <div>
                <form @submit.prevent="submit()">
                    <div class="mb-4">
                        <label  for="code" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">コードを入力してください! <span class="text-red-500">*</span></label>
                        <input v-model="form.code" id="code" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                        <div v-if="errors.code" class="text-red-500 text-sm mt-1">
                            {{ errors.code }}
                        </div>
                    </div>
                    <button type="submit" class="w-full inline-block items-center px-8 py-2.5 bg-teal-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        獲得
                    </button>
                    
                </form>
            </div>
            <div class="w-full mt-8">
                <h3 class="underline underline-offset-4">獲得履歴</h3>
                <table class="w-full table-fixed">
                    <thead>
                        <tr class="border-b border-collapse">
                            <td class="text-center py-2">タイトル</td>
                            <td class="text-center py-2">ポイント</td>
                            <td class="text-center py-2">獲得時間</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="coupon in coupons" class="border-b border-collapse">
                            <td class="text-center py-2">{{ coupon.title }}</td>
                            <td class="text-center py-2">{{ coupon.point }} pt</td>
                            <td class="flex justify-center py-2">{{ coupon.acquired_time }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template> 

<script>
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {Head, AdminLayout, Link},
    props: {
        coupons: Object,
        errors:Object,
    },
    mounted() {
        
    },
    methods: {
        submit() {
            this.form.post(route('user.coupon.post'));
        }
    },
    setup(props) {
        const form = useForm( {
            code : '',
        })
        return { form }
    }
}
</script>