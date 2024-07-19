<template>
    <Head title="ガチャ履歴" />

    
    <AdminLayout>

        <div class="md:px-1 px-2">
            <div class="border-b w-full p-2 mb-2 font-semibold flex flex-wrap gap-4 items-center">
                <h3 class="flex-1">ガチャ履歴</h3>
                <span>合計:</span>
                <span>{{ total_gacha }}回</span>
                <span>{{ format_number(total_point - total_exchanged) }}pt</span>
                <span>( {{ format_number(total_point) }}pt 使用、{{ format_number(total_exchanged) }}pt 交換 )</span>
            </div>
            <div class="w-full flex flex-col gap-4">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border border-gray-400 border-collapse font-bold">
                            <td class="text-center py-2">ガチャID</td>
                            <td class="text-center py-2">カテゴリー</td>
                            <td class="text-center py-2">ガチャ日時</td>
                            <td class="text-center py-2">製品番号</td>
                            <td class="text-center py-2">名前</td>
                            <td class="text-center py-2">レア度</td>
                            <td class="text-center py-2">種類</td>
                            <td class="text-center py-2">状態</td>
                            <td class="text-center py-2">更新日時</td>
                        </tr>
                    </thead>
                    <tbody class="text-sm border border-gray-400">
                        <template v-for="gacha in gachas" class="">
                            <tr class="border-r border-t border-gray-400">
                                <td class="text-center py-2 border border-gray-400" :rowspan="gacha.products.length">
                                    <Link class="underline text-sky-600" :href="route('admin.gacha.edit', {id: gacha.gacha_id})">{{ gacha.gacha_id }}</Link>
                                </td>
                                <td class="text-center py-2 border border-gray-400" :rowspan="gacha.products.length">{{ gacha.category }}</td>
                                <td class="text-center py-2 border border-gray-400" :rowspan="gacha.products.length">{{ gacha.created_at }}</td>
                                <td class="text-center py-2 border-r border-gray-200">
                                    <Link class="underline text-sky-600" v-if="gacha.products[0].id > 0" :href="route('admin.lost_product', {id: gacha.products[0].id})">{{ gacha.products[0].id }}</Link>
                                </td>
                                <td class="text-center py-2 border-r border-gray-200">{{ gacha.products[0].name }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ gacha.products[0].rare }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ gacha.products[0].type }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ gacha.products[0].status }}</td>
                                <td class="text-center py-2">{{ gacha.products[0].updated_at }}</td>
                            </tr>
                            <tr v-for="product in gacha.products.slice(1)" class="border-r border-r-gray-400 border-t border-t-gray-200">
                                <td class="text-center py-2 border-r border-gray-200">
                                    <Link class="underline text-sky-600" v-if="product.id > 0" :href="route('admin.lost_product', {id: product.id})">{{ product.id }}</Link>
                                </td>
                                <td class="text-center py-2 border-r border-gray-200">{{ product.name }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ product.rare }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ product.type }}</td>
                                <td class="text-center py-2 border-r border-gray-200">{{ product.status }}</td>
                                <td class="text-center py-2">{{ product.updated_at }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <Pagination :search_cond="{ ... search_cond, id }" route_name="admin.users.gacha_log" :total="total"></Pagination>
            </div>
        </div>
    </AdminLayout>
</template> 

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import Pagination from '@/Parts/Pagination.vue';

import GachaCard from '@/Parts/GachaCard.vue';

export default {
    components: {Head, AdminLayout, Link,  GachaCard, Pagination},
    props: {
        errors: Object,
        gachas: Object,
        search_cond: Object,
        total: Number,
        total_exchanged: Number,
        total_point: Number,
        total_gacha: Number,
        id: Number,
    },
    mounted() {
        console.log(this.gachas);
    },
    methods: {
        format_number(n) {
            // return n;
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        },
    },
    setup(props) {
        const form_search = useForm(props.search_cond);
        
        return { form_search }
    },
}
</script>