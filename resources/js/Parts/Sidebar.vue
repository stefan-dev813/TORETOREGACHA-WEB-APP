<template>
    <ul class="text-neutral-700 font-semibold">
        <div class="mt-1.5 pl-8 py-2 text-lg font-bold">{{menu_title}}</div>
        <template v-for="item in main_menu">
            <!-- <li v-if="item.type=='text'" class="font-semibold text-base bg-neutral-400 text-white p-2 pl-5">
                {{item.title}}
            </li> -->

            <li v-if="item.type=='link'" :class="getActiveColor(item.route_name)" class=" text-sm mx-8 my-2 border hover:bg-zinc-200">
                <Link :href="route(item.route_name)" class="py-2 px-4 w-full text-start" as="button"> {{item.title}}</Link>
            </li>
            
        </template>
        <li class=" text-sm mx-8 my-2 border hover:bg-zinc-200">
            <Link :href="route('logout')" method="get" class="py-2 px-4 w-full text-start" as="button">ログアウト</Link>
        </li>
    </ul>
</template>

<script>
    import { Link, usePage } from '@inertiajs/inertia-vue3';
    import admin_menu from '@/Store/admin-menu';
    import user_menu from '@/Store/user-menu';
    import staff_menu from '@/Store/staff-menu';
    export default {
        components: {Link},
        data(){
            return {
                main_menu: [],
                current_route: "",
                menu_title: "管理者メニュー",
            }
        }, 
        mounted(){
            this.current_route = route().current();
            
            let user  = usePage().props.value.auth.user;
            if (user) {
                if (user.type==1) {
                    this.main_menu = admin_menu;
                    this.menu_title = "管理者メニュー";
                } else if (user.type==2) {
                    this.main_menu = staff_menu;
                    this.menu_title = "職員ページ";
                } else {
                    this.main_menu = user_menu;
                    this.menu_title = "マイページ";
                }
                this.main_menu = [
                    {
                        title: "ユーザー情報管理",
                        route_name : "profile.edit",
                        type: "link",
                    },
                    ...this.main_menu
                ];
            } else {
                this.main_menu = [];
            }
            
            
        },
        methods: {
            getActiveColor(route_name) {
                if(this.current_route.indexOf(route_name) !== -1) {
                    return 'text-neutral-900 text-base';
                } else {
                    return 'text-neutral-700 hover:text-neutral-900 text-sm hover:underline';
                }
                
            }
        },  
    }
    
</script>