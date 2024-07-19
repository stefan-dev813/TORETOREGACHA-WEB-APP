<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Point;
use App\Models\Payment;
use App\Models\User;
use App\Models\Invitation;

use \Exception;

class PaymentController extends Controller
{
    public $config = [
        'testOrLive' => 'live',
        'is3DSecure' => '1',
        'fincode_public_key' => '',
        'fincode_secret_key' => '',
        'apiDomain' => '',
    ];

    protected function set_config() {
        $this->config['testOrLive'] = getOption('testOrLive');
        $this->config['is3DSecure'] = getOption('is3DSecure');

        if ($this->config['testOrLive'] =="test") {
            $this->config['fincode_public_key'] = env('FINCODE_TEST_API_KEY');
            $this->config['fincode_secret_key'] = env('FINCODE_TEST_SECRET_KEY');
            $this->config['apiDomain'] = "https://api.test.fincode.jp";
        } else {
            $this->config['fincode_public_key'] = env('FINCODE_LIVE_API_KEY');
            $this->config['fincode_secret_key'] = env('FINCODE_LIVE_SECRET_KEY');
            $this->config['apiDomain'] = "https://api.fincode.jp";
        }
    }

    public function do_request($apiPath, $method, $requestParams) {
        $res = [
            'status' => 1,
            'response' => '',
            'httpcode' => '0',
            'error' => '',
        ];

        try{
            $session = curl_init();
            curl_setopt($session, CURLOPT_URL, $this->config['apiDomain'].$apiPath);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);

            $headers = array(
                "Authorization: Bearer " . $this->config['fincode_secret_key'],
                "Content-Type: application/json"
                );
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

            if ($requestParams) {
                $requestParamsJson = json_encode($requestParams);
                curl_setopt($session, CURLOPT_POSTFIELDS, $requestParamsJson);
            }
            

            curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($session);
            $httpcode = curl_getinfo($session, CURLINFO_HTTP_CODE);
            
            curl_close($session);
            $res['response'] = $response;
            $res['httpcode'] = $httpcode;

        } catch(Exception $e) {
            $text = "処理中に問題が発生しました！ " ;
            $res['status'] = 0;
            $res['error'] = $text;
        }

