<template>
    <Head title="購入履歴" />

    <AdminLayout>

        <div class="md:px-1 px-2">
            <div class="border-b w-full p-2 mb-2 font-semibold flex gap-4 items-center">
                <h3 class="flex-1">購入履歴</h3>
                <span>合計:</span>
                <span>{{ format_number(total_amount) }}円</span>
                <span>{{ format_number(total_point) }}pt</span>
            </div>
            <div class="w-full flex flex-col gap-4">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-collapse">
                            <td class="text-center py-2">オーダーID</td>
                            <td class="text-center py-2">取引ID</td>
                            <td class="text-center py-2">決済種別</td>
                            <td class="text-center py-2">金額</td>
                            <td class="text-center py-2">ポイント</td>
                            <td class="text-center py-2">更新日時</td>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr v-for="payment in payments" class="border border-sky-50 divide-x-2 divide-sky-50">
                            <td class="text-center py-2">{{ payment.order_id }}</td>
                            <td class="text-center py-2">{{ payment.access_id }}</td>
                            <td class="text-center py-2">{{ payment.pay_type }}</td>
                            <td class="text-center py-2">{{ format_number(payment.amount) }}</td>
                            <td class="text-center py-2">{{ format_number(payment.point) }} pt</td>
                            <td class="text-center py-2">{{ payment.updated_at }}</td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :search_cond="{ ...search_cond, id}" route_name="admin.users.purchase_log" :total="total"></Pagination>
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
        payments: Object,
        search_cond: Object,
        total: Number,
        total_amount: Number,
        total_point: Number,
        id: Number,
    },
    mounted() {
      
    },
    methods: {
        search() {
            this.form_search.get(route('admin.users.purchase_log'));
        },
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