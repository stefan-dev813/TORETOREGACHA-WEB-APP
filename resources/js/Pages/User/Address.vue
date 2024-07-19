<template>
    <Head title="個人情報登録" />

    <AdminLayout>
        <div class="pt-6">
            <h1 class="mb-8 text-xl text-center underline underline-offset-8 font-bold">&nbsp;&nbsp;個人情報登録&nbsp;&nbsp;</h1>
            <div class="w-full text-sm md:px-12 px-6">  
                <form @submit.prevent="submit()">
                    <div class="w-full mb-4">
                        <div class="w-1/2 p-1 md:pr-8 inline-block">
                            <label for="first_name" class="mb-1 pb-2 px-5 text-lg font-bold block">姓</label>
                            <input v-model="form.first_name" id="first_name" type="text"  class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="動波"/>
                            <div v-if="errors.first_name" class="text-red-500 text-sm ">
                                {{ errors.first_name }}
                            </div>
                        </div>
                        <div class="w-1/2 p-1 inline-block">
                            <label for="last_name" class="mb-1 pb-2 px-5 text-lg font-bold block">名</label>
                            <input v-model="form.last_name" id="last_name" type="text"  class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="太郎"/>
                            <div v-if="errors.last_name" class="text-red-500 text-sm ">
                                {{ errors.last_name }}
                            </div>
                        </div>
                    </div>

                    <div class="w-full mb-4">
                        <div class="w-1/2 p-1 md:pr-8 inline-block">
                            <label for="first_name_gana" class="mb-1 pb-2 px-5 text-lg font-bold block">姓 (カナ)</label>
                            <input v-model="form.first_name_gana" id="first_name_gana" type="text"  class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="ドウバ"/>
                            <div v-if="errors.first_name_gana" class="text-red-500 text-sm ">
                                {{ errors.first_name_gana }}
                            </div>
                        </div>
                        <div class="w-1/2 p-1 inline-block">
                            <label for="last_name_gana" class="mb-1 pb-2 px-5 text-lg font-bold block">名 (カナ)</label>
                            <input v-model="form.last_name_gana" id="last_name_gana" type="text"  class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="タロウ"/>
                            <div v-if="errors.last_name_gana" class="text-red-500 text-sm ">
                                {{ errors.last_name_gana }}
                            </div>
                        </div>
                    </div>

                    <div class="w-full mb-4 p-1">
                        <label for="postal_code" class="mb-1 pb-2 px-5 text-lg font-bold block">郵便番号 (ハイフンなし半角数字)</label>
                        <input v-model="form.postal_code" id="postal_code" type="text"  class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="入力してください。"/>
                        <div v-if="errors.postal_code" class="text-red-500 text-sm ">
                            {{ errors.postal_code }}
                        </div>
                    </div>

                    <div class="w-full mb-4 p-1">
                        <label for="prefecture" class="mb-1 pb-2 px-5 text-lg font-bold block">都道府県</label>
                        <select v-model="form.prefecture" id="prefecture" class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="選択してください">
                            <option v-for="(item, key) in prefectures" :value="item">{{item}}</option>
                        </select>
                        <div v-if="errors.prefecture" class="text-red-500 text-sm ">
                            {{ errors.prefecture }}
                        </div>
                    </div>

                    <div class="w-full mb-4 p-1">
                        <label for="address" class="mb-1 pb-2 px-5 text-lg font-bold block">住所</label>
                        <input v-model="form.address" type="text" id="address" class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="例) 渋谷区神宮前1-2-3 DPビル501号室"/>
                        <div v-if="errors.address" class="text-red-500 text-sm ">
                            {{ errors.address }}
                        </div>
                    </div> 

                    <div class="w-full mb-6 p-1">
                        <label for="phone" class="mb-1 pb-2 px-5 text-lg font-bold block">電話番号 (ハイフンなし半角数字)</label>
                        <input v-model="form.phone" id="phone" type="tel" class="h-12 w-full md:px-5 px-3 py-2 border border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 placeholder-neutral-300" placeholder="入力してください"/>
                        <div v-if="errors.phone" class="text-red-500 text-sm ">
                            {{ errors.phone }}
                        </div>
                    </div>
                    <div class="w-full mb-4 mt-10 pb-8 p-1 text-center">
                        <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="inline-flex items-center px-10 py-1.5 font-semibold text-lg text-white uppercase tracking-widest hover:bg-[#896858] bg-[#60493d] transition ease-in-out duration-150">
                            保存
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
import prefectures from '@/Store/prefectures';


export default {
    components: {Head, AdminLayout, Link},
    props: {
        errors: Object,
        gacha: Object,
        category_share:Object,
        profiles: Object,
    },
    data() {
        return {
            prefectures: prefectures,
        }
    },
    methods: {
        submit () {
            this.form.post(route('user.address.post'), {
                onFinish: () => {},
            });
        },
    },
    setup(props) {
        let profile = {
            first_name:'',
            last_name:'',
            first_name_gana:'',
            last_name_gana:'',
            postal_code:'',
            prefecture: prefectures[0],
            address:'',
            phone:'',
        };
        if (props.profiles.length) {
            let item = props.profiles[0]
            profile = {
                first_name: item.first_name,
                last_name: item.last_name,
                first_name_gana: item.first_name_gana,
                last_name_gana: item.last_name_gana,
                postal_code: item.postal_code,
                prefecture: item.prefecture,
                address: item.address,
                phone: item.phone,
            };
        }

        const form = useForm( profile );
        return { form }
    },
    mounted() {
        
    },
}
</script>