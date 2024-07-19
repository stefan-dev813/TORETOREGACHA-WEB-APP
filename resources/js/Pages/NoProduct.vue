<template>
    <Head title="マイページ" />

    <AdminLayout>

        <div class="m-3 px-3 pt-10 text-center h-[400px] border border-neutral-200 rounded-md">
            <div v-html="text" class="whitespace-pre-line"> </div>
            

            <div class="mt-8 mb-8 text-center">
                <Link :href="route('main')" type="submit" class="inline-block items-center px-14 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                    トップページへ
                </Link>
            </div>
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
        text: String,
    },
}
</script>