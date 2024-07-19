<template>
    
    <AdminLayout>

        <div class="pt-6 md:px-2 px-4">  
            <h1 v-if="editing" class="mb-2 text-lg font-bold">ポイント配布編集</h1>
            <h1 v-else class="mb-2 text-lg font-bold">ポイント配布追加</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <input v-model="form.id" class="hidden"/>
                <div class="mb-6">
                    <label  for="title" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">お名前 (テキスト) <span class="text-red-500">*</span></label>
                    <input v-model="form.title" id="title" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.title" class="text-red-500 text-sm mt-1">
                        {{ errors.title }}
                    </div>
                </div>
                

                <div class="mb-6">
                    <label  for="code" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">コード(テキスト) <span class="text-red-500">*</span></label>
                    <input v-model="form.code" id="code" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.code" class="text-red-500 text-sm mt-1">
                        {{ errors.code }}
                    </div>
                </div>

                <div class="mb-6">
                    <label  for="point" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">ポイント (半角数字) <span class="text-red-500">*</span></label>
                    <input v-model="form.point" id="point" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.point" class="text-red-500 text-sm mt-1">
                        {{ errors.point }}
                    </div>
                </div>
        
                <div class="mb-6">
                    <label  for="expiration" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">有効期限 <span class="text-red-500">*</span></label>
                    <VueDatePicker v-model="form.expiration" format="Y-M-d HH:mm"></VueDatePicker>
                    <!-- <input v-model="form.expiration" id="expiration" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください"/> -->
                    <div v-if="errors.expiration" class="text-red-500 text-sm mt-1">
                        {{ errors.expiration }}
                    </div>
                </div>

                <div class="mb-6">
                    <label  for="user_limit" class="block font-medium text-sm text-neutral-700 mb-2 ml-1">人数上限 (半角数字) <span class="text-red-500">*</span></label>
                    <input v-model="form.user_limit" id="user_limit" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.user_limit" class="text-red-500 text-sm mt-1">
                        {{ errors.user_limit }}
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/2">
                        <button type="submit" class="inline-block items-center w-full py-2.5 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-green-700 transition ease-in-out duration-150">
                            保存
                        </button>
                    </div>
    
                    <div class="w-1/2">
                        <Link :href="route('admin.coupon')" class="text-center inline-block items-center w-full py-2.5 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 transition ease-in-out duration-150" as="button">
                            戻る
                        </Link>
                    </div>

                </div>
            </form>



        </div>
    </AdminLayout>
</template>

<script>
import { Head, useForm, usePage, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    components: {Head, AdminLayout, Link, VueDatePicker},
    props: {
        errors: Object,
        auth: Object,
        coupon:Object,
        editing:Object,
    },
    methods: {
        submit() {
            this.form.post(route('admin.coupon.store'));
        }
    },
    computed : {
        flash() {
            return usePage().props.value.flash;
        } 
    },
    watch : {
        flash: function(newVal, oldVal) {
            this.form.title = newVal.data.title;
            this.form.code = newVal.data.code;
            this.form.point = newVal.data.point;
            this.form.expiration = newVal.data.expiration;
            this.form.user_limit = newVal.data.user_limit;
        }
    },
    setup(props) {
        const form = useForm( {
            id : props.coupon.id,
            title : props.coupon.title,
            code : props.coupon.code,
            point : props.coupon.point,
            expiration : props.coupon.expiration,
            user_limit : props.coupon.user_limit
        })
        return { form }
    }
}
</script>