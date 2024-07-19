<template>
    <Head title="獲得した商品一覧" />

    <AdminLayout>
        <div style="min-height:400px;" class="px-4 md:px-8 w-full">  
            <div class="pt-3 pb-1 mx-4 px-4 mb-3 border-black">
                <h1 class="mb-8 text-lg text-center font-bold underline underline-offset-8">&nbsp;&nbsp;&nbsp;獲得した商品一覧&nbsp;&nbsp;&nbsp;</h1>
                <div class="w-full text-neutral-600 mb-3">
                    <Link v-for="(item, key) in main_tab" :href="item.route_url" class="inline-block md:px-8 px-4 py-1.5" :class="{'bg-[#896858] text-white': item.is_active}">
                        {{item.title}}
                    </Link>
                </div>
            </div>
            <div v-if="products.data.length" class="grid md:grid-cols-2 gap-1">
                <div v-for="(item, key) in products.data" class="mt-1 py-2 px-3 hover:bg-sky-50 focus:bg-sky-50">
                    <div class="flex border-neutral-200 items-start">
                        <img :src="item.image" class="w-24 h-32 inline-block object-contains"/>
                        <label class="cursor-pointer flex items-start justify-center inline-block text-sm py-1">
                            
                            <div class="ml-2 flex flex-col self-stretch justify-around py-3">
                                <div class="text-sm py-1 px-3">{{item.name}}</div>
                                <div class="text-sm py-1 px-3">{{item.rare}}</div>
                                <div v-if="item.is_lost_product!=2" class="flex bg-[#ff8000] justify-center items-center rounded-full px-3 md:my-1 mx-3 py-1 text-white w-fit">
                                    <img src="/images/icon_cash.png" class="h-5 mr-2" />
                                    <span>{{item.point}}&nbsp;PT</span>
                                </div>
                            </div>
                            
                        </label>
                    </div>
                
                </div>
            </div>
            <div v-else class="mb-6">
                商品はありません。
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
        gacha: Object,
        category_share:Object,
        products: Object,
    },
    data() {
        return {
            main_tab : [
                {title:"未選択", route_url: route('user.products'), is_active:false},
                {title:"発送待ち", route_url: route('user.products.wait'), is_active:false},
                {title:"発送済み", route_url: route('user.products.delivered'), is_active:true},
            ],
        }
    },
}
</script>