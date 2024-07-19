<template>
    <Head title="カテゴリー" />

    <AdminLayout>
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">カテゴリー</h1>
            <hr class="mb-8" />
            <div class="">
                <form @submit.prevent="submit()">
                    <table class="m-auto min-w-full text-sm font-medium">
                        <tbody class="border-t">
                            <tr class="border-b border-r border-l">
                                <td class="bg-neutral-200 px-3 py-2 whitespace-nowrap  border-r w-32">カテゴリー</td>
                                <td class="font-light p-1 whitespace-nowrap">
                                    <input v-model="new_form.title" type="text" class="bg-neutral-50 text-neutral-900 text-sm rounded-md border-neutral-300 block px-2 h-full w-full focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="" required />
                                    <div v-if="errors.title" class="text-red-600 mt-1 text-center">
                                        {{ errors.title }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>                    
                    </table>
                    <div class="text-center py-2">
                        <button type="submit" :class="{ 'opacity-25': order_form.processing }" :disabled="order_form.processing" class="w-32 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-normal py-2 px-3 inline-block rounded">
                            追加
                        </button>
                    </div>
                </form>
            </div>

            <hr class="my-4" />

            <div class="text-center pb-2">
                <div class="border-b border-r border-l bg-neutral-200 p-2">
                    カテゴリー編集
                </div>
                
                <div class="flex mb-1">
                    <draggable class="w-full list-group" :list="categories.data" @change="log">
                        <div
                            class="list-group-item m-1 rounded-md text-left cursor-pointer border h-12 border-neutral-500 flex items-center hover:bg-neutral-100 focus:bg-neutral-100 gap-2"
                            v-for="element in categories.data"
                            :key="element.id">
                            <div class="flex-1 whitespace-nowrap h-full">
                                <input v-model="element.title" type="text" class="bg-neutral-50 text-neutral-900 text-sm rounded-l-md border-neutral-300 block px-2 h-full w-full focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="" required />
                            </div>
                            <div class="align-middle h-full flex items-center z-10 gap-1">
                                <button @click.prevent="levelUp(element.id)" class="bg-neutral-700 rounded p-1 hover:bg-neutral-600"><ArrowLongUpIcon class="w-5 h-5 text-white block" /></button>
                                <button @click.prevent="levelDown(element.id)" class="bg-neutral-700 rounded p-1 hover:bg-neutral-600"><ArrowLongDownIcon class="w-5 h-5 text-white block" /></button>
                            </div>
                            <button @click="destroy(element.id)" class="bg-red-500 hover:bg-red-700 text-white text-sm font-normal py-1 px-2 mr-2 rounded">削除</button>
                        </div>
                    </draggable>
                </div>
                <div class="p-1 flex justify-center">
                    <button @click.prevent="submit_order()" :class="{ 'opacity-25': order_form.processing }" :disabled="order_form.processing" class="w-32 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-normal py-2 px-3 inline-block rounded">
                        <div class="inline-block">保存</div>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import { Inertia } from '@inertiajs/inertia';

import { VueDraggableNext } from 'vue-draggable-next';
import { ArrowLongUpIcon, ArrowLongDownIcon } from '@heroicons/vue/24/solid'


export default {
    components: {Link, Head, AdminLayout, draggable:VueDraggableNext, ArrowLongUpIcon, ArrowLongDownIcon},
    props: {
        errors: Object,
        auth: Object,
        categories: Object,
    },
    methods: {
        log() {

        },
        submit() {
            this.new_form.post(route('admin.category.store'),{ onSuccess:()=>this.new_form.reset(),preserveScroll: true});
        },
        destroy(id){
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.category.destroy', id), {preserveScroll: true});
            }
        },
        levelUp(id) {
            let i;
            for(i=1; i<this.categories.data.length; i++) {
                if (this.categories.data[i].id==id) {
                    break;
                }
            }
            if (i<this.categories.data.length) {
                this.arrayMove(i, i-1);
            }
        },
        arrayMove(from, to) {
            this.categories.data.splice(to, 0, this.categories.data.splice(from, 1)[0]);
        },
        levelDown(id) {
            let i;
            for(i=0; i<this.categories.data.length-1; i++) {
                if (this.categories.data[i].id==id) {
                    break;
                }
            }
            if (i<(this.categories.data.length-1)) {
                this.arrayMove(i, i+1);
            }
        },
        submit_order() {
            this.order_form.categories = this.categories;
            console.log(this.categories);
            this.order_form.post(route('admin.category.sorting.store'), {
                preserveScroll: true,
                onFinish: () => {
                }
            });
        }
    },
    data(){
        return {
            open: true,
            dragging: false,
        }  
    },
    setup(props) {
        
        const new_form = useForm( {
            title: "",
        });
        const order_form = useForm( {
            categories: [],
        })
        return {new_form, order_form};
    },
    mounted() {
        
    }
}
</script>