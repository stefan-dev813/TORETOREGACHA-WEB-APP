<template>
    <div class="min-h-screen w-full" >
        <video-player
            class="video-player vjs-big-play-centered" 
            playsinline
            preload="yes"
            :src="'/videos/'+ video"
            :loop="false"
            :autoplay="false"
            :volume="0.8"
            @mounted="handleMounted"
            @ended="handleEvent($event)"
        >   
            <template v-slot="{ player, state }">
                <div class="custom-player-controls absolute w-full h-full flex flex-col z-30" :style="[!state.playing?{'background': '#000'}:{}]">
                    <div class="flex justify-between items-center">
                        <button @click="player.muted(!state.muted)" class=" px-5 py-4 text-sm m-1">
                            <div v-if="state.muted" class="flex justify-center"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="2.3em" height="2.3em"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></div>

                            <div v-else class="flex justify-center"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="2.3em" height="2.3em"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"></path></svg></div>
                        </button>
                        <Link :href="route('user.gacha.result', {token: token})" class="block mr-3 items-center px-6 py-2.5 bg-neutral-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-neutral-700 active:bg-neutral-700 focus:outline-none focus:border-neutral-700 focus:shadow-outline-neutral transition ease-in-out duration-150">動画をスキップ</Link>
                    </div>
                    
                    <!-- more custom controls elements ... -->
                    <button v-if="((!state.playing) && (!startedPlay))" class="flex-1 bg-black flex justify-center items-center text-white font-bold" style="font-size:18px;" @click="clickPlay(player, state)" >
                        タップして演出を開始
                    </button>
                </div>
            </template>
        </video-player>
    </div>
</template>
<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Footer from '@/Parts/Footer.vue';



import { VideoPlayer } from '@videojs-player/vue';
import 'video.js/dist/video-js.css';

export default {
    components: {Head, UserLayout, Footer, VideoPlayer, Link},
    props: {
        errors: Object,
        gacha: Object,
        category_share:Object,
        token: String,
        video: String,
    },
    data() {
        return {
            payload:{},
            startedPlay: false,
        }
    },
    methods: {
        handleMounted (payload ) {
            this.payload = payload;
            // payload.player.autoplay('any');
            // payload.player.autoplay('muted');
            // payload.player.play();
        },
        
        handleEvent (log) {
            if(this.payload) {
                window.location.href = route('user.gacha.result', {token: this.token});
            }
        },

        clickPlay(player, state) {
            if (!state.playing) {
                player.play();  
                player.controls = false;
            }
        },

        tryPlay( ) {
            if(this.payload) {
                if (this.payload.state.playing) {
                    this.startedPlay = true;
                }
            }
        }
    },
    mounted () {
        setInterval(this.tryPlay, 250);
    },
}

</script>

<style >
  .video-player {
    background-color: #000;
    width: 100%;
    height: 100vh;
  }
</style>