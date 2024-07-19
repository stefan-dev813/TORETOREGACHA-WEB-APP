<template>
    <Head title="TP交換所 – 詳細" />
    <AdminLayout>

        <div class="w-full md:flex mx-auto bg-custom mb-6" style="min-height:400px">
            <div class="md:w-1/2 py-9 px-6">
                <img :src="products.data[0].image" class="w-full h-[100%] object-cover block"/>
            </div>
            <div class="md:w-1/2 py-9 px-6">
                <h1 class="font-bold mb-6 text-[32px]">{{ products.data[0].name }}</h1>
                <div class="mb-4 text-lg font-bold">
                    <span class="text-neutral-500">{{format_number(products.data[0].dp) }}</span> 
                    <span class="text-neutral-500"> TP </span>
                    <span class="bg-cyan-600 rounded text-cyan-50 px-1.5 ml-3 text-sm">{{products.data[0].status_product}} </span>
                    <span v-if="productStatusTxt[products.data[0].status_product]" class="text-cyan-600 ml-2 text-sm font-bold">{{ productStatusTxt[products.data[0].status_product].short }}</span>
                </div>
                <button @click="exchange()" class="w-full mb-4 block text-center md:w-2/3 py-2.5 bg-sky-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 active:bg-sky-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                    交換する
                </button>

                <button v-if="favorite" @click="add_favorite(0)" class="w-full mb-6 md:w-2/3 block text-center py-2.5  bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-400 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                    お気に入り解除
                </button>

                <button v-else @click="add_favorite(1)" class="w-full mb-8 block md:w-2/3  text-center py-2.5 text-neutral-500 border-neutral-500 bg-neutral-50 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-neutral-200 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                    お気に入り登録
                </button>

                <div class="mb-8 p-4 pb-1 rounded-md bg-white"> 
                    <p class="mb-1 text-xl font-bold">商品の詳細</p>
                    <hr class="mb-2"/>
                    <div class="w-full mb-6">
                        <table class="w-full text-sm">
                            <tr>
                                <td class="p-1 w-2/5 font-bold">カテゴリー</td>
                                <td class="p-1 w-3/5">{{ category }}</td> 
                            </tr>
                            <tr>
                                <td class="p-1 w-2/5 font-bold">型番</td>
                                <td class="p-1 w-3/5">{{ products.data[0].product_type }}</td>
                            </tr>
                            <tr>
                                <td class="p-1 w-2/5 font-bold">レアリティ</td>
                                <td class="p-1 w-3/5">{{ products.data[0].rare }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div v-if="productStatusTxt[products.data[0].status_product]" class="mb-8 p-4 rounded-md bg-white">
                    <p class="mb-1 text-xl font-bold ">商品の状態</p>
                    <hr class="mb-4"/>
                    <div class="w-full mb-2">
                        <span class="bg-cyan-600 rounded text-cyan-50 px-2 text-xl">{{ products.data[0].status_product }} </span>
                        <span class="text-cyan-600 ml-2 text-base ">{{ productStatusTxt[products.data[0].status_product].short }}</span>
                    </div>
                    <div class="text-sm mb-3">
                        {{ productStatusTxt[products.data[0].status_product].long }}
                    </div>
                    <div class="text-left text-sm">
                        <Link :href="route('main.status_estimate')" class="text-cyan-600 underline">
                            状態評価の基準はこちら
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <TransitionRoot as="template" :show="open">
            <Dialog as="div" class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="text-center text-sm pt-3 text-neutral-600 ">
                            <div class="mb-2 text-lg px-2">
                                <ExclamationTriangleIcon class="w-5 h-5 inline-block"/> 入力エラー
                            </div>
                            <div class="py-3 text-sm px-2">
                                配送先情報を入力してください！
                            </div>
                            <hr/>
                            <div class="pt-3 px-2">
                                <button @click="open=false" class="mb-4 mx-1 text-center px-4 py-1.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                                    キャンセル
                                </button>
                                <Link :href="route('user.address')" class="mb-4 mx-1 text-center px-4 py-1.5 bg-neutral-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                                    配送先情報入力
                                </Link>
                            </div>
                        </div>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>
    </AdminLayout>
</template>

<script>
import { Head, Link, useForm  } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import Footer from '@/Parts/Footer.vue';

import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

export default {
    components: {Head, AdminLayout, Footer, Link, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot, ExclamationTriangleIcon, XMarkIcon},
    props: {
        errors: Object,
        products: Object,
        category_share:Object,
        favorite: Number,
        auth: Object,
        productStatusTxt: Object,
        profile: Object,
    },
    data() {
        return {
            is_busy: false,
            open: false,
            category: "",
        }
    },
    methods: {
        format_number(n) {
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
        exchange() {
            if (this.profile.address) {
                if (this.auth.user.dp<this.products.data[0].dp) {
                    alert('TP不足、交換に必要なTPが不足しています。');
                } else {
                    if (confirm("交換しますか?  "+ this.products.data[0].dp +"TPを消費してこの景品と交換します。")) {
                        this.form.post(route('user.dp.detail.post'), {
                            onFinish: () => {
                            },
                        });
                    }
                }
            } else {
                this.open = true;

            }   
        },
        add_favorite(value) {
            this.form.value = value;
            this.form.post(route('user.favorite.add'), {
                onFinish: () => {
                },
            });


            // const post_data = { id: this.products.data[0].id, value: value};
            // this.is_busy = true;
            // axios.post(route('user.favorite.add'), post_data)
            // .then(response=>{ 
            //     if(response.status == 201 || response.status == 200) {
            //         if(response.data.status) {
            //             if (value) {

            //             } else {

            //             }
            //         }
            //     }
            //     this.is_busy = false;
            // }).catch(error=> {
            //     this.is_busy = false;
            //     console.log(error);
            // });

            // this.form.post(route('user.favorite.add'), {
            //     onFinish: () => {
            //     },
            // });
        }   
    }, 
    setup(props) {
        const form = useForm( {
            id: props.products.data[0].id,
            value: 0,
        })
        return { form }
    },
    mounted () {
        if (this.category_share.categories.data) {
            let i;
            for (i=0; i<this.category_share.categories.data.length; i++) {
                let item = this.category_share.categories.data[i];
                if (item.id==this.category_share.cat_id) {
                    this.category = item.title;
                }
            } 
        }
    },
}
</script>

