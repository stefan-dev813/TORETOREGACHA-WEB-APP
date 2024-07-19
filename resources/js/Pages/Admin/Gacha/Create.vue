<template>
    <Head title="ガチャ 追加" />

    <AdminLayout>

        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">ガチャ 追加</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">消費ポイント（テキスト）<span class="text-red-500">*</span></label>
                    <input v-model="form.point" id="text1" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.point" class="text-red-500 text-sm mt-1">
                        {{ errors.point }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="text2" class="block font-medium text-sm text-neutral-700 mb-1">総カード数（半角数字）<span class="text-red-500">*</span></label>
                    <input v-model="form.count_card" id="text2" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.count_card" class="text-red-500 text-sm mt-1">
                        {{ errors.count_card }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">スピンリミット <span class="text-red-500">*</span></label>
                    <input v-model="form.spin_limit" id="text1" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.spin_limit" class="text-red-500 text-sm mt-1">
                        {{ errors.spin_limit }}
                    </div>
                </div>
        <!--    <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">カードの種類指定</label>
                    <input v-model="form.lost_product_type" id="text1" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="無い場合は無記入"/>
                    <div v-if="errors.lost_product_type" class="text-red-500 text-sm mt-1">
                        {{ errors.lost_product_type }}
                    </div>
                </div>
        -->

                <div class="mb-4">
                    <label for="file1" class="block font-medium text-sm text-neutral-700 mb-1">サムネイル <span class="text-red-500">*</span></label>
                    <div class="text-center w-full">
                        <img 
                            v-if="url1"
                            :src="url1"
                            class="inline-block mt-4 h-52"
                        />
                    </div>
                    <input @change="previewImage1" ref="photo1" id="file1" type="file" class="w-full"/>
                    <div v-if="errors.thumbnail" class="text-red-500 text-sm mt-1">
                        {{ errors.thumbnail }}
                    </div>
                </div>

                <div class="mb-8">
                    <label for="file1" class="block font-medium text-sm text-neutral-700 mb-1">詳細ページ画像 <span class="text-red-500">*</span></label>
                    <div class="text-center w-full">
                        <img 
                            v-if="url"
                            :src="url"
                            class="inline-block mt-4 h-52"
                        />
                    </div>
                    <input @change="previewImage" ref="photo" id="file1" type="file" class="w-full"/>
                    <div v-if="errors.image" class="text-red-500 text-sm mt-1">
                        {{ errors.image }}
                    </div>
                </div>

                <div class="mb-8 text-center">
                    <button type="submit" class="inline-block items-center px-14 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                        保存
                    </button>
                </div>
            </form>



        </div>
    </AdminLayout>
</template>

<script>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';

export default {
    components: {Head, AdminLayout},
    props: {
        errors: Object,
        auth: Object,
        category_share:Object,
    },
    data() {
        return {
            url: null,
            url1: null,
        }
    },
    methods: {
        submit() {
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
            if (this.$refs.photo1) {
                this.form.thumbnail = this.$refs.photo1.files[0];
            }
            this.form.post(route('admin.gacha.store'));
        },  
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        previewImage1(e) {
            const file = e.target.files[0];
            this.url1 = URL.createObjectURL(file);
        },
    },
    setup(props) {
        const form = useForm( {
            point:'',
            count_card: '',
            lost_product_type: '',
            thumbnail: '',
            image: '',
            category_id: props.category_share.cat_id,
            spin_limit: '3',
        })
        return { form }
    }
}
</script>