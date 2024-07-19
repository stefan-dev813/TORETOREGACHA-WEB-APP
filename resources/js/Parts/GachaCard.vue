<template>
    <div  class="cursor-pointer border-4 border-[#60493d] mb-0 relative rounded-[20px] shadow w-full bg-cover overflow-hidden bg-[#60493d]">
        <div @click="clickCard()">
            <img v-bind:src=gacha.thumbnail />
        </div>
        
        <template v-if="url_edit">
            <Link :href="url_edit" class="absolute z-20 top-0 right-0 items-center px-8 py-2.5 bg-green-500 border border-transparent rounded-sm font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                編集する
            </Link>
            <div v-if="(gacha.status==1)" class="absolute z-20 top-0 left-0 items-center px-2 py-1 bg-green-500 border border-transparent rounded-sm font-semibold text-sm text-white tracking-widest ">
                公開
            </div>
            <div v-else class="absolute z-20 top-0 left-0 items-center px-2 py-1 bg-neutral-500 border border-transparent rounded-sm font-semibold text-sm text-white tracking-widest ">
                非公開
            </div>

            <button @click="destroyGacha(gacha.id)" class="z-20 rounded absolute bottom-0 right-0 px-4 py-2 bg-red-500 text-xs text-neutral-50">
                削除
            </button>
        </template>
        
        
        <div v-if="(gacha.count_rest==0 && !url_edit)" class="absolute bottom-0 w-full h-full bg-opacity-70 z-10 bg-black flex items-center justify-center" @click="clickCard()">
            <img src="/images/sold_out.png" class="w-[75%]"/>
        </div>

        <div class="bottom-0 w-full  p-2 rounded-b-[20px]">
            <div class="px-4 text-white">
                <div class="px-1 flex flex-row justify-between mb-1">
                    <div>
                        <span class="text-sm">残 </span>
                        <span class="font-semibold text-base">{{format_number(gacha.count_rest)}}</span> 
                        <span class="text-sm">/{{format_number(gacha.count_card)}}</span>
                    </div>
                    <div>
                        <span class="inline-block align-middle pr-2"><img class="h-5" src="/images/icon_cash.png"/> </span>
                        <span class="font-semibold">{{format_number(gacha.point)}}</span> 
                        <span>PT</span>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700 overflow-hidden">
                        <div class="h-4 rounded-full " :class="get_progress_value().bg_color" :style=" {width: get_progress_value().value + '%'}" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { PlayIcon } from "@heroicons/vue/24/solid";

export default {
    components: {Link, PlayIcon},
    props: {
        gacha: Object,
        url_edit: String,
    }, 
    data () {
        return {
            category_share: usePage().props.value.category_share,
            url_card: "",
            url_10gacha: "",
            url_1gacha: "",
            str_gacha10: "",
            point_10gacha: 0,
        };
    },  
    methods: {
        get_progress_value() {
            let p = Math.round(this.gacha.count_rest * 100.0/this.gacha.count_card)
            let bg_color = 'bg-red-500';
            if (p>33) bg_color = 'bg-yellow-500';
            if (p>66) bg_color = 'bg-green-500'
            return {value: p, bg_color: bg_color}
        },
        format_number(n) {
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
        
        getImageClass(image) {
            return "url('"+ image +"')";
        }, 

        clickCard() {
            if(this.gacha.count_rest>0 || usePage().props.value.auth.user?.type > 0) {
                window.location.href = this.url_card;
            }
        },

        destroyGacha (id) {
            if (confirm("削除してもいいですか？")) {
                Inertia.delete(route('admin.gacha.destroy', {'id': id}));
            }
        }
    },
    mounted() {
        if(this.gacha.count_rest<10) { 
            this.str_gacha10 = "全て";
            this.point_10gacha = this.gacha.count_rest * this.gacha.point;
        } else {
            this.str_gacha10 = "10連";
            this.point_10gacha = 10 * this.gacha.point;
        }

        if(!this.url_edit) {
            this.url_card = route('user.gacha', this.gacha.id) + this.category_share.cat_route_appendix;
        }
    }
}
</script>