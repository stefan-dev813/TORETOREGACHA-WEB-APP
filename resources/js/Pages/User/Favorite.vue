<template>
    <Head title="お気に入り一覧" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4" style="min-height:400px">  
            <div class="bg-custom py-6 pb-12 px-6">
                <h1 class="mb-2 text-[24px] text-center font-bold underline">お気に入り一覧</h1>
            </div>
            <div v-if="products.data.length" class="mb-8">
                <template v-for="(item, key) in products.data">
                    <Link :href="route('user.dp.detail', {id: item.product.id})"  class="py-3 px-4 bg-custom flex items-start justify-between mb-1">
                        <img :src="item.product.image" class=" h-32 w-24 inline-block object-cover"/>
                        <div class="flex-1 inline-block text-sm px-2 ">
                            <div class="text-lg font-bold px-3">{{item.product.name}}</div>
                            <div class="text-md md:py-2 py-1 mb-4 px-3">{{item.product.rare}}</div>
                            <div class="py-1 mx-3 bg-red-500 text-white rounded-full w-24 text-center">{{format_number(item.product.dp)}}TP</div>
                        </div>
                        <div class="text-neutral-300">
                            <ChevronRightIcon class="w-5"/>
                        </div>
                    </Link>
                </template>
            </div>
            <div v-else class="mb-8">
                商品はありません。
            </div>
        </div>

    </AdminLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { ChevronRightIcon } from "@heroicons/vue/24/outline"

export default {
    components: {Head, AdminLayout, Link, ChevronRightIcon},
    props: {
        errors: Object,
        auth: Object,
        category_share: Object,
        products: Object,
    },
    data(){
        return {
            // catagries: ['sdfads', 'abc', 'sdfads', 'sdfads'],
        }  
    },
    methods: {
        format_number(n) {
            // return n;
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
    },
}
</script>