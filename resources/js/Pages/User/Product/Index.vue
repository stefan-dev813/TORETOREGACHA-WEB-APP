<template>
    <Head title="獲得した商品一覧" />

    <AdminLayout>
        
        <div v-if="ready_delivery==0" style="min-height:400px;" class="px-4 md:px-8 w-full">  
            <div class="pt-3 pb-1 mx-4 px-4 mb-3 border-black">
                <h1 class="mb-8 text-lg text-center font-bold underline underline-offset-8">&nbsp;&nbsp;&nbsp;獲得した商品一覧&nbsp;&nbsp;&nbsp;</h1>
                <div class="w-full text-neutral-600 mb-3">
                    <Link v-for="(item, key) in main_tab" :href="item.route_url" class="inline-block md:px-8 px-4 py-1.5" :class="{'bg-[#896858] text-white': item.is_active}">
                        {{item.title}}
                    </Link>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-1">
                <div v-for="(item, key) in products.data" class="mt-1 py-2 px-3 hover:bg-sky-50 focus:bg-sky-50">
                    <div class="flex border-neutral-200 items-start">
                        <input :id="'checkbox' + item.id" v-model="form.checks['id'+item.id]" type="checkbox" @change="changeCheck()" class="cursor-pointer border-neutral-300 text-blue-600 shadow-sm focus:ring-0 m-1"/>
                            
                        <label :for="'checkbox' + item.id" class="cursor-pointer flex items-start justify-center inline-block text-sm py-1">
                            <img :src="item.image" class="w-32 h-32 inline-block object-contain"/>
                        
                            <div class="ml-2 flex flex-col self-stretch justify-around py-3">
                                <div class="text-sm py-1 px-3">{{item.name}}</div>
                                <div class="text-sm py-1 px-3">{{item.rare}}</div>
                                <div v-if="item.is_lost_product!=2" class="flex bg-[#ff8000] justify-center items-center rounded-full px-3 md:my-1 mx-3 py-1 text-white w-fit">
                                    <img src="/images/icon_cash.png" class="h-5 mr-2" />
                                    <span>{{item.point}}&nbsp;PT</span>
                                </div>
                            </div>
                            
                        </label>
                    </div>
                
                </div>
            </div>

            <div class="pb-6 pt-6 text-center text-md text-black">
                <template v-if="hasCheck">
                    選択した商品を...
                </template>
                <template v-else>
                    商品を選択してください！
                </template>
            </div>
            
            <div class="mb-8 pb-8 text-center text-neutral-50">
                <button type="button" @click="submit('point')" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="w-40 inline-block items-center px-4 py-2 bg-[#896858] hover:bg-[#60493d] font-semibold text-sm text-white uppercase tracking-widest active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-blue transition ease-in-out duration-150 m-1">
                    <div>ポイントに換える</div>
                </button>
                <button type="button" @click="submit('delivery')" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="w-40 inline-block items-center px-4 py-2 hover:bg-[#896858] bg-[#60493d] font-semibold text-sm text-white uppercase tracking-widest active:bg-indigo-500 focus:outline-none focus:border-indigo-500 focus:shadow-outline-lime transition ease-in-out duration-150 m-1">
                    <div>発送する</div>
                </button>
            </div>
        </div>        

        <div v-if="ready_delivery==1" class="pt-6 md:px-2 px-4" style="min-height:400px">  
            <h2 class="mb-2 text-lg font-bold">配送内容の確認</h2>
            <hr class="mb-6" />
            <div class="mb-3 text-sm">配送商品 （計{{products_count}}点）</div>
            <div class="mb-8">
                <template v-for="(item, key) in products.data">
                    <div  v-if="form.checks['id'+item.id]" class="mb-0  border-neutral-200 border-b">
                        <div class="flex border-neutral-200 items-center">
                            <img :src="item.image" class="w-12 h-12 inline-block object-contain"/>
                            <label :for="'checkbox' + item.id" class="cursor-pointer h-12 flex items-center justify-between flex-1 inline-block text-xs py-1 px-2 ">
                                <div>
                                    <div>{{item.name}}</div>
                                    <div>{{item.rare}}</div>
                                    <div class="text-red-500">{{item.point}}pt</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </template>
            </div>
            <div class="mb-2 text-sm">配送先情報</div>
            <div class="mb-8 border border-neutral-200 rounded-md px-4 py-2 flex items-center justify-between">
                <div class="flex-1">
                    <template v-if="profile.id">
                        <p class="font-bold text-sm">{{ profile.first_name + profile.last_name }}</p>
                        <p class="text-sm">〒{{ profile.postal_code }}</p>
                        <p class="text-sm">{{ profile.address }}</p>
                    </template>
                </div>
                <div>
                    <Link :href="route('user.address')" type="button" @click="back_delivery()" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="inline-block items-center px-5 py-1.5 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-[#896858] hover:bg-[#60493d] active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150 m-1">
                        編集
                    </Link>
                </div>
            </div>
            
            <div class="mb-8 text-center text-neutral-50">
                <button type="button" @click="back_delivery()" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="w-32 inline-block items-center px-1 py-2.5 border border-transparent font-semibold text-xs text-white uppercase bg-[#896858] hover:bg-[#60493d] tracking-widest active:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:shadow-outline-cyan transition ease-in-out duration-150 m-1">
                    戻る
                </button>
                <button type="button" @click="submit_delivery()" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="w-32 inline-block items-center px-1 py-2.5 border border-transparent font-semibold text-xs text-white uppercase hover:bg-[#896858] bg-[#60493d] tracking-widest active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 m-1">
                    発送する
                </button>
            </div>
        </div>

        <div v-if="ready_delivery==2" class="pt-6 md:px-2 px-4" style="min-height:400px">  
            <h2 class="mb-2 text-lg font-bold">配送手続き完了</h2>
            <hr class="mb-8" />
            <div class="mb-8 ">
                計{{products_count}}点の商品の配送手続きが完了しました。<br/><br/>
                商品の発送には<span class="font-bold">数日~数週間</span>かかる可能性がございます。<br/>
                ご了承のほどよろしくお願い致します。
            </div>
           
            
            <div class="mb-6 text-center text-neutral-50">
                <Link :href="route('main')" class="w-36 inline-block items-center px-1 py-2.5 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 m-1 bg-[#896858] hover:bg-[#60493d]">
                    ガチャTOPへ
                </Link>

                <button type="button" @click="back_delivery()" :class="{ 'opacity-25': form.processing || (!hasCheck) }" :disabled="form.processing || (!hasCheck)" class="w-36 inline-block items-center px-1 py-2.5 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:shadow-outline-cyan transition ease-in-out duration-150 m-1 hover:bg-[#896858] bg-[#60493d]">
                    獲得した商品一覧へ
                </button>
                
            </div>
        </div>

        
    </AdminLayout>
