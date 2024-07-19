<template>
    <div v-if="(gacha.count_rest>0 && gacha.productCount!=0)" class="w-[84%] md:max-w-[360px] mx-auto flex justify-center">
        <button  @click="clickgacha(10)" :class="{ 'opacity-50': processing }" :disabled="processing" class="cursor-pointer m-1 rounded-[5px] bg-[#896858] text-white text-sm text-center w-[48%]">
            <div class="m-2">
                <div>
                    <span style="font-size:18px;" >{{str_gacha10}}</span> 
                    <span style="font-size:18px;" >ガチャる</span>
                </div>
                
            </div>
        </button>
        
        <button  @click="clickgacha(1)" :class="{ 'opacity-50': processing }" :disabled="processing" class="cursor-pointer m-1 rounded-[5px] bg-[#60493d] text-white text-sm text-center w-[48%]">
            <div class="m-2">
                <div>
                    <span style="font-size:18px;" >1回</span> 
                    <span style="font-size:18px;" >ガチャる</span>
                </div>
                
            </div>
        </button>
    </div>
    <div v-if="(gacha.count_rest>10 && gacha.productCount!=0)" class="w-[84%] md:max-w-[360px] mx-auto flex justify-center">
        <button  @click="clickgacha(100)" :class="{ 'opacity-50': processing }" :disabled="processing" class="cursor-pointer m-1 rounded-[5px] bg-red-600 text-white text-sm text-center w-full">
            <div class="m-2">
                <div>
                    <span style="font-size:18px;" >{{str_gacha100}}</span> 
                    <span style="font-size:18px;" >ガチャる</span>
                </div>
                
            </div>
        </button>
    </div>
</template>

<script>
import { Link, usePage, useForm } from '@inertiajs/inertia-vue3';
import {PlayIcon} from "@heroicons/vue/24/solid";


export default {
    components: {Link, PlayIcon},
    props: {
        gacha: Object,
    }, 
    data () {
        return {
            category_share: usePage().props.value.category_share,
            str_gacha10: "",
            str_gacha100: "",
            processing: false,
        };
    }, 
    methods: {
        format_number(n) {
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 

        clickgacha(number) {
            this.processing = true;
            if (this.url_edit) { return; }
            if(this.gacha.ableCount==0) {
                alert("サーバーが混み合っております。少し時間をおいて再度お試しください。");
                this.processing = false;
                return;
            }
            useForm({id:this.gacha.id, number}).post(route('user.gacha.start_post'), {
                onFinish: () => {
                    this.processing = false;
                }
            });
        },
    }, 
    mounted() {
        if(this.gacha.count_rest<10) { 
            this.str_gacha10 = "全て";
        } else {
            this.str_gacha10 = "10連";
        }
        if(this.gacha.count_rest<100) { 
            this.str_gacha100 = "全て";
        } else {
            this.str_gacha100 = "100連";
        }

        if(!this.url_edit) {
            this.url_card = route('user.gacha', this.gacha.id) + this.category_share.cat_route_appendix;
        }
    }
}
</script>

