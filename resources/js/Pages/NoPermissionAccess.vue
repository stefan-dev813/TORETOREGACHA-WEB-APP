<template>
    <Head title="マイページ" />

    <AdminLayout>

        <div class="m-3 p-3 text-center h-[400px] border border-neutral-200 rounded-md">
            このページにアクセスする権限がありません。
        </div>
    </AdminLayout>
</template>

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';

export default {
    components: {Head, AdminLayout, Link},
    props: {
        errors: Object,
        auth: Object,
    },
    data(){
        return {
            catagries: ['sdfads', 'abc', 'sdfads', 'sdfads'],
        }  
    },
}
</script>