</template>

<script>
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/Admin.vue';



export default {
    components: {Head, AdminLayout, Link},
    props: {
        errors: Object,
        gacha: Object,
        category_share:Object,
        products: Object,
        profile: Object,
    },
    data() {
        return {
            hasCheck: false,
            main_tab : [
                {title:"未選択", route_url: route('user.products'), is_active:true},
                {title:"発送待ち", route_url: route('user.products.wait'), is_active:false},
                {title:"発送済み", route_url: route('user.products.delivered'), is_active:false},
            ],
            ready_delivery: 0,
            products_count: 0,
        }
    },
    watch : {
        flash: function(newVal, oldVal) {
            if (newVal.data) {
                if (newVal.data.delivery_result && newVal.data.delivery_result == 'fail') this.ready_delivery = 1;
                if (newVal.data.delivery_result && newVal.data.delivery_result == 'success') this.ready_delivery = 2;
            }
        }
    },
    setup(props) {
        let checks = {};
        let i;
        for(i=0; i<props.products.data.length; i++) {
            checks['id'+props.products.data[i]['id']] = false;            
        }
        
        const form = useForm( {
            checks: checks,
        })
        return { form }
    },
    methods: {
        changeCheck() {
            let i; let hasCheckLocal = false;
            for(i=0; i<this.products.data.length; i++) {
                if (this.form.checks['id'+this.products.data[i]['id']] ) {
                    hasCheckLocal = true;
                    break;
                }
            }
            this.hasCheck = hasCheckLocal;
        },
        submit(submit_type) {
            let i; let products_count = 0; var point = 0;
            for(i=0; i<this.products.data.length; i++) {
                if (this.form.checks['id'+this.products.data[i]['id']]) {
                    products_count++; point = parseInt(point) + parseInt(this.products.data[i]['point']);
                }
            }

            let need_submit = false;

            if(submit_type=="point") {
                if(point>0) {
                    if (confirm('交換しますか？ 選択した'+products_count+'点の商品を'+ point +'ptと交換します。')){
                        this.form.post(route('user.products.point.exchange'), {
                            onFinish: () => {
                            },
                        });
                    }
                } 
            } else {
                if (point < 1000) {
                    alert('発送は１０００ポイント以上からお願いします。');
                    return ;
                }
                if(products_count>0) {
                    this.products_count = products_count;
                    this.ready_delivery = 1;
                    // if (confirm('選択した'+products_count+'点の商品を発送しますか？')){
                    //     
                    // }
                } 
            }
        },
        back_delivery() {
            this.ready_delivery = 0;
        },  
        submit_delivery() {
            if(this.profile.address) {
                if (confirm(''+ this.products_count + '点の商品を配送しますか？')) {
                    this.form.post(route('user.delivery.post'), {
                        onFinish: () => {
                            
                        },
                    });
                }
            } else {
                alert('入力エラー、配送先情報を入力してください。')
            }
            
        },

        
    },
}
</script>