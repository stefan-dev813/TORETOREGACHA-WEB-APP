<template>
    <Head title="カード設定" />

    <AdminLayout>
        <div class="mt-6 px-5">  
            <div class="text-red-600" style="white-space: break-spaces;">{{ get_product_status() }}</div>
            <h1 class="mb-2 text-lg font-bold">カード設定</h1>
            <hr class="mb-2" />
            <div v-for="(item, key) in products.data" class="mb-4  border-neutral-200 border-b">
                <div class="flex border-neutral-200 items-center">
                    <img :src="item.image" class="w-16 h-16 inline-block object-contain"/>
                    <div class="flex-1 inline-block text-sm py-1 px-2 ">
                        <div>{{item.name}}</div>
                        <div>{{item.rare}}</div>
                        <div>{{item.marks}}</div>
                        <div v-if="item.lost_type">{{item.lost_type}}</div>
                        <div class="text-red-500">{{item.point}}pt</div>
                    </div>
                </div>
            
                <div class="mb-2 text-center">
                    <button type="button" @click="modalProductOpen(item)" class="inline-block items-center px-10 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        編集
                    </button>

                    

                    <button type="button" @click="destroy_product_last(item.id)" class="inline-block items-center px-10 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        削除
                    </button>
                </div>
            </div>
            <div class="text-center mb-6">
                <button type="button" @click="modalProductOpen(0)" class="inline-block items-center px-10 py-2.5 bg-cyan-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:shadow-outline-cyan transition ease-in-out duration-150 mr-2">
                            追加
                </button>
            </div>
            
        </div>







        <template>
        <TransitionRoot as="template" :show="open">
            <Dialog as="div" class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="p-3 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form @submit.prevent="submit_last()">
                        <div class="mb-4">
                            <label  for="text1-1" class="block font-medium text-sm text-neutral-700 mb-1">名前<span class="text-red-500">*</span></label>
                            <input v-model="form_last.last_name" id="text1-1" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_name }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label  for="text1-2" class="block font-medium text-sm text-neutral-700 mb-1">変換ポイント（半角数字）<span class="text-red-500">*</span></label>
                            <input v-model="form_last.last_point" id="text1-2" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_point }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label  for="text1-3" class="block font-medium text-sm text-neutral-700 mb-1">レア度<span class="text-red-500">*</span></label>
                            <input v-model="form_last.last_rare" id="text1-3" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_rare }}
                            </div>
                        </div>


                        <div class="mb-4">
                            <label  for="text1-3" class="block font-medium text-sm text-neutral-700 mb-1">点数（半角数字）<span class="text-red-500">*</span></label>
                            <input v-model="form_last.last_marks" id="text1-3" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_marks }}
                            </div>
                        </div>

                <!--    <div class="mb-4">
                            <label  for="text1-3" class="block font-medium text-sm text-neutral-700 mb-1">種類</label>
                            <input v-model="form_last.last_lost_type" id="text1-3" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください"/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_lost_type }}
                            </div>
                        </div>
                -->

                        <div class="mb-4">
                            <label for="file1-1" class="block font-medium text-sm text-neutral-700 mb-1">画像アップロード <span class="text-red-500">*</span></label>
                            <div class="text-center w-full">
                                <img 
                                    v-if="url2"
                                    :src="url2"
                                    class="inline-block mt-4 h-24"
                                />
                            </div>
                            <input @change="previewImage2" ref="photo2" id="file1-1" type="file" class="w-full"/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_image }}
                            </div>
                        </div>
                        <div class="mb-6 text-center">
                            <button type="submit" :class="{ 'opacity-25': form_last.processing }" :disabled="form_last.processing" class="inline-block items-center px-8 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                                保存
                            </button>

                            <button type="button" @click="open = false" class="inline-block items-center px-8 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                                キャンセル
                            </button>
                        </div>
                        </form>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>
        </template>
        







    </AdminLayout>
</template>

<script>
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { Inertia } from '@inertiajs/inertia';

import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

export default {
    components: {Head, AdminLayout, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot},
    props: {
        errors: Object,
        category_share:Object,
        products: Object,
        products_status: Object,
    },
    data() {
        return {
            url2: null,
            open: false,

            point_value: 0,
        }
    },
    
    methods: {
        get_product_status() {
            let res = this.products_status.map((find)=>{
                return (parseInt(find.products_lost_count)<parseInt(find.gacha_products_count))?  ` ${find.point} ポイントのカードが ${find.gacha_products_count} つ必要です。   現在の在庫数は ${find.products_lost_count}つです。\n `:"" 
            })

            console.log(this.products_status)

            let text = "";
            res.forEach((item)=>{
                text = text + item;
            })

            return text;
        }, 
        destroy_product_last(id) {
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.gacha.product_last.destroy', {id:id}));
            }
        },
        modalProductOpen(product) {
            if (product) {
                this.form_last.last_id = product.id;
                this.form_last.last_name = product.name;
                this.form_last.last_point = product.point;
                this.form_last.last_rare = product.rare;
                this.form_last.last_marks = product.marks;
                this.form_last.last_lost_type = product.lost_type;
                this.form_last.last_image = "";
                this.form_last.is_update = 1;
                this.url2 = product.image;
            } else {
                this.form_last.last_id = "";
                this.form_last.last_name = "";
                this.form_last.last_point = "";
                this.form_last.last_rare = "";
                this.form_last.last_marks = "";
                this.form_last.last_lost_type = "";
                this.form_last.last_image = "";
                this.form_last.is_update = 0;
                this.url2 = "";
            }
            this.open = true;
        },
        submit_last() {
            if (this.$refs.photo2.files[0]) {
                this.form_last.last_image = this.$refs.photo2.files[0];
            } 
            this.form_last.post(route('admin.lost_product.create'), {
                onFinish: () => {
                    let flash = usePage().props.value.flash;
                    if (flash.message) {
                        this.open = false;
                    }
                },
            }); 
        },  

        previewImage2(e) {
            const file = e.target.files[0];
            this.url2 = URL.createObjectURL(file);
        },
    },
    setup(props) {
        let form_last_data = {
            last_id: "",
            last_name: "",
            last_point: "",
            last_rare: "",
            last_marks: "",
            last_lost_type: "",
            last_image: "",
            is_update: 0,
            category_id: props.category_share.cat_id,
        };
        const form_last = useForm( form_last_data );

        return { form_last }
    },
    mounted() {
    },
}
</script>