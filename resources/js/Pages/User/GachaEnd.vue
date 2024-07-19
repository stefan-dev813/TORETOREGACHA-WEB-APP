<template>
    <Head title="マイページ" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4" style="min-height:400px">  
            <h1 class="mb-2 text-lg font-bold">ガチャ完了</h1>
            <hr class="mb-8" />

            <div v-if="point" class="mb-6 text-center text-sm">
                計{{number_products}}点の商品を計{{point}}PTに交換しました。<br/>
                その他の商品は「獲得した商品一覧」に送りしています。
            </div>
            <div v-else class="mb-6 text-center text-sm">
                全ての商品を「獲得した商品一覧」にお送りしました。
            </div>

            <div v-if="(gachas.data[0].ableCount>0)" class="mb-8 text-center">
                <h3 class="text-base font-bold mb-2">もう一度ガチャる！</h3>
                <GachaButtons :gacha="gachas.data[0]" />
            </div>

            <div class="mb-12 text-center" >
                <Link :href="route('main')" class="inline-block items-center px-4 py-2 m-1  bg-neutral-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-900 active:bg-neutral-900 focus:outline-none focus:border-neutral-900 focus:shadow-outline-neutral transition ease-in-out duration-150">
                    ガチャ選択ページへ
                </Link>
                <Link :href="route('user.products')" class="inline-block items-center px-4 py-2 m-1 bg-neutral-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">
                    獲得した商品一覧へ
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import GachaButtons from '@/Parts/GachaButtons.vue';

export default {
    components: {Head, AdminLayout, Link, GachaButtons}, 
    props: {
        errors: Object,
        auth: Object,
        number_products: Number,
        point: Number,
        gachas: Object,
    },
}
</script>