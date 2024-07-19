<template>
    <!-- <div class=" py-4 flex justify-evenly "> -->
    <!--
    <div v-if="is_gacha" class="md:w-[760px] mx-auto md:px-0 px-1 py-0 justify-between" style="display: grid;gap: 10px;grid-auto-flow: column;grid-template-columns: 1fr 1fr;">
        <Link :href="url_gacha" class="font-bold h-12 text-md text-center py-2.5" :class="is_gacha==1?'text-white bg-red-500' : 'text-black bg-gray-200'">ガチャ</Link>
        <Link :href="url_dp" class="font-bold h-12 text-md text-center py-2.5" :class="is_gacha!=1? 'text-white bg-red-500' : 'text-black bg-gray-200'">TP交換所</Link>
    </div>
    -->
</template>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';


export default {
    components: {Link},
    props: {
        // is_gacha: Number,
    }, 
    data(){
        return {
            category_share: usePage().props.value.category_share,
            is_gacha: 0,
            url_gacha: "",
            url_dp: "",
        }  
    },
    mounted() {
        this.is_gacha = usePage().props.value.branch_is_gacha;
        

        this.url_dp = route('main.dp')+this.category_share.cat_route_appendix;
        this.url_gacha = route('main')+this.category_share.cat_route_appendix;
    },
}
</script>