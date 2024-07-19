<template>
    <Head title="ガチャ並び替え" />

    <AdminLayout>
        <div class="mt-2">
            <div class="p-1 text-right">
                <button @click.prevent="submit()" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-32 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-normal py-2 px-3 inline-block rounded">
                    <div class="inline-block align-middle"><PaperAirplaneIcon class="w-5 h-5 mr-1"/></div> <div class="inline-block align-middle">保存する</div>
                </button>
            </div>
            <div class="flex mb-1">
                <draggable class="dragArea list-group w-full" :list="list" @change="log">
                <div
                    class="list-group-item bg-neutral-800 m-1 p-1 rounded-md text-left cursor-pointer text-white relative"
                    v-for="element in list"
                    :key="element.name"
                >
                    <img :src="element.thumbnail" class="w-24 h-24 inline-block align-middle mr-2"/> 
                    <div class="inline-block text-left align-middle">
                        <div>
                            <span v-if="element.status==1" class="bg-green-600 rounded text-xs px-2 mx-1">公開</span>
                            <span v-if="element.status==0" class="bg-neutral-600 rounded text-xs px-2 mx-1">非公開</span>
                        </div>
                        <div>
                            <span class="text-white text-sm">残 </span><span class=" text-base">{{ element.count_rest }}</span><span class="text-white text-xs">/{{ element.count_card }}</span>
                            <span v-if="element.count_rest==0" class="bg-red-600 rounded text-xs px-2 mx-1">Soldout</span>
                        </div>
                        <div><span class="text-sm ">1回:</span> <span>{{ element.point }} pt</span></div>
                    </div>
                    <div class="inline-block align-middle h-full absolute top-0 right-2 flex items-center z-10">
                        <button @click.prevent="levelUp(element.id)" class="bg-neutral-700 rounded p-1 mr-1 hover:bg-neutral-600"><ArrowLongUpIcon class="w-6 h-5 text-white block" /></button>
                        <button @click.prevent="levelDown(element.id)" class="bg-neutral-700 rounded p-1 mr-1 hover:bg-neutral-600"><ArrowLongDownIcon class="w-6 h-5 text-white block" /></button>
                    </div>
                </div>
                </draggable>
            </div>
        </div>
    </AdminLayout>
</template> 

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';
import GachaCard from '@/Parts/GachaCard.vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { ChevronUpDownIcon, ArrowLongUpIcon, ArrowLongDownIcon, PaperAirplaneIcon } from '@heroicons/vue/24/solid'

export default {
    components: {Head, AdminLayout, Link,  GachaCard, draggable: VueDraggableNext, ChevronUpDownIcon, ArrowLongUpIcon, ArrowLongDownIcon, PaperAirplaneIcon},
    props: {
        errors: Object,
        auth: Object,
        gachas:Object,
        category_share:Object,
    },
    data() {
        return {
            enabled: true,
            list: [],
            dragging: false,
        }
    },
    methods: {
        log(event) {
            // console.log(this.list);
        },
        levelUp(id) {
            let i;
            for(i=1; i<this.list.length; i++) {
                if (this.list[i].id==id) {
                    break;
                }
            }
            if (i<this.list.length) {
                this.arrayMove(i, i-1);
            }
        },
        arrayMove(from, to) {
            this.list.splice(to, 0, this.list.splice(from, 1)[0]);
        },
        levelDown(id) {
            let i;
            for(i=0; i<this.list.length-1; i++) {
                if (this.list[i].id==id) {
                    break;
                }
            }
            if (i<(this.list.length-1)) {
                this.arrayMove(i, i+1);
            }
        },
        submit() {
            this.form.gachas = this.list;
            this.form.post(route('admin.gacha.sorting.store'), {
                onFinish: () => {
                }
            });
        }
    }, 
    setup() {
        const form = useForm( {
            gachas: [],
        });
        return { form }
    },
    mounted() {
        this.list = this.gachas.data;
    }
}
</script>