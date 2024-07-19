<template>
    <div class="flex min-h-screen text-neutral-700 text-base underline-offset-2 font-medium">
        <TransitionRoot :show="sidebarOpened" class="h-full">
            <Dialog :open="sidebarOpened" @close="sidebarOpened=false"  class="fixed h-full inset-0 z-40 ">
                <TransitionChild  
                    enter="transition ease-in-out duration-200 transform"
                    enter-from="translate-x-full"
                    enter-to="translate-x-0"
                    leave="transition ease-in-out duration-200 transform"
                    leave-from="translate-x-0"
                    leave-to="translate-x-full"
                    as="template">
                    <div class="flex flex-col relative z-10 h-full w-64 bg-gray-100 border-l border-neutral-200 ml-auto">
                        <button 
                            @click="sidebarOpened=false"
                            class="absolute top-2 right-2 mt-8 mr-8 flex items-center justify-center w-10 h-10 rounded-full focus:outline-none " 
                            type="button" value="Close sidebar">
                            <XMarkIcon class="h-5 w-5"/>
                        </button>
                        <div class="p-4">
                            
                        </div>
                        
                        <div class="flex-1 overflow-y-auto"> 
                            <Sidebar/>
                        </div>
                    </div>
                </TransitionChild>
                <TransitionChild 
                    enter="transition-opacity ease-linear duration-200"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    as="template">
                    <DialogOverlay class="fixed inset-0 bg-neutral-600 h-full bg-opacity-50"></DialogOverlay>
                </TransitionChild>
            </Dialog>
        </TransitionRoot>


        <TransitionRoot :show="isDialogMessage" class="h-full">
            <Dialog :open="isDialogMessage" @close="isDialogMessage=false"  class="fixed h-full inset-0 z-40 ">
                <TransitionChild  
                    enter="transition ease-in-out duration-200 transform"
                    enter-from="translate-y-full"
                    enter-to="translate-y-0"
                    leave="transition ease-in-out duration-200 transform"
                    leave-from="translate-y-0"
                    leave-to="translate-y-full"
                    as="template">
                    <div class="flex flex-col text-neutral-700 rounded relative z-20 top-20 w-fit max-w-96 min-w-48 bg-neutral-50 border-l border-neutral-200 m-auto">
                        <div class="pt-3 pb-1 px-1 text-center font-bold border-b mb-2">
                            {{dialogTitle}}
                        </div>
                        
                        <div class="px-4 text-sm text-center" v-html="dialogMessage">
                        </div>

                        <hr class="mt-3"/>
                        <button @click="isDialogMessage=false" class="cursor-pointer p-2 text-cyan-500 text-center text-base focus-visible:outline-0">
                            OK
                        </button>
                    </div>
                </TransitionChild>
                <TransitionChild 
                    enter="transition-opacity ease-linear duration-200"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    as="template">
                    <DialogOverlay class="fixed inset-0 bg-neutral-600 h-full bg-opacity-50"></DialogOverlay>
                </TransitionChild>
            </Dialog>
        </TransitionRoot>

        <div class="min-h-screen w-full flex flex-col">
            <div class="flex-shrink-0 w-full mb-1">
                <div class="flex justify-center xl:px-8 px-0 ">
                    <div class="md:w-[760px] w-full flex md:px-0 px-2 mx-auto justify-center md:gap-2 gap-1">
                        <div class="flex items-center flex-1">
                            <Logo/>
                        </div>
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="point_link" class="flex text-center text-sm text-white rounded-md bg-[#896858] overflow-hidden">
                                <div class="flex items-center py-1 px-3">
                                    <img src="/images/icon_cash.png" class="md:h-6 h-5 mr-2"/>
                                    <span class="font-bold">{{point_value}}&nbsp;PT</span>
                                </div>
                                <div class="flex items-center bg-[#60493d] px-2.5">
                                    <span class="text-lg">+</span>
                                </div>
                            </Link>
                            
                            <!--   <div class="min-w-[60px] h-[26px] text-right bg-blue-500 hover:bg-cyan-700 text-red-50 py-1 pr-5 pl-5 rounded-full text-xs m-1 mb-0"> {{dp_value}} <span class="text-xs"> tp</span>
                            </div>
                            -->

                        </div>
                        <div class="relative flex-shrink-0 flex items-center">
                            <img v-if="this.user" @click="sidebarOpened=true" class="md:w-8 md:h-8 w-7 h-7 inline cursor-pointer" src="/images/dopakun.png"/>
                            <Link :href="route('login')" v-else class="md:h-8 h-7 border-solid border-neutral-600 hover:bg-[#896858] border rounded-md md:px-5 px-2 text-sm text-black hover:text-white" as='button'>ログイン</Link>
                        </div>
                    </div>
                </div>
                <Category v-if="!hide_cat_bar"/>
            </div>
            <main class="w-full flex-1 relative">
                <slot />
            </main>
            
        </div>
        
    </div>
</template>

<script>
import {TransitionRoot, TransitionChild, Dialog, DialogOverlay, Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue';
import Logo from '@/Parts/Logo.vue';
import Sidebar from '@/Parts/Sidebar.vue';
import Footer from '@/Parts/Footer.vue';
import { usePage, Link } from '@inertiajs/inertia-vue3';

import Category from '@/Parts/Category.vue';

import { XMarkIcon, ChevronLeftIcon, Bars3Icon } from '@heroicons/vue/24/outline'

import Toastify from "toastify-js";

export default {
    components: {Category, Sidebar, Footer, TransitionRoot, TransitionChild, Dialog, DialogOverlay, XMarkIcon, ChevronLeftIcon, Bars3Icon, Link, Menu, MenuButton, MenuItems, MenuItem,Logo},
    data() {
        return {
            sidebarOpened: false,
            isDialogMessage: false,
            dialogMessage: "",
            dialogTitle: "",
            user: {},
            point_value: 0,
            dp_value: 0,
            point_link: route('user.point'),            
            hide_cat_bar: "",
            gacha_button: {},
        }
    },
    mounted(){        
        
        this.user = usePage().props.value.auth.user;
        if (this.user) {
            this.point_value = this.user.point;
            this.dp_value = this.user.dp;
        }

        this.gacha_button = usePage().props.value.gacha_button;

        this.hide_cat_bar = usePage().props.value.hide_cat_bar;
    },
    computed : {
        flash() {
            return usePage().props.value.flash;
        } 
    },
    watch : {
        flash: function(newVal, oldVal) {
            if (newVal.type=="notification") {
                this.notification(newVal.message);
            }
            if (newVal.type=="dialog") {
                this.dialogMessage = newVal.message;
                this.dialogTitle = newVal.title;
                this.isDialogMessage = true;
            }
            if (newVal.data && newVal.data.user) {
                this.point_value = newVal.data.user.point;
                this.dp_value = newVal.data.user.dp;
            }
        }
    },
    methods: {
        format_number(n) {
            // return n;
            return String(n).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        }, 
        notification(message) {
            if(message){
                Toastify({
                    text: message,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: false, // Prevents dismissing of toast on hover
                    className: "toastify-content newClass",
                    onClick: function(){} // Callback after click
                }).showToast();
            }
        },
        back() {
            window.history.back();
        },
    }
}
</script>