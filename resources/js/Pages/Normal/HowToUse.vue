<template>
    <AdminLayout>
        <Head title="ご利用方法" />
        <div class="pt-6 md:px-2 px-4">  
            <h1 class="mb-2 text-lg font-bold">ご利用方法</h1>
            <hr class="mb-8" />        
            <div class="mb-8 text-sm">
                <h2 class="text-sm font-semibold mb-1">1. ポイントを購入。</h2>
                <div class="text-neutral-600">
                    ガチャ一覧からまわしたい商品を選んでください。<br/>
                    ガチャ画面が表示されます、消費ポイントと所持ポイントを確認したのち、ポイントが足りない場合は「ポイント購入」へ進んでください。<br/><br/>

                    ご利用・ポイント購入には会員登録（無料）が必要です。<br/><br/>
                </div>


                <h2 class="text-sm font-semibold mb-1">2. ガチャるボタンを押します。</h2>
                <div class="text-neutral-600">
                    「ガチャる」ボタンを押すと残り回転数があり、ご利用可能の場合、演出が開始します。<br/>
                    ポイント消費のタイミングはガチャるボタンを押したタイミングになります。<br/>
                    演出にて出てきた玉の色で商品期待度が決まります。<br/><br/>

                    色の種類は<br/>
                    白＜青＜緑＜赤＜金＜虹<br/><br/>
                    
                    虹に色が近づくほど期待度が高くなります。<br/>
                    とれとれガチャステーション君の見た目の変化で期待度変化も!?<br/><br/>
                </div>


                <h2 class="text-sm font-semibold mb-1">3. 商品が確定すると「ガチャ結果画面」の表示が出ます。</h2>
                <div class="text-neutral-600">
                    ポイントに変換したい商品にチェックおいれて「選択した商品をポイントに変換」ボタンを<br/>
                    押してください。チェックを入れていない商品はマイページの獲得した商品一覧へと送られます。<br/>
                    ご自宅に発送したいカードを獲得した商品一覧から発送手続きをしてください。<br/>
                    手続きが完了すると発送待ちに商品が移行されます。<br/>
                    こちらで発送手続きが完了すると発送済みに移行されます。<br/><br/>
                </div>

                <h2 class="text-sm font-semibold mb-1">4. TPポイント</h2>
                <div class="text-neutral-600">
                    TP交換所はホーム画面からとれとれガチャステーション交換所というタブを押すと進むことができます。<br/>
                    ガチャを回すたびに、消費ポイントに応じてTPポイントが付与されます。<br/>
                    TPポイントがたまるとTP交換所にて商品と引き換えることができます。<br/>
                    引き換えた商品は獲得済みの商品一覧へ移行されます。<br/><br/>


                    不具合などで表示されない場合は「お問い合わせ」にてご連絡ください。<br/><br/>
                </div>



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