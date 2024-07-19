<template>

    <div class="w-full">
        <div class="md:w-[760px] w-full mx-auto whitespace-nowrap md:px-0 px-1 py-3 text-left text-sm text-neutral-400 overflow-x-auto overflow-y-hidden mb-5">
            <Link v-for="(category, key) in category_share.categories.data" :href="current_route+'?cat_id='+category.id" class="inline-block px-4 py-2" :class="{'border-b-2 border-solid text-neutral-800 border-neutral-800':category.id==category_share.cat_id, 'text-neutral-800':category.id!=category_share.cat_id}">
                {{category.title}}
            </Link>
        </div>
    </div>        
        

         
</template>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';


export default {
    components: {Link},
    data(){
        return {
            category_share: usePage().props.value.category_share,
            current_route: "",
        }  
    },
    mounted() {
        let url_str = window.location.href;
        this.current_route = url_str.split("?")[0];
    },
}
</script>