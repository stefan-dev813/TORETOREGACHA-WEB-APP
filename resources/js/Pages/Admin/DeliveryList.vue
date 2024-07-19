<template>
    <Head title="ポイント設定" />

    <AdminLayout>

        <div class="mt-3">
            <div class="py-4 flex justify-evenly">
                <button class="rounded-full py-1 bg-neutral-400 text-neutral-100 w-[120px] text-sm">ガチャ</button>
                <button class="rounded-full py-1 bg-cyan-500 text-neutral-100 w-[120px] text-sm">TP交換所</button>
            </div>

            <div  class="flex flex-wrap justify-evenly">
                <div v-for="(dp_product, key) in dp_products.data" class="mb-4 bg-white text-center relative rounded-lg shadow-md" style="width:160px;height:200px">
                    <div class="text-center h-3/5 pt-2" style="height:65%">
                        <img class="inline-block h-full object-contain" :src="dp_product.image"/>
                    </div>
                    <div class="text-sm  px-3 flex items-end justify-center font-bold" style="height:15%">
                        {{dp_product.title}}
                    </div>
                    <div class="p-1 h-1/5" >
                        <button class="rounded-full py-0.5 bg-neutral-800 text-neutral-100 w-[100px] text-xs h-5/6" >
                            ¥ {{dp_product.amount_str}}
                        </button>
                    </div>
                    <Link :href="route('admin.dp.edit', {'id':dp_product.id})" class="absolute top-0 right-0 px-5 py-2 bg-green-500 text-xs text-neutral-50">
                        編集する
                    </Link>
                </div>
                <Link :href="route('admin.dp.create') + category_share.cat_route_appendix" class="mb-4 bg-white text-center relative rounded-lg shadow-md flex justify-center items-center" style="width:160px;height:200px">
                    <div class="rounded-full bg-green-500 text-3xl   text-neutral-100 h-10 w-10" >
                        +
                    </div>
                </Link>
            </div>

            <div  class="flex flex-wrap justify-evenly">
                

            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';

export default {
    components: {Head, AdminLayout, Link},
    props: {
        errors: Object,
        auth: Object,
        category_share:Object,
        dp_products:Object,
    },
    data(){
        return {
            // catagries: ['sdfads', 'abc', 'sdfads', 'sdfads'],
        }  
    },
}
</script>