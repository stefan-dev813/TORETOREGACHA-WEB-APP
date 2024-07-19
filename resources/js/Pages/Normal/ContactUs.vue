<template>
    <AdminLayout>
        <Head title="お問い合わせ" />
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">お問い合わせ</h1>
            <hr class="mb-8" />

            <div class="mb-2 text-sm">
                恐れ入りますが、 toretoreoripaのお問い合わせはtoretoreoripa公式TwitterのDMからお願い致します。<br/>
                (回答にはお時間をいただく場合がございます)<br/>
            </div>
            <div class="mb-8">
                公式Twitter: <a href="https://twitter.com/toretoreoripa" class="text-cyan-600 underline "> @toretoreoripa </a>
            </div>
        </div>
    </AdminLayout>

</template>
<script>
    import { Link, Head } from '@inertiajs/inertia-vue3';
    import AdminLayout from '@/Layouts/Admin.vue';
    export default {
        components: { Link, AdminLayout, Head },
    }
</script>