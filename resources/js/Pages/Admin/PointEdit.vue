<template>
    <Head title="ポイント購入 編集" />

    <AdminLayout>

        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">ポイント購入 編集</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">タイトル（テキスト）</label>
                    <input v-model="form.title" id="text1" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.title" class="text-red-500 text-sm mt-1">
                        {{ errors.title }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="text2" class="block font-medium text-sm text-neutral-700 mb-1">金額（半角数字）</label>
                    <input v-model="form.amount" id="text2" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.amount" class="text-red-500 text-sm mt-1">
                        {{ errors.amount }}
                    </div>
                </div>

                <div class="mb-4">
                    <label  for="point" class="block font-medium text-sm text-neutral-700 mb-1">ポイント（半角数字）</label>
                    <input v-model="form.point" id="point" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div v-if="errors.point" class="text-red-500 text-sm mt-1">
                        {{ errors.point }}
                    </div>
                </div>

                <div class="mb-8">
                    <label for="file1" class="block font-medium text-sm text-neutral-700 mb-1">画像アップロード</label>
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
        point: Object,
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
            this.form.post(route('admin.point.update'));
        },  
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
    },
    setup(props) {
        const form = useForm( {
            id: props.point.id,
            title: props.point.title,
            point: props.point.point,
            amount: props.point.amount,
            image: '',
            category_id: props.category_share.cat_id,
        })
        return {form};
    },
    mounted(){
        this.url = this.point.image;
    },
}
</script>