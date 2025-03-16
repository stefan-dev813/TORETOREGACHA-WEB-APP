<template>
    <Head title="ガチャ管理" />

    <AdminLayout>

        <div>
            <div class="border-b w-full p-2 mb-2 font-semibold">
                <h3>ポイント配布</h3>
            </div>
            <Link :href="route('admin.coupon.create')" class="rounded float-right px-8 py-2 bg-teal-600 hover:bg-teal-700 text-neutral-50">
                新規追加
            </Link>
            <div class="w-full mt-16">
                <table class="w-full table-fixed">
                    <thead>
                        <tr class="border-b border-collapse">
                            <td class="text-center py-2">名前</td>
                            <td class="text-center py-2">コード</td>
                            <td class="text-center py-2">ポイント</td>
                            <td class="text-center py-2">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="coupon in coupons" class="border-b border-collapse">
                            <td class="text-center py-2">{{ coupon.title }}</td>
                            <td class="text-center py-2 text-red-600">{{ coupon.code }}</td>
                            <td class="text-center py-2">{{ coupon.point }} pt</td>
                            <td class="flex justify-center py-2">
                                <Link :href="route('admin.coupon.edit', coupon.id)" class="rounded float-right px-3 py-1 mr-2 text-sm bg-cyan-600 hover:bg-cyan-700 text-neutral-50">
                                    編集
                                </Link>
                                <!-- <button @click="delete_coupon(coupon.id)" class="rounded float-right px-3 py-1 text-sm bg-neutral-600 hover:bg-neutral-700 text-neutral-50">
                                    削除
                                </button> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template> 

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {Head, AdminLayout, Link},
    props: {
        coupons: Object
    },
    mounted() {
        
    },
    methods: {
        delete_coupon(id) {
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.coupon.delete', {id:id}));
            }
        },
    }
}
</script>