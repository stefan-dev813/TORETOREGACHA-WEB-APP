<template>
    <Head title="ポイントを購入詳細" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4" style="min-height:400px;">  
            <h1 class="mb-2 text-lg font-bold">ポイントを購入詳細</h1>
            <hr class="mb-8" />
            
        
            <div  class="flex flex-wrap justify-center">
                <template v-for="(point, key) in points.data">
                    <div>
                        <div class="mb-4 bg-white text-center relative rounded-lg shadow-md" style="width:160px;height:200px">
                            <div class="text-center h-3/5 pt-2 px-1 flex justify-center" style="height:65%">
                                <img class="inline-block object-contain" :src="point.image"/> 
                            </div>
                            <div class="text-sm  px-3 flex items-end justify-center font-bold" style="height:15%">
                                {{point.title}}
                            </div>
                            <div class="p-1 h-1/5" >
                                <button class="rounded-full py-0.5 bg-neutral-800 text-neutral-100 w-[100px] text-xs h-5/6" >
                                    ¥ {{point.amount_str}}
                                </button>
                            </div>
                            
                        </div> 

                        <div  class="text-center">
                            <button @click="submit"  :class="{ 'opacity-25': is_busy }" :disabled="is_busy" class="w-full px-1 py-2.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 active:bg-red-800 focus:outline-none focus:border-red-900 focus:shadow-outline-red transition ease-in-out duration-150"> 購入する </button>
                        </div>
                    </div>
                </template>
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
        points:Object,
    },
    data() {
        return {
            is_busy: false,
        }
    },
    mounted () {
    },
    methods: {
        submit () {
        },
    },
}
</script>
