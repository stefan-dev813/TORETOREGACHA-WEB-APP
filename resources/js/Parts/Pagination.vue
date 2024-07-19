<template>
    <!-- BEGIN: Pagination -->
    <div v-if="total>1" class="flex justify-center items-center">
        <Link v-if="page>1" :href="route(route_name, { ...search_cond, page: 1 })" class="rounded-l-md border w-9 h-9 flex justify-center items-center hover:bg-neutral-200">1</Link>
        <span v-if="page>3" class="p-2"><EllipsisHorizontalIcon class="w-5"/></span>
        <Link v-if="page>2" :href="route(route_name, { ...search_cond, page: page - 1 })" class="border w-9 h-9 flex justify-center items-center hover:bg-neutral-200">{{ page - 1 }}</Link>
        <Link :href="route(route_name, { ...search_cond, page: page })" class="w-9 h-9 flex justify-center items-center text-white bg-neutral-600" :class="{'rounded-l-md': page == 1, 'rounded-r-md': page == total}">{{ page }}</Link>
        <Link v-if="page+1<total" :href="route(route_name, { ...search_cond, page: page + 1 })" class="border w-9 h-9 flex justify-center items-center hover:bg-neutral-200">{{ page + 1 }}</Link>
        <span v-if="page+2<total" class="p-2"><EllipsisHorizontalIcon class="w-5"/></span>
        <Link v-if="page<total" :href="route(route_name, { ...search_cond, page: total})" class="rounded-r-md border w-9 h-9 flex justify-center items-center hover:bg-neutral-200">{{ total }}</Link>
    </div>
</template>

<script>
    import { ChevronRightIcon, ChevronLeftIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/outline'
    import { Link } from '@inertiajs/inertia-vue3';
    export default {
        components: {Link, ChevronRightIcon, ChevronLeftIcon, EllipsisHorizontalIcon},
        props: {
            route_name: String,
            search_cond: Object,
            total: Number,
        },
        setup(props) {
            const page = props.search_cond.page;
            return {page};
        }, 
        methods: {
        }
    }
</script>