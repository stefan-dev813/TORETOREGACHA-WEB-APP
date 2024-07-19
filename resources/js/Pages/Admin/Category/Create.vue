<template>
    <Head title="マイページ" />

    <AdminLayout>

        <div class="mt-3">
            
            <div class=" whitespace-nowrap text-center text-neutral-400 overflow-x-auto overflow-y-hidden">
                <div v-for="(category, key) in catagries" class="inline-block px-4 py-1.5" :class="{'border-b-2 border-red-600 text-red-600':category=='abc'}">
                    {{category}}
                </div>
            </div>

            <div  class="flex flex-wrap justify-evenly">
                <div v-for="(category, key) in catagries" class="mb-4 bg-white text-center relative rounded-lg shadow-md" style="width:160px;height:180px">
                    <div class="text-center" style="height:60%">
                        <img class="inline-block h-full object-contain" src="/images/dp_exchange/1.png"/>
                    </div>
                    <div class="h-1/5 text-xs px-3">
                        クリックorタップ：タブ移動（表示す
                    </div>
                    <div class="p-1 h-1/5">
                        <button class="rounded-full py-0.5 bg-neutral-800 text-neutral-100 w-[100px] text-xs" >
                            1,000tp
                        </button>
                    </div>
                    <div class="absolute top-0 right-0 px-5 py-2 bg-green-500 text-xs text-neutral-50">
                        編集する
                    </div>
                </div>
                <div class="mb-4 bg-white text-center relative rounded-lg shadow-md flex justify-center items-center" style="width:160px;height:180px">
                    <div class="rounded-full bg-green-500 text-3xl   text-neutral-100 h-10 w-10" >
                        +
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';

export default {
    components: {Head, AdminLayout},
    props: {
        errors: Object,
        auth: Object,
    },
    data(){
        return {
            catagries: ['sdfads', 'abc', 'sdfads', 'sdfads'],
        }  
    },
}
</script>