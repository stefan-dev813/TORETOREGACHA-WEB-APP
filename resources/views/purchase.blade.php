<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>とれとれガチャステーションポイント購入</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta http-equiv="Access-Control-Allow-Origin" content="*" />

        <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>

        @vite('resources/css/app.css')
        @if($testOrLive =='test')
            <script src="https://js.test.fincode.jp/v1/fincode.js"></script>
        @else
            <script src="https://js.fincode.jp/v1/fincode.js"></script>     
        @endif

        <script async crossorigin
        src="https://applepay.cdn-apple.com/jsapi/v1.1.0/apple-pay-sdk.js"
        ></script>

        <!-- Styles -->
        <style>
            .main {
                width:max-content;
                margin-left: auto;
                margin-right: auto;
                padding-bottom: 40px;
                max-width:100%;
            }
            apple-pay-button {
                --apple-pay-button-width: 140px;
                --apple-pay-button-height: 30px;
                --apple-pay-button-border-radius: 5px;
                --apple-pay-button-padding: 5px 0px;
            }
            #fincode-form {
                height:auto!important;
                box-sizing: border-box;
            }
            

            #fincode-ui {
                width: 100%!important;
                height: 340px!important;
            }

            div.errors {
                color:red;
                font-size: 13px;
                padding: 15px;
            }

            .fincode-logo {
                padding-left: 10px;
                padding-bottom: 12px;
                text-align:center;
            }
            
            .fincode-logo a {
                cursor: pointer;
                display: flex;
                align-items: center;
            }

            .product-info {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align:center;
            }
            .product-title {
                font-size: 14px;
                font-weight: 500;
            }
            .product-amount{
                font-size: 24px;
                font-weight: 600;
            }

            button#submit {
                border-radius: 0.375rem;
                background-color: #dc2626;
                border:none;
                
                /* padding-left: 4rem;
                padding-right: 4rem; */
                padding-top: 0.625rem;
                padding-bottom: 0.625rem;
                cursor: pointer;
                margin-left: auto;
                margin-right: auto;
                width : 14rem;
            }
            button#submit:hover {
                background-color: #991b1b;
            }

            .button-container {
                padding-top:15px;
                text-align: center;
                margin-bottom: 50px;
            }

            .button-container span {
                color: white!important;
            }

            .card-info{
                font-size: 14px;
                padding-left:15px;
                padding-right:15px;
                padding-top: 15px;
            }      
            .card-info .label {
                margin-bottom: 6px;
            }
            
            .card-info img{
                height: 45px;
            }

            @media only screen and (max-width: 425px) {
                #fincode-form {
                    width:100%!important;
                    margin:0px!important;
                }

                .card-info{
                    padding-left:0px;
                    padding-right:0px;
                    padding-top: 5px;
                    margin-bottom: 10px;
                }   
                
                .fincode-logo {
                    padding-left: 0px;
                }
            }

            .notation-commercial {
                text-align:center;
                margin-bottom: 40px;
            }

            .notation-commercial a {
                text-decoration: none;
                font-size: 14px;
                color: #a3a3a3!important;
                margin-bottom: 15px;
                display: inline-block;
            }

            .notation-commercial .powered-label {
                font-size: 12px;
                color: #a3a3a3!important;
            }

            .notation-commercial .powered-label span {
                color: #737373!important;
            }
            .right-reserve {
                font-size: 12px;
                color: #525252!important;
                text-align:center;
            }

            .check-agree {
                text-align: center;
                font-size: 14px;
                padding-top: 18px;
                padding-bottom: 8px;
            }
            .check-agree>input {
                cursor: pointer;
            }

            .check-agree>input, .check-agree>label {
                vertical-align: middle;
            }
        </style>

    </head>
    <body>
        <div class="flex justify-center">
            <div class="md:w-[760px] w-full min-h-screen flex flex-col">
                <div class="fincode-logo pt-2 w-full">
                    <!-- <img src="https://dashboard.test.fincode.jp/assets/images/logos/vi_02.svg"/> -->
                    <a href="{{($is_admin==1)?route('test.user.point'):route('user.point')}}">
                        <svg xmlns="http://www.w3.org/2000/svg"  style="width:30px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path></svg>
                        <img src="{{ asset('images/logo.png?v=123') }}" class="h-16 mx-2" />
                    </a>
                </div>
                <div class="flex-1 flex flex-col items-center justify-around">
                    <div class="product-info">
                        <span class="product-title">ポイント購入 </span>
                        <br>
                        <span class="product-amount"> ¥ {{number_format($amount)}}</span>
                        <div class="check-agree">
                            <input type="checkbox" id="check-agree" name="check-agree" class="text-blue-600 focus:ring-0">
                            <label for="check-agree">
                                <span>個人情報の取り扱いに同意する</span>
                                <a href="{{ route('main.privacy_police') }}" class="ml-2 text-blue-600 underline underline-offset-4">見る</a>
                            </label>
                        </div>
                    </div>
                    <div class="w-full flex justify-center pay-method">
                        <button onclick="makeCardPay()" class="rounded-lg border bg-white h-11 p-2 w-56 justify-center border-cyan-500 hover:bg-slate-100 flex items-center">
                            <img src="{{ asset('images/icon_card.png') }}" alt="CreditCard" class="h-full"/>
                            <span class="ml-2 text-lg font-bold">Card</span>
                        </button>
                    </div>
                    <div class="flex justify-center w-full pay-method">
                        <button onclick="makePaypay()" class="rounded-lg border bg-white h-11 p-2 w-56 flex justify-center border-rose-500 hover:bg-slate-100">
                            <img src="{{ asset('images/pay_pay_logo.svg') }}" alt="Paypay" class="h-full"/>
                        </button>
                    </div>
                    <div class="flex justify-center w-full pay-method">
                        <button onclick="makeApplepay()" class="rounded-lg border bg-white h-11 w-56 flex justify-center border-green-500 hover:bg-slate-100 items-center">
                            <img src="{{ asset('images/apple-pay-logo.svg') }}" alt="Applepay" class="h-16"/>
                        </button>
                    </div>
                    
                    <div>
                    <div class="card-info card-pay hidden">
                        <div class="label">
                            利用可能カード
                        </div>
                        <div class="w-full grid grid-cols-5 gap-1">
                            <img alt="VISA" src="{{ asset('images/credit_cards/1.JPG') }}" class="w-full"/>
                            <img alt="MasterCard" src="{{ asset('images/credit_cards/2.JPG') }}"  class="w-full"/>
                            <img alt="JCB" src="{{ asset('images/credit_cards/3.JPG') }}"  class="w-full"/>
                            <img alt="Express" src="{{ asset('images/credit_cards/4.JPG') }}"  class="w-full"/>
                            <img alt="International" src="{{ asset('images/credit_cards/5.JPG') }}"  class="w-full"/>
                        </div>
                    </div>


                    <form id="fincode-form">
                        <div id="fincode">
                        </div>
                        
                        <div class="errors">

                        </div>
                    </form>

                    <div>
                        

                        <div class="button-container card-pay hidden">
                            <button id="submit" onclick="makePayment()">
                                <span>お支払い</span>
                            </button>
                        </div>
                        
                        <div class="notation-commercial">
                            <a href="{{ route('main.notation_commercial') }}">特定商取引法に基づく表記</a>
                            
                            <div class="powered-label">
                                Powered by <span>GMO</span>
                            </div>
                            
                        </div>
                        <div class="right-reserve">
                            © {{ date("Y") }} all rights reserved
                        </div>
                        <apple-pay-button buttonstyle="black" type="buy" locale="el-GR"></apple-pay-button>
                    </div>

                </div>
            </div>
        </div>
        
    
    <script>
        let fincode;
        let is_admin = '{{$is_admin}}';
        let purchase_process_url = '{{($is_admin==1)?route('test.user.point.purchase_process'): route('user.point.purchase_process')}}';
        var backUrl = '{{($is_admin==1)?route('test.user.point'):route('user.point')}}';
        var purchase_successUrl = '{{($is_admin==1)?route('test.purchase_success'):route('purchase_success')}}';
        
        var is_busy = false;
        var _token = '{{ csrf_token() }}';
        
        var ui;
        var card_result, order_id = null, access_id = null;

        function makeCardPay() {
            let check_agree = document.querySelector("#check-agree");
            if(check_agree.checked==false) {
                alert('個人情報の取り扱いに同意してください！');
                return;
            }
            $.ajax({
                url : "{{ route('user.point.purchase_card') }}",
                type: "POST",
                data: {
                    id : {{ $point->id }},
                    _token : _token
                },
                dataType: 'json',
                success : function (data) {
                    console.log(data);
                    
                    fincode = Fincode(data.fincode_public_key);
                    ui = fincode.ui({layout: "vertical"});
                    ui.create("payments",{layout: "vertical", hidePayTimes: true});
                    ui.mount("fincode",'360');

                    
                    order_id = data.order_id;
                    // console.log(order_id);
                    access_id = data.access_id;
                    $('.pay-method').hide();
                    $('.card-pay').show();
                }
            }).fail(function (jqXHR, textStatus, error) {
                
            });
            
            return ;
        }

        function makePayment() {
            
            if (is_busy) { return; }
            
            ui.getFormData().then(result => {
                is_busy = true;
                card_result = result;
                getTokens(result);
                return;
            });
        }

        function makePaypay() {
            let check_agree = document.querySelector("#check-agree");
            if(check_agree.checked==false) {
                alert('個人情報の取り扱いに同意してください！');
                return;
            }
            $.ajax({
                url : "{{ route('user.point.purchase_paypay') }}",
                type: "POST",
                data: {
                    id : {{ $point->id }},
                    _token : _token
                },
                dataType: 'json',
                success : function (data) {
                    // console.log(data);
                    if (data.status == "AWAITING_CUSTOMER_PAYMENT") {
                        location.href = data.code_url;
                    }
                    is_busy = false;
                }
            }).fail(function (jqXHR, textStatus, error) {
                is_busy = false;
            });
            
            return ;
        }

        function makeApplepay() {
            let check_agree = document.querySelector("#check-agree");
            if(check_agree.checked==false) {
                alert('個人情報の取り扱いに同意してください！');
                return;
            }
            $.ajax({
                url : "{{ route('user.point.purchase_applepay') }}",
                type: "POST",
                data: {
                    id : {{ $point->id }},
                    _token : _token
                },
                dataType: 'json',
                success : function (data) {
                    console.log(data);

                    const APPLE_PAY_SUPPORTED_VERSION = 3 /* Apple PayJSのサポートバージョン */
                    const MARCHANT_IDENTIFIER = "example.com.store" /* マーチャントID */
                    const APPLE_PAY_BUTTON_ID = "pay-button" /* Apple Payボタン要素のID */
                    /**
                     * 各ショップで作成したマーチャントID検証用のURL 
                     * https://developer.apple.com/documentation/apple_pay_on_the_web/apple_pay_js_api/providing_merchant_validation
                     */
                    const MERCHANT_VALIDATION_URL = "https://example-merchant-validation"

                    if (window.ApplePaySession) {
                        if (ApplePaySession.canMakePaymentsWithActiveCard(MARCHANT_IDENTIFIER)) {
                        $(".pay-button").show();
                        }
                    }
                    $(".pay-button").click(function() {
                    /* 商品の情報 */
                    let request = {
                    countryCode: 'JP',
                    currencyCode: 'JPY',
                    /* 利用可能なカードブランドの種類 */

                    supportedNetworks: ['visa', 'masterCard', 'jcb', 'amex'],
                    merchantCapabilities: ['supports3DS'],
                    total: { 
                        label: 'Apple Payで表示する商品名',
                        amount: '10'
                    }
                    };
                    let session = new ApplePaySession(2, request);

                    session.onvalidatemerchant = function (event) {
                    $.post(MERCHANT_VALIDATION_URL, {validationURL: event.validationURL}, function(res,status) {
                        session.completeMerchantValidation(res);
                    });
                    }
                    /* キャンセルを押した時に呼ばれる */
                    session.oncancel = function(event) {}; 

                    /* 購入者が支払いを承認した時に呼ばれる */
                    session.onpaymentauthorized = function(event) {
                        console.log(event.payment.token)
                        /* base64エンコードしたトークンをfincodeの決済実行APIのtokenに設定する */
                        const encodedToken = btoa(JSON.stringify(token))
                    };
                    session.begin();
                    });

                    
                    // if (data.status == "AWAITING_CUSTOMER_PAYMENT") {
                    //     location.href = data.code_url;
                    // }
                    is_busy = false;
                }
            }).fail(function (jqXHR, textStatus, error) {
                is_busy = false;
            });
            
            return ;
        }


        function backToPage() {
            location.href = backUrl;
        }

        function postFunction(result, cardToken) {
            const transaction = {
                id: order_id,           // オーダーID
                pay_type: "Card", // 決済種別
                access_id: access_id,   // 取引ID 
                expire: result.expire,        // カード有効期限(yymm)
                method: "1",        // 支払い方法  
                token: cardToken,
                holder_name: result.holderName,       // カード名義人
                pay_times: 1,                // トークン発行数
                _token : _token,
            }

            console.log(transaction);
            return ;

            $.ajax({
                url : purchase_process_url,
                type: "POST",
                data: transaction,
                dataType: 'json',
                success : function (data) {
                    // console.log(data)
                    if (data.status == "CAPTURED"){
                        location.href = purchase_successUrl;
                    } else if (data.status == "AUTHENTICATED") {
                        location.href = data.acs_url;
                    } else {
                        if (data.message) {
                            alert(data.message);
                        } else {
                            alert("決済処理中に問題が発生しました！");
                        }
                    }
                    is_busy = false;
                }
            }).fail(function (jqXHR, textStatus, error) {
                is_busy = false;
            });
            return ;
        }


        function getTokens(result) {
            const card = {
                card_no : result.cardNo,       // カード番号
                expire : result.expire,        // カード有効期限(yymm)
                holder_name: result.holderName,       // カード名義人
                security_code: result.CVC,   // セキュリティコード
                number: 1,                // トークン発行数
            }
            fincode.tokens(card,
                function (status, response) {
                    if (200 === status) {
                        // console.log(response.list[0].token);
                        // console.log(result);
                        postFunction(result, response.list[0].token);
                        // リクエスト正常時の処理
                    } else {
                        // リクエストエラー時の処理
                        is_busy = false;
                    }
                },
                function () {
                    // 通信エラー処理
                    is_busy = false;
                }
            );
        }
    </script>
    </body>
    
</html>
