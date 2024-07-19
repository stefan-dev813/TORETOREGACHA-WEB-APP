<template>
    <AdminLayout>
        <Head title="状態評価の基準について" />
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">状態評価の基準について</h1>
            <hr class="mb-8" />
            <div class="mb-8 text-sm">

                <h2 class="text-sm font-semibold mb-1">とれとれガチャステーション状態評価とは、とれとれガチャステーションの基準により、カードの状態をS+, S, A+, A, B+, B, C+, C, D+, Dの10段階で評価したものです。</h2>
                <br/><br/>

                <h2 class="text-sm font-semibold mb-1">S+ 超美品</h2>
                <div class="text-neutral-600">実質完璧な状態。カードの四つ角にも全く傷がなく、どんなシミや汚れもない状態。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">S 超美品</h2>
                <div class="text-neutral-600">非常に優れた状態。ごくわずかな傷や汚れがないもので繊細な傷がひとつ見受けられる程度の状態。</div>
                <br/><br/>

                <h2 class="text-sm font-semibold mb-1">A+ 美品</h2>
                <div class="text-neutral-600">完璧な優れた状態のカードに見えるが、精査するとわずかなシミや汚れがあるような状態。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">A 美品</h2>
                <div class="text-neutral-600">細かく精査すると表面にほんのわずかな傷や汚れがあるが、極めて状態の良いもの。</div>
                <br/><br/>

                <h2 class="text-sm font-semibold mb-1">B+ 良品</h2>
                <div class="text-neutral-600">視認できる表面に印刷上の欠陥はあるが、全体的に見栄えの損なわない状態。</div>
                <br/><br/>

                <h2 class="text-sm font-semibold mb-1">B 良品</h2>
                <div class="text-neutral-600">大きな傷の無いもの、角や端に極めて繊細な傷があり、印刷上の欠陥が視認しやすいもの。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">C+ 並品</h2>
                <div class="text-neutral-600">カードに軽い傷や擦り傷がついているがしっかりと視認しないと気が付かないレベルの状態。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">C 並品</h2>
                <div class="text-neutral-600">カードの四隅は明らかに丸みがあり、ひどくはないが擦り傷がある状態。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">D＋ 訳あり品</h2>
                <div class="text-neutral-600">角は丸みがあり、表面に分かりやすい傷、汚れやシミがまたは削れがある状態。</div>
                <br/><br/>


                <h2 class="text-sm font-semibold mb-1">D 訳あり品</h2>
                <div class="text-neutral-600">カード全体の見栄えが悪く、大きな傷や欠陥がある状態。</div>
                <br/><br/>
            </div>
        </div>
        
    </AdminLayout>

</template>
<script>
    import { Link, Head } from '@inertiajs/inertia-vue3';
    import AdminLayout from '@/Layouts/Admin.vue';
    export default {
        components: { Link, AdminLayout, Head },
    }
</script>