        return $res;
    }

    public function purchase($id) {
        $this->set_config();
        $hide_cat_bar = 1;

        $points = Point::where('id', $id)->get();
        if (!count($points)) {
            return redirect()->route('user.point');
        }
        $point = $points[0];

        $user = auth()->user();
        $is_admin = 0;
        if ($user) {
            if ( $user->type==1 ) {
                $is_admin = 1;
            }
        }
        
        $amount = $point->amount;
        if ($is_admin==1) {
            $amount = 100;
        }
        // else if ($this->config['testOrLive'] != 'live') {
        //     return redirect()->route('user.point');
        // }
     
        return view('purchase', ['point' => $point, 'is_admin'=>$is_admin, 'amount'=>$amount, 'testOrLive'=>$this->config['testOrLive']]);
    }
    public function purchase_card(Request $req) {
        $id = $req->id;
        $this->set_config();
        $hide_cat_bar = 1;

        $points = Point::where('id', $id)->get();
        if (!count($points)) {
            return redirect()->route('user.point');
        }
        $point = $points[0];

        $user = auth()->user();
        $is_admin = 0;
        if ($user) {
            if ( $user->type==1 ) {
                $is_admin = 1;
            }
        }
        
        $amount = $point->amount;
        if ($is_admin==1) {
            $amount = 100;
        }
        
           
        $apiPath = "/v1/payments";
        $method = 'POST';
        $requestParams = array(
            "pay_type" => "Card",
            "job_code" => "CAPTURE",
            "amount" => strval($amount),
            "tds_type" => "0"
        );        
        if ($this->config['is3DSecure']=="1") {
            $requestParams['tds_type'] = "2";  //   3DS2.0を利用
            $requestParams['td_tenant_name'] = "Toretore Gacha Station";  //   3Dセキュア表示店舗名
            $requestParams['tds2_type'] = "3";  //   3DS2.0の認証なしでオーソリを実施し、決済処理を行う。
            // $requestParams['tds2_type'] = "2";  //   エラーを返し、決済処理を行わない。
        }

        $res = $this->do_request($apiPath, $method, $requestParams);
        if ($res['httpcode']!='200') {
            $text = "決済登録エラー！\n";
            $text .= $this->getErrorText($res) ;
            $hide_back_btn = 1; $hide_cat_bar = 1;
            return inertia('NoProduct', compact('text', 'hide_back_btn', 'hide_cat_bar'));
        }        
        $json_data = json_decode($res['response']);
        $order_id = $json_data->id;
        $access_id = $json_data->access_id;

        Payment::Create([
            'user_id'=> auth()->user()->id,
            'point_id'=> $point->id,
            'access_id'=>$access_id,
            'order_id'=>$order_id,
        ]);
        
        return response()->json(['point' => $point, 'order_id' => $order_id, 'access_id' => $access_id, 'fincode_public_key'=>$this->config['fincode_public_key'], 'testOrLive'=>$this->config['testOrLive'], 'is_admin'=>$is_admin, 'amount'=>$amount]);
    }

    public function purchase_paypay(Request $req) {
        $this->set_config();
        $hide_cat_bar = 1;

        $id = $req->id;
        
        $points = Point::where('id', $id)->get();
        if (!count($points)) {
            return json_encode(['status' => 1]);
        }
        $point = $points[0];

        $user = auth()->user();
        $is_admin = 0;
        if ($user) {
            if ( $user->type==1 ) {
                $is_admin = 1;
            }
        }
        
        $amount = $point->amount;
        if ($is_admin==1) {
            $amount = 100;
        }
        
           
        $apiPath = "/v1/payments";
        $method = 'POST';
        $requestParams = array(
            "pay_type" => "Paypay",
            "job_code" => "CAPTURE",
            "amount" => strval($amount)
        );        
        
        $res = $this->do_request($apiPath, $method, $requestParams);
        if ($res['httpcode']!='200') {
            $text = "決済登録エラー！\n";
            $text .= $this->getErrorText($res) ;
            $res['status'] = '0';
            $res['message'] = $text;
            return json_encode($res);
        }
        
        $json_data = json_decode($res['response']);
        $order_id = $json_data->id;
        $access_id = $json_data->access_id;

        Payment::Create([
            'user_id'=> auth()->user()->id,
            'point_id'=> $point->id,
            'access_id'=>$access_id,
            'order_id'=>$order_id,
        ]);

        $apiPath = "/v1/payments/".$order_id;
        $method = 'PUT';
        $requestParams = [
            'pay_type' => 'Paypay',
            'access_id' => $access_id,
            'redirect_url' => route('purchase_info', ['order_id'=>$order_id,'access_id'=>$access_id,'pay_type'=>'Paypay'])
        ];
        $ans = $this->do_request($apiPath, $method, $requestParams);
        
        if ($ans['httpcode']!='200') {
            $res['message'] = "決済実行エラー！\n";
            $res['message'] .= $this->getErrorText($ans) ;
            $res['status'] = '0';
            return json_encode($res);
        }

        return $ans['response'];
    }

    public function purchase_process(Request $request) {
        $this->set_config();

        $transaction = $request->all();
        unset($transaction['_token']);

        $user = auth()->user();
        if ($this->config['is3DSecure']=="1") {
            $transaction['tds2_ret_url'] = route('tds2_ret_url');  
        }
        
        $res = array();
        $res['status'] = '0';
        
        $apiPath = "/v1/payments/".$transaction['id'];
        $method = 'PUT';
        $requestParams = $transaction;
        if ($this->config['is3DSecure']=="1") {
            $ans = $this->do_request($apiPath, $method, $requestParams);
        }
        else {
            $ans = $this->do_request($apiPath, $method, [
                'pay_type' => 'Card',
                'access_id' => $transaction['access_id'],
                'method' => $transaction['method'],
                'token' => $transaction['token'],
                'pay_times' => $transaction['pay_times'],
                'holder_name' => $transaction['holder_name'],
                'expire' => $transaction['expire']
            ]);
        }
        
        if ($ans['httpcode']!='200') {
            $res['message'] = "決済実行エラー！\n";
            $res['message'] .= $this->getErrorText($ans) ;
            $res['status'] = '0';
            return $res;
        }
        $json_data = json_decode($ans['response']);
        
        $order_id = $json_data->id;
        $access_id = $json_data->access_id;
        if ($json_data->status=="CAPTURED") {
            $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
            if(count($payments)) {
                $payment = $payments[0];
                $user = User::find($payment->user_id);
                $point = Point::find($payment->point_id);
                $sum = ((int)$point->point) + ((int)$user->point);
                $user->update(['point'=>$sum]);
                $payment->update(['status'=>1]);
                $res['status'] = $json_data->status;  // CAPTURED
            }
        }

        if ($json_data->status=="AUTHENTICATED") {
            $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
            if(count($payments)) {
                $payment = $payments[0];
                $payment->update(['status'=>2]);
            }

            $res['order_id'] = $order_id;  
            $res['access_id'] = $access_id;
            $res['job_code'] = $json_data->job_code;  // CAPTURE
            $res['acs_url'] = $json_data->acs_url; 
            $res['status'] = $json_data->status; // AUTHENTICATED
        }

        return json_encode($res);
    }

    public function purchase_info($order_id, $access_id, $pay_type) {
        $this->set_config();
        $apiPath = "/v1/payments/".$order_id."?pay_type=".$pay_type;
        $method = 'GET';
        $ans = $this->do_request($apiPath, $method, []);
        $json_data = json_decode($ans['response']);
        
        if ($json_data->status=="CAPTURED") {
            $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
            if(count($payments)) {
                $payment = $payments[0];
                $user = User::find($payment->user_id);
                $point = Point::find($payment->point_id);
                $sum = ((int)$point->point) + ((int)$user->point);
                $invite = Invitation::where('user_id', $user->id)->where('status', 0)->first();
                if ($invite) {
                    $sum += 300;
                    $friend = User::find($invite->inviter);
                    if ($friend) {
                        $friend->point += 300;
                        $friend->save();
                    }
                }
                $user->update(['point'=>$sum]);
                $payment->update(['status'=>1]);
            }
            return redirect()->route('purchase_success');
        }

        if ($json_data->status=="AUTHENTICATED") {
            $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->where('status', 0)->get();
            if(count($payments)) {
                $payment = $payments[0];
                $payment->update(['status'=>2]);
            }
        }
        
        return redirect()->route('user.point');
    }


    public function tds2_ret_url(Request $request) {
        $text = "" ;

        $this->set_config();
        $access_id = $request->MD;        
        if ($request->event!="AuthResultReady") {
            $apiPath = "/v1/secure2/$access_id";
            $method = 'PUT';
            $requestParams = ['param' => $request->param];
            
            $ans = $this->do_request($apiPath, $method, $requestParams);
            if ($ans['httpcode']!='200') {
                $text = $this->getErrorText($ans);
            } else {
                $json_data = json_decode($ans['response']);
                $text = "" ;
                switch ($json_data->tds2_trans_result) {
                    case 'Y':
                        $ans = $this->payment_after_auth($access_id);
                        $text .= "\n" . $ans['error'];
                        break;
                    case 'C':
                        header('Location: '. $json_data->challenge_url); 
                        die();
                        exit();
                        break;
                    case 'A':
                        $ans = $this->payment_after_auth($access_id);
                        $text = "3Dセキュア利用ポリシー設定が認証必須の設定の場合はエラーです。(A)";
                        $text .= "\n" . $ans['error'];
                        break;
                    default:
                        $text = "認証失敗しました！ ($json_data->tds2_trans_result)";
                }
            }
        } else {
            
            $apiPath = "/v1/secure2/$access_id";
            $method = 'GET';
            $requestParams = [];
            $ans = $this->do_request($apiPath, $method, $requestParams);
            if ($ans['httpcode']!='200') {
                
                $text .= $this->getErrorText($ans);
            } else {
                $json_data = json_decode($ans['response']);
                if ($json_data->tds2_trans_result!='Y') {
                    $text = "チャレンジ認証失敗しました！($json_data->tds2_trans_result)" ;
                } else {
                    $res = $this->payment_after_auth($access_id);
                    $text .= "\n" . $res['error'];
                }
            }
        }

        
        $hide_back_btn = 1;
        $hide_cat_bar = 1;
        return inertia('NoProduct', compact('text', 'hide_back_btn', 'hide_cat_bar'));
    }

    public function payment_after_auth($access_id) {
        // 認証後決済
        $res = [
            'status' => 0,
            'error' => '',
        ];
        $payments = Payment::where('access_id', $access_id)->where('status', 2)->get();
        if (count($payments)) {
            $payment = $payments[0];
            $order_id = $payment->order_id;

            $apiPath = "/v1/payments/$order_id/secure";
            $method = 'PUT';
            $requestParams = ['pay_type'=>'Card', 'access_id'=>$access_id];
            $ans = $this->do_request($apiPath, $method, $requestParams);
            if ($ans['httpcode']!='200') {
                $res['error'] = "認証後決済エラー！\n";
                $res['error'] .= $this->getErrorText($ans);
                return $res;
            } else {
                $json_data = json_decode($ans['response']);
                
                if (isset($json_data->status)) {
                    $order_id = $json_data->id;
                    $access_id = $json_data->access_id;
                    if ($json_data->status=="CAPTURED") {
                        $payments = Payment::where('order_id', $order_id)->where('access_id', $access_id)->get();
                        if(count($payments)) {
                            $payment = $payments[0];
                            $user = User::find($payment->user_id);
                            $point = Point::find($payment->point_id);
                            $sum = ((int)$point->point) + ((int)$user->point);
                            $invite = Invitation::where('user_id', $user->id)->where('status', 0)->first();
                            if ($invite) {
                                $sum += 300;
                                $friend = User::find($invite->inviter);
                                if ($friend) {
                                    $friend->point += 300;
                                    $friend->save();
                                }
                            }
                            $user->update(['point'=>$sum]);
                            $payment->update(['status'=>1]);
                            $res['status'] = 1;  // CAPTURED
                            $redirect_uri = ($user->type==1)? 'test.purchase_success': 'purchase_success';
                            header('Location: '. route($redirect_uri)); 
                            die();
                            exit();
                        } 
                    }
                } else {
                    $res['error'] = "認証後決済エラー！\n";
                    $res['error'] .= '状態が存在しません。';
                }
            }
        } else {
            $res = [
                'status' => 0,
                'error' => 'データベースに取引が存在しません。',
            ];
        }
        return $res;
    }

    protected function getErrorText($data) {
        $error_print = "";
        try{
            // switch($data['httpcode']) {
            //     case '0':
            //         $error_print .= "CURLリクエストエラー!\n";
            //         break;
            //     case '400':
            //         $error_print .= $data['httpcode'] . " : 不正なリクエストです。リクエストパラメータとJSONの形式を確認してください。\n";
            //         break;
            //     case '401':
            //         $error_print .= $data['httpcode'] . " : 認証されていません。APIキーを確認してください。\n";
            //         break;
            //     case '403':
            //         $error_print .= $data['httpcode'] . " : APIを使用する権限がありません。アクセス先を確認してください。または一定時間内のリクエスト数が多すぎる可能性があります。\n";
            //         break;
            //     case '404':
            //         $error_print .= $data['httpcode'] . " : 指定したAPIが存在しません。アクセス先を確認してください。\n";
            //         break;
            //     case '405':
            //         $error_print .= $data['httpcode'] . " : 無効なHTTPメソッドへの要求です。HTTPメソッドを確認してください。\n";
            //         break;
            //     case '406':
            //         $error_print .= $data['httpcode'] . " : 要求されたリソースが受け入れられないコンテンツしか生成できないことを意味します。Acceptヘッダーを確認してください。\n";
            //         break;
            //     case '409':
            //         $error_print .= $data['httpcode'] . " : リソースの競合が発生しています。要求を処理できませんでした。\n";
            //         break;
            //     case '415':
            //         $error_print .= $data['httpcode'] . " : サーバーまたはリソースがサポートしていないメディアタイプが指定されました。リクエストヘッダーのメディアタイプを確認してください。\n";
            //         break;
            //     case '500':
            //         $error_print .= $data['httpcode'] . " : サーバーでエラーが発生したため、要求を完了できませんでした。\n";
            //         break;
            //     case '502':
            //         $error_print .= $data['httpcode'] . " : ゲートウェイエラーです。プロキシサーバーの使用中など、あるサーバーが別のサーバーから無効なリクエストを受信したことを意味します。\n";
            //         break;
            //     case '503':
            //         $error_print .= $data['httpcode'] . " : メンテナンス中による流入制限です。\n";
            //         break;
            //     case '504':
            //         $error_print .= $data['httpcode'] . " : ゲートウェイタイムアウトが発生しました。\n";
            //         break;
            //     default:
            //         $error_print .= $data['httpcode'] . " : エラー!\n";
            //         break;
            // }
            $json_data = json_decode($data['response']);
            if ($json_data->errors) {
                foreach($json_data->errors as $item) {
                    $error_print .= "\n";
                    $error_print .= "$item->error_code : ";
                    $error_print .= "$item->error_message";
                }
            }
        } catch(Exception $e) {
            
        }
        return $error_print;
    }

    public function webhook (Request $request) {
    }
}
