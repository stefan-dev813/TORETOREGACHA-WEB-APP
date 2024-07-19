<template>
    <Head title="TP交換所" />

    <AdminLayout>
        <div  class="flex flex-wrap mt-24 justify-center gap-8 pt-3" style="min-height:400px">
            <template v-for="(dp, key) in products.data">
                <Link :href="route('user.dp.detail', {id: dp.id})"  class="mb-4 bg-sky-600 text-center relative rounded-lg shadow-md border-2 border-gray-200" style="width:190px;height:254px">
                    <div class="text-center h-[180px] bg-white py-8 px-12 flex justify-center rounded-t-md">
                        <img class="inline-block object-cover" :src="dp.image"/> 
                    </div>
                    <div class="h-100">
                        <div class="text-sm px-3 py-1 flex items-center text-white justify-center font-bold">
                            {{dp.name}}
                        </div>
                        <div class="p-1 text-white">
                            <button class="rounded-full py-0.5 bg-white text-neutral-800 w-[100px] text-sm font-bold h-5/6" >
                                {{ format_number(dp.dp) }}tp
                            </button>
                        </div>
                    </div>
                </Link>
            </template>
            
        </div>

        
    </AdminLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import Branch from '@/Parts/Branch.vue';

export default {
    components: {Head, AdminLayout, Link, Branch},
    props: {
        errors: Object,
        auth: Object,
        category_share:Object,
        products:Object,
    },
    methods : {
        format_number(n) {
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
    },
}

//
</script>