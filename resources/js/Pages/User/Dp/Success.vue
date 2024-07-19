<template>
    <AdminLayout>
        <Head title="交換完了" />
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">交換完了</h1>
            <hr class="mb-8" />
        
            <div class="text-center py-3 mb-6">
                交換した景品は「獲得した商品一覧」に<br/>
                お送りしました。
            </div>
            <div class="text-center mb-4">
                <Link :href="route('user.products.wait')"  class="inline-block w-[200px] text-center py-2.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150" >
                    獲得した商品一覧へ 
                </Link>
            </div>
            <div class="text-center mb-8">
                <Link :href="route('main.dp')" class="inline-block w-[200px] text-center py-2.5 text-neutral-500 border-neutral-500 bg-neutral-50 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-200 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                    TP交換所に戻る
                </Link>
            </div>
        </div>
    </AdminLayout>

</template>
<script>
    import AdminLayout from '@/Layouts/Admin.vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        components: { Head, AdminLayout, Link },
        
    }
</script>