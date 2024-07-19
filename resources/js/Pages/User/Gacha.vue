<template>
    <Head title="ガチャ" />

    <UserLayout>
        <template v-if="!is_admin && gacha_log.length == 0">
            <div class="absolute w-full h-full overflow-y-auto">
                <div class="md:w-[760px] w-full mx-auto bg-white md:py-6 py-0">
                    <div class="mx-auto relative w-full md:max-w-[430px]">
                        <div class="flex justify-end p-6 absolute top-0 w-full">
                            <button class="rounded-full py-0.5 w-fit px-4 text-sm border-2 border-white bg-white items-center flex align-center">
                                <span class="font-semibold text-sm">残&nbsp;&nbsp;</span>
                                <span class="font-semibold text-sm">{{format_number(gachas.data[0].count_rest)}}</span> 
                                <span class="text-sm">/{{format_number(gachas.data[0].count_card)}}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- </button>
                            <button class="rounded-full py-1 text-white w-[135px] text-sm border-2 border-white bg-sky-500"> -->
                                <img src="/images/icon_cash.png" class="w-5 h-5"/>&nbsp;&nbsp;
                                <span class="font-semibold text-sm">{{format_number(gachas.data[0].point)}}</span> 
                                <span class="text-sm">PT</span>
                            </button>
                        </div>
                        <img :src="gachas.data[0].image" class="block w-full mb-[140px]"/>
                    </div>                
                </div>             
            </div>
            
            <div class="absolute w-full" style="bottom:30px;">
                <GachaButtons :gacha="gachas.data[0]" />
            </div>
        </template>
        <div v-else class="w-full md:w-[768px] mx-auto mt-6">
            <h3 class="underline underline-offset-4 mb-4 px-4 text-lg">排出履歴</h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-collapse">
                        <th class="text-center py-2">番号</th>
                        <th class="text-center py-2">名前</th>
                        <th class="text-center py-2">画像</th>
                        <th class="text-center py-2">レア度</th>
                        <template v-if="is_admin">
                            <th class="text-center py-2">ポイント</th>
                            <th class="text-center py-2">ユーザー</th>
                            <th class="text-center py-2">時間</th>
                            <th class="text-center py-2">状態</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(log, index) in gacha_log" class="border-b border-collapse">
                        <td class="text-center py-2">{{ index + 1 }}</td>
                        <td class="text-center py-2">{{ log.name }}</td>
                        <td class="text-center py-1 flex justify-center">
                            <img class="h-20" :src="log.image" />
                        </td>
                        <td class="text-center py-2">{{ log.rare }}</td>
                        <template v-if="is_admin">
                            <td class="text-center py-2">{{ log.point }}</td>
                            <td class="text-center py-2">{{ log.email }}</td>
                            <td class="text-center py-2">{{ log.gacha_time }}</td>
                            <td class="text-center py-2">{{ log.status }}</td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </UserLayout>
</template>

<script>
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Footer from '@/Parts/Footer.vue';
import GachaButtons from '@/Parts/GachaButtons.vue';


export default {
    components: {Head, UserLayout, Footer, Link, GachaButtons},
    props: {
        errors: Object,
        gachas: Object,
        category_share:Object,
        gacha_log: Array,
        is_admin: Boolean,
    },
    data() {
        return {
          
        }
    },
    methods: {
        format_number(n) {
            // return n;
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
    },
}
</script>

