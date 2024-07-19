<template>
    <div class="w-full">

    
        <div class="md:w-[760px] w-full mx-auto  pt-5 md:px-2 px-4 items-start">
            <div class="md:w-[760px] w-full text-center mx-auto pb-2 md:px-2 px-4 flex flex-wrap md:gap-20 md:justify-center"> 
                <Link :href="route('main.terms_conditions')" class="text-inherit-400 text-center md:w-auto w-[100%]">利用規約</Link>
                <Link :href="route('main.privacy_police')" class="text-inherit-400 text-center md:w-auto w-[100%]">プライバシーポリシー</Link>
                <Link :href="route('main.notation_commercial')" class="text-inherit-400 text-center md:w-auto w-[100%]">特定商取引法に基づく表記</Link>        
            </div>
            <hr class="border-black"/>
            <div class="md:w-[760px] w-full text-center mx-auto pt-1 pb-5 md:px-2 px-4">
                <span class="text-inherit-100 text-sm font-semibold mr-3">とれとれガチャステーション </span> 
                <span class="text-inherit-400 text-xs">© {{year}}  all rights reserved</span>
            </div>
        </div>
        
    </div>
</template> 




<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {Link},
    data () {
        return {
            category_share: usePage().props.value.category_share,
            url_org: "",
            year : "",
        };
    },
    mounted() {
        let url_str = window.location.href;
        let current_route = url_str.split("?")[0];
        if (current_route.indexOf("/dp") !== -1) {
            this.url_org = route('main.dp') + "?cat_id=";
        } else {
            this.url_org = route('main') + "?cat_id=";
        }

        const d = new Date();
        this.year = d.getFullYear();
    },
}
</script>

