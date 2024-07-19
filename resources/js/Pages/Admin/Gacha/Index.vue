<template>
    <Head title="ガチャ管理" />

    <AdminLayout>

        <div class="mt-6">
            <div  class="px-2 mb-8 sm:grid-cols-2 grid-cols-1" style="min-height: 400px;display: grid;gap: 20px;">
                <template v-for="(gacha, key) in gachas.data">
                    <GachaCard :url_edit="route('admin.gacha.edit', gacha.id)+category_share.cat_route_appendix"  :gacha="gacha"/>
                </template> 

                <Link :href="route('admin.gacha.create')+category_share.cat_route_appendix" class="rounded-lg border-2 border-green-500 shadow w-full aspect-[9/10] flex items-center justify-center">
                    <div class="rounded-full bg-green-500 text-4xl text-center  text-neutral-100 h-16 w-16 pt-2.5" >
                        +
                    </div>
                </Link>
                
            </div>
            <div class="text-center mb-8">
                <Link :href="(route('admin.lost_product') + category_share.cat_route_appendix)" class="inline-block items-center w-36 py-2.5 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 mr-2">
                    カード編集
                </Link>

                <Link :href="(route('admin.gacha.sorting') + category_share.cat_route_appendix)" class="inline-block items-center w-36 py-2.5 bg-cyan-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:shadow-outline-cyan transition ease-in-out duration-150 mr-2">
                    並び替え
                </Link>
            </div>
        </div>
    </AdminLayout>
</template> 

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';

import GachaCard from '@/Parts/GachaCard.vue';

export default {
    components: {Head, AdminLayout, Link,  GachaCard},
    props: {
        errors: Object,
        auth: Object,
        gachas:Object,
        category_share:Object,
    },
    mounted() {
        console.log(this.gachas.data)
    }
}
</script>