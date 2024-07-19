<template>
    <Head title="交換アイテム 編集" />

    <AdminLayout>

        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">交換アイテム 編集</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">タイトル（テキスト）<span class="text-red-500">*</span> </label>
                    <input v-model="form.name" id="text1" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.name" class="text-red-500 text-sm mt-1">
                        {{ errors.name }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="dp" class="block font-medium text-sm text-neutral-700 mb-1">TP（半角数字）<span class="text-red-500">*</span></label>
                    <input v-model="form.dp" id="dp" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.dp" class="text-red-500 text-sm mt-1">
                        {{ errors.dp }}
                    </div>
                </div>


                <div class="mb-8">
                    <label for="file1" class="block font-medium text-sm text-neutral-700 mb-1">画像アップロード<span class="text-red-500">*</span></label>
                    <div class="text-center w-full">
                        <img 
                            v-if="url"
                            :src="url"
                            class="inline-block mt-4 h-24"
                        />
                    </div>
                    <input @change="previewImage" ref="photo" id="file1" type="file" class="w-full"/>
                    <div v-if="errors.image" class="text-red-500 text-sm mt-1">
                        {{ errors.image }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="select0" class="block font-medium text-sm text-neutral-700 mb-1">カテゴリー<span class="text-red-500">*</span></label>
                    <select id="select0" v-model="form.category_id" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option v-for="(item, key) in category_share.categories.data" :value="item.id">{{item.title}} カード</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label  for="text3" class="block font-medium text-sm text-neutral-700 mb-1">レアリティ</label>
                    <input v-model="form.rare" id="text3" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.rare" class="text-red-500 text-sm mt-1">
                        {{ errors.rare }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="text4" class="block font-medium text-sm text-neutral-700 mb-1">型番</label>
                    <input v-model="form.product_type" id="text4" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.product_type" class="text-red-500 text-sm mt-1">
                        {{ errors.product_type }}
                    </div>
                </div>

                <div class="mb-8">
                    <label  for="select1" class="block font-medium text-sm text-neutral-700 mb-1">商品の状態<span class="text-red-500">*</span></label>
                    <select id="select1" v-model="form.status_product" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option v-for="(item, key) in productStatusTxt" :value="key">{{key}}</option>
                    </select>
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
        category_share: Object,
        product: Object,
        productStatusTxt: Object,
    },
    data() {
        return {
            url: null,
        }
    },
    methods: {
        submit() {
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
            this.form.post(route('admin.dp.update'));
        },  
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
    },
    setup(props) {
        

        const form = useForm( {
            id: props.product.id,
            name: props.product.name,
            dp: props.product.dp,
            image: '',
            category_id: props.product.category_id,
            rare: props.product.rare,
            product_type: props.product.product_type,
            status_product: props.product.status_product,
        });
        return { form }
    },
    mounted() {
        this.url = this.product.image;
    },
}
</script>