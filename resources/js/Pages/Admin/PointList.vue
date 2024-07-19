<template>
    <Head title="ポイント設定" />

    <AdminLayout> 
        
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">ポイント設定</h1>
            <hr class="mb-8" />
            <div class="flex flex-wrap justify-evenly">
                <div v-for="(point, key) in points.data" class="mb-4 bg-white text-center relative rounded-lg shadow-md" style="width:160px;height:200px">
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
                    <Link :href="route('admin.point.edit', {'id':point.id}) + category_share.cat_route_appendix" class="rounded absolute top-0 right-0 px-4 py-2 bg-green-500 text-xs text-neutral-50">
                        編集する
                    </Link>

                    <button @click="destroyPoint(point.id)" class="rounded absolute top-0 left-0 px-4 py-2 bg-red-500 text-xs text-neutral-50">
                        削除
                    </button>
                </div>
                <Link :href="route('admin.point.create') + category_share.cat_route_appendix" class="mb-4 bg-white text-center relative rounded-lg shadow-md flex justify-center items-center" style="width:160px;height:200px">
                    <div class="rounded-full bg-green-500 text-3xl   text-neutral-100 h-10 w-10" >
                        +
                    </div>
                </Link>
            </div>
        </div>

    </AdminLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {Head, AdminLayout, Link},
    props: {
        errors: Object,
        auth: Object,
        category_share:Object,
        points:Object,
    },
    methods : {
        destroyPoint(id) {
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.point.destroy', {'id': id}));
            }
        },
    },
    data(){
        return {
            // catagries: ['sdfads', 'abc', 'sdfads', 'sdfads'],
        }  
    },
}
</script>