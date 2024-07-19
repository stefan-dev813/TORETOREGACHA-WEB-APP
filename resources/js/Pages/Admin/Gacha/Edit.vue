<template>
    <Head title="ガチャ 編集" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4">  
            <div class="flex flex-wrap mb-3">
                <div class="text-right">
                    <button v-if="(gacha.status==0)" type="button" @click="toPublic(1)" :class="{ 'opacity-25': form_to_public.processing }" :disabled="form_to_public.processing" class="inline px-10 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white  tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        公開にする
                    </button>
                    <button v-else type="button" @click="toPublic(0)" :class="{ 'opacity-25': form_to_public.processing }" :disabled="form_to_public.processing" class="inline px-10 py-2.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white  tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150 mr-2">
                        非公開にする
                    </button>
                </div>
                <div class="flex-1"></div>
                <div class="text-left">
                    <button v-if="(gacha.gacha_limit_on_setting==0)" type="button" @click="gachaLimit(1)" :class="{ 'opacity-25': form_to_limit.processing }" :disabled="form_to_limit.processing" class="inline px-10 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white  tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        1日1回制限設定
                    </button>
                    <button v-else type="button" @click="gachaLimit(0)" :class="{ 'opacity-25': form_to_limit.processing }" :disabled="form_to_limit.processing" class="inline px-10 py-2.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white  tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150 mr-2">
                        1日1回制限キャンセル
                    </button>
                </div>
            </div>
            <h1 class="mb-2 text-lg font-bold">ガチャ 編集</h1>
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
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">ガチャ制限回数 <span class="text-red-500">*</span></label>
                    <input v-model="form.spin_limit" id="text1" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm  placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.spin_limit" class="text-red-500 text-sm mt-1">
                        {{ errors.spin_limit }}
                    </div>
                </div>
        <!--    <div class="mb-4">
                    <label  for="text2" class="block font-medium text-sm text-neutral-700 mb-1">消費カード数（半角数字）<span class="text-red-500">*</span></label>
                    <input v-model="form.count" id="text2" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください"/>
                    <div v-if="errors.count" class="text-red-500 text-sm mt-1">
                        {{ errors.count }}
                    </div>
                </div>
        -->
        
        <!--    <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">カードの種類指定</label>
                    <input v-model="form.lost_product_type" id="text1" type="text" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="無い場合は無記入"/>
                    <div v-if="errors.lost_product_type" class="text-red-500 text-sm mt-1">
                        {{ errors.lost_product_type }}
                    </div>
                </div>
        -->
        
                <div class="mb-4">
                    <label  for="text1" class="block font-medium text-sm text-neutral-700 mb-1">カードの指定（半角数字）</label>
                    <template v-for="(item, key) in form.lostProducts">
                        <div v-if="item.key"  class="w-full flex flex-row items-center mb-3">
                            <div class="flex-1">
                                <div class="w-1/2 inline-block pr-1">
                                    <input v-model="item.point" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="変換pt"/>
                                </div>
                                <div class="w-1/2 inline-block pr-1">
                                    <input v-model="item.count" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="枚数"/>
                                </div>
                            </div>
                            <div class="w-10 shrink h-10"> 
                                <button type="button" @click="deleteLostProduct(item.key)" class="h-full w-10 text-center">
                                    <XMarkIcon class="h-5 w-5 inline-block"/>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    
                    <div class="text-center pt-2">
                        <button type="button" @click="addLostProduct()" class="inline-block items-center px-28 py-2.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150 mr-2"> 
                            カード指定追加 
                        </button>
                    </div>
                </div>
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
                    <button type="submit" class="inline-block items-center px-28 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                        保存
                    </button>
                </div>
            </form>



            <h3 class="mb-2 text-lg font-bold">切り番 登録</h3>
            <hr class="mb-4" />
            <div v-for="(item, key) in product_last" class="mb-4  border-neutral-200 border-b">
                <div v-if="item.name" class="flex border-neutral-200 ">
                    <img :src="item.image" class="w-16 h-16 inline-block object-contain"/>
                    <div class="flex-1 inline-block text-sm py-1 px-2 ">
                        <div>{{item.name}}</div>
                        <div>{{item.rare}}</div>
                        <div>{{item.is_last}}(番)</div>
                        <div class="text-red-500">{{item.point}}pt</div>
                        <div>{{item.status}}</div>
                    </div>
                </div>
            
                <div class="mb-2 text-center">
                    <button type="button" @click="modalProductLastOpen(item)" class="inline-block items-center px-14 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        編集
                    </button>

                    <button @click="destroy_product_last(item.id)" type="button" class="inline-block items-center px-14 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        削除
                    </button>
                </div>
            </div>
            <div class="text-center mb-6">
                <button type="button" @click="modalProductLastOpen(0)" class="inline-block items-center px-28 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                    追加
                </button>
            </div>

                    

            <h3 class="mb-2 text-lg font-bold">当たりカード 登録</h3>
            <hr class="mb-2" />
            <div v-for="(item, key) in products.data" class="mb-4  border-neutral-200 border-b">
                <div class="flex border-neutral-200 items-center">
                    <img :src="item.image" class="w-16 h-16 inline-block object-contain"/>
                    <div class="flex-1 inline-block text-sm py-1 px-2 ">
                        <div>{{item.name}}</div>
                        <div>{{item.rare}}</div>
                        <div>{{item.marks}}</div>
                        <div class="text-red-500">{{item.point}}pt</div>
                    </div>
                </div>
            
                <div class="mb-2 text-center">
                    <button type="button" @click="modalProductOpen(item)" class="inline-block items-center px-14 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                        編集
                    </button>

                    

                    <button type="button" @click="destroy_product_last(item.id)" class="inline-block items-center px-14 py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        削除
                    </button>
                </div>
            </div>
            <div class="text-center mb-6">
                <button type="button" @click="modalProductOpen(0)" class="inline-block items-center px-28 py-2.5 bg-cyan-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:shadow-outline-cyan transition ease-in-out duration-150 mr-2">
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
                        <div v-if="form_last.is_last > 0" class="mb-4">
                            <label  for="text1-4" class="block font-medium text-sm text-neutral-700 mb-1">切り番（半角数字）<span class="text-red-500">*</span></label>
                            <input v-model="form_last.is_last" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.is_last }}
                            </div>
                        </div>


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

                        <div v-if="form_last.is_last == 0" class="mb-4">
                            <label  for="text1-4" class="block font-medium text-sm text-neutral-700 mb-1">点数（半角数字）<span class="text-red-500">*</span></label>
                            <input v-model="form_last.last_marks" id="text1-4" type="number" class="w-full border-neutral-300 focus:border-neutral-300 focus:ring focus:ring-neutral-200 focus:ring-opacity-50 rounded-md shadow-sm placeholder-neutral-300" placeholder="入力してください" required/>
                            <div class="text-red-500 text-sm mt-1">
                                {{ errors.last_marks }}
                            </div>
                        </div>

                        
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
                            <button type="submit" class="inline-block items-center px-8 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
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
import { XMarkIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';


export default {
    components: {Head, AdminLayout, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot, ExclamationTriangleIcon, XMarkIcon},
    props: {
        errors: Object,
        gacha: Object,
        category_share:Object,
        product_last: Object,
        products: Object,
        productsLostSetting: Object,
    },
    data() {
        return {
            url: null,
            url1: null,
            url2: null,
            open: false,
        }
    },
    methods: {
        destroy_product_last(id) {
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.gacha.product_last.destroy', {id:id}));
            }
        },
        modalProductLastOpen(product) {
            if (product) {
                this.form_last.last_name = product.name;
                this.form_last.last_point = product.point;
                this.form_last.last_rare = product.rare;
                this.form_last.last_image = "";
                this.form_last.is_update = 1;
                this.form_last.is_last = product.is_last;
                this.url2 = product.image;
                this.form_last.last_id = product.id;
            } else {
                this.form_last.last_name = "";
                this.form_last.last_point = "";
                this.form_last.last_rare = "";
                this.form_last.last_image = "";
                this.form_last.is_update = 0;
                this.url2 = "";
                this.form_last.is_last = this.gacha.count_card;
            }
            this.form_last.last_marks = 0;
            
            this.open = true;
        },
        modalProductOpen(product) {
            if (product) {
                this.form_last.last_id = product.id;
                this.form_last.last_name = product.name;
                this.form_last.last_point = product.point;
                this.form_last.last_rare = product.rare;
                this.form_last.last_marks = product.marks;
                this.form_last.last_image = "";
                this.form_last.is_update = 1;
                this.url2 = product.image;
            } else {
                this.form_last.last_id = "";
                this.form_last.last_name = "";
                this.form_last.last_point = "";
                this.form_last.last_rare = "";
                this.form_last.last_marks = 0;
                this.form_last.last_image = "";
                this.form_last.is_update = 0;
                this.url2 = "";
            }
            this.form_last.is_last = 0;
            this.open = true;
        },
        submit_last() {
            if (this.form_last.is_last>0) {
                if (this.$refs.photo2.files[0]) {
                    this.form_last.last_image = this.$refs.photo2.files[0];
                } 
                console.log(this.form_last);
                this.form_last.post(route('admin.gacha.product_last.create'), {
                    onFinish: () => {
                        let flash = usePage().props.value.flash;
                        if (flash.message) {
                            this.open = false;
                        }
                    },
                });
            } else {
                if (this.$refs.photo2.files[0]) {
                    this.form_last.last_image = this.$refs.photo2.files[0];
                } 
                this.form_last.post(route('admin.gacha.product.create'), {
                    onFinish: () => {
                        let flash = usePage().props.value.flash;
                        if (flash.message) {
                            this.open = false;
                        }
                    },
                });
            }
                
        },  

        submit() {
            let count_rest = this.form.count_card - this.gacha.count;
            var count_lost = 0; let i;
            for (i=0; i<this.form.lostProducts.length; i++){
                if (this.form.lostProducts[i].key) {
                    count_lost = count_lost + parseInt(this.form.lostProducts[i].count);
                }
            }
            let count_product = 0;
            for (i=0; i<this.products.data.length; i++){
                count_product = count_product + parseInt(this.products.data[i].marks);
            }

            let count_all = count_lost + count_product;
            let result = count_all == count_rest ? '製品数が正確です。\n' : '製品数が正確ではありません！\n';
            if (confirm(result+ "残り口数 "+ count_rest + ", 当たりカードの残り数 " + count_product + ", カード指定の残り数 " + count_lost + "  大丈夫ですか？")) {
                if (this.$refs.photo) {
                    this.form.image = this.$refs.photo.files[0];
                }
                if (this.$refs.photo1) {
                    this.form.thumbnail = this.$refs.photo1.files[0];
                }
                this.form.post(route('admin.gacha.update'));
            }
        },  

        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },

        previewImage1(e) {
            const file = e.target.files[0];
            this.url1 = URL.createObjectURL(file);
        },

        previewImage2(e) {
            const file = e.target.files[0];
            this.url2 = URL.createObjectURL(file);
        },

        toPublic(to_status) {
            this.form_to_public.to_status = to_status;
            this.form_to_public.post(route('admin.gacha.to_public'), {
                onFinish: () => {
                    
                },
            });
        },

        gachaLimit(to_status) {
            this.form_to_limit.to_status = to_status;
            this.form_to_limit.post(route('admin.gacha.gacha_limit'), {
                onFinish: () => {
                    
                },
            });
        },

        addLostProduct() {
            let key = 0;
            let i;
            for (i=0; i<this.form.lostProducts.length; i++){
                if (key<this.form.lostProducts[i].key) {
                    key = this.form.lostProducts[i].key;
                }
            }
            key = key + 1;
            this.form.lostProducts.push({id: 0, point:0, count:0, key: key});
        },
        deleteLostProduct(key) {
            let i;
            console.log(this.form.lostProducts);
            for (i=0; i<this.form.lostProducts.length; i++){
                if (key==this.form.lostProducts[i].key) {
                    this.form.lostProducts[i].key = 0;
                    break;
                }
            }
        }
    },
    setup(props) {
        const form = useForm( {
            id: props.gacha.id,
            point: props.gacha.point,
            count_card: props.gacha.count_card,
            count: props.gacha.count,
            lost_product_type: props.gacha.lost_product_type,
            thumbnail: '',
            image: '',
            category_id: props.category_share.cat_id,
            lostProducts : [],
            spin_limit: props.gacha.spin_limit,
        });

        let form_last_data = {
            last_id: "",
            last_name: "",
            last_point: "",
            last_rare: "",
            last_marks: "",
            last_image: "",
            is_last: 0,
            gacha_id : props.gacha.id,
            is_update: 0,
        };
        
        const form_last = useForm( form_last_data );

        const form_to_public = useForm( {gacha_id: props.gacha.id, to_status: 1} );
        const form_to_limit = useForm( {gacha_id: props.gacha.id, to_status: 1} );

        return { form, form_last, form_to_public, form_to_limit }
    },
    mounted() {
        this.url = this.gacha.image;
        this.url1 = this.gacha.thumbnail;

        this.form.lostProducts = [];
        let i;
        for (i=0; i<this.productsLostSetting.length; i++) {
            let item = this.productsLostSetting[i];
            this.form.lostProducts.push({id: item.id, count: item.count, point: item.point, key: item.id});
        }
    },
}
</script>