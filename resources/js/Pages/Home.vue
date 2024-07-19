<template>
    <Head >
        <title>ホーム</title>
        <meta name="description" content="とれとれガチャステーション">
        <meta property="og:url" content="https://toretoreoripa.com/" />
        <meta property="og:title" content="とれとれガチャステーション！" />
        <meta property="og:description" content="とれとれガチャステーション" />
    </Head>

    <AdminLayout>    
        <div class="px-2 mb-8 sm:grid-cols-2 grid-cols-1" style="display: grid;gap: 32px; box-sizing: content-box;">
            <template v-for="(gacha, key) in gachas.data">
                <GachaCard v-if="gacha.count_rest" :gacha="gacha"/>
            </template> 
            <template v-for="(gacha, key) in gachas.data">
                <GachaCard v-if="!gacha.count_rest" :gacha="gacha"/>
            </template> 
        </div>
        
    </AdminLayout>  
</template>

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import Branch from '@/Parts/Branch.vue';
import GachaCard from '@/Parts/GachaCard.vue';

export default {
    components: {Head, AdminLayout, Link, Branch, GachaCard},
    props: {
        errors: Object,
        auth: Object,
        gachas:Object,
        category_share:Object,
    },
}
</script>