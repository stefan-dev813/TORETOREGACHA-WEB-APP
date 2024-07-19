<template>
    <Head title="バナー設定" />

    <AdminLayout>

        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">バナー設定</h1>
            <hr class="mb-8" />
            <form @submit.prevent="submit()">
                <div class="mb-4">
                    <label  for="link_url" class="block font-medium text-sm text-neutral-700 mb-1">リンクURL</label>
                    <input v-model="form.link_url" id="link_url" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.link_url" class="text-red-500 text-sm mt-1">
                        {{ errors.link_url }}
                    </div>
                </div>

                <div class="mb-8">
                    <label for="file" class="block font-medium text-sm text-neutral-700 mb-1">バナー画像 <span class="text-red-500">*</span></label>
                    <div class="text-center w-full">
                        <img 
                            v-if="url"
                            :src="url"
                            class="inline-block mt-4 h-52"
                        />
                    </div>
                    <input @change="previewImage" ref="photo" id="file" type="file" class="mt-4 w-full"/>
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
        banner: Object
    },
    data() {
        return {
            url: this.banner.image,
        }
    },
    methods: {
        submit() {
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
            this.form.post(route('admin.banner.store'));
        },  
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
    },
    setup(props) {
        const form = useForm( {
            image: '',
            link_url: props.banner.link_url,
        })
        return { form }
    }
}
</script>