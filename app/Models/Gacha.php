<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gacha extends Model
{
    use HasFactory;
    protected $fillable = [
        'point',
        'count_card',
        'count',
        'lost_product_type',
        'thumbnail',
        'image',
        'category_id',
        'status',
        'spin_limit',
        'gacha_limit_status',
        'gacha_limit_on_setting',
        'starting_day'
    ];

    public function getProductsLostSettingAttribute(){
        return Gacha_lost_product::where('gacha_id', $this->id)->get();
        // return [];
        // return $this->hasMany(Gacha_lost_product::class, 'gacha_id', 'id');
    }

    public function ableCount() {
        $res = 0; $rest_count = 0; $rest_product = 0; $is_error = 0;
        $rest_product = Product::where('gacha_id', $this->id)->where('is_lost_product', 0)->where('is_last', 0)->where('status', 0)->sum('marks'); 
        
        // if ($rest_product) {
            if ($this->lost_product_type!='無') {
                $productLostSettings = Gacha_lost_product::where('gacha_id', $this->id)->where('count', '>', 0)->get();
                foreach($productLostSettings as $productLostSetting) {
                    $products_lost = Product::where('point', $productLostSetting->point)->where('status', 0)->where('is_lost_product', 1)->where('category_id', $this->category_id)->where('marks', '>', 0);
                    if ($this->lost_product_type) {
                        $products_lost = Product::where('lost_type', $this->lost_product_type);
                    }
                    $temp = $products_lost->sum('marks');
                    
                    if ($temp>=$productLostSetting->count) {
                        $rest_product = $rest_product + $productLostSetting->count;
                    } else {
                        if ($temp>=10) {
                            $rest_product = $rest_product + $temp;
                        } else {
                            $is_error = 1; $rest_product = 0;
                            break;
                        }
                    }
                }
            }
        // }
        
        $rest_count = $this->count_card - $this->count;
        if ($rest_count<0) { $rest_count = 0; }

        if ($rest_count<$rest_product) {
            $is_error = 1;
        } else {
            $res = $rest_product;
        }
        
        if ($is_error) {
            return 0;
        } else {
            return $res;
        }
    }

    public function getProductLast(){
        $products = Product::where('gacha_id', $this->id)->where('is_last', '>', 0)->where('is_lost_product', 0)->orderBy('is_last')->orderBy('status')->get();
        foreach ($products as $product) {
            $product->image = getProductImageUrl($product->image);
        }
        return $products;
    }

    public function getProducts() {
        $products = Product::where('gacha_id', $this->id)->where('is_last', 0)->where('is_lost_product', 0)->where('status', 0)->orderBy('id', 'ASC')->get();
        return $products;
    }


    public function getAward($award_total, $rest_total) {
        $award_products = []; $arr_select = [];
        $products = Product::where('gacha_id', $this->id)->where('is_lost_product', 0)->where('is_last', 0)->where('status', 0)->where('marks', '>', 0)->get();
        // $to = count($products)-1; $from = 0;
        // $item = ['from'=>0, 'to'=>$to, 'type'=>'product', 'point'=>0];
        // array_push($arr_select, $item);
        $to = -1;
        foreach($products as $item) {
            $from = $to + 1; $to = $from + $item->marks - 1;
            $item = ['from'=>$from, 'to'=>$to, 'type'=>'product', 'point'=>0, 'id'=>$item->id];
            array_push($arr_select, $item);
        }

        if ($this->lost_product_type!='無') {
            $productLostSettings = Gacha_lost_product::where('gacha_id', $this->id)->where('count', '>', 0)->get();
            foreach($productLostSettings as $productLostSetting) {
                $from = $to + 1; $to = $from + $productLostSetting->count - 1;
                $item = ['from'=>$from, 'to'=>$to, 'type'=>'lost', 'point'=>$productLostSetting->point];
                array_push($arr_select, $item);
            }
        }
        

        if ($rest_total != ($to+1)) {
            return [];
        }

        if ($award_total>$rest_total) {
            return [];
        }

        

        $rand_ids = [];
        for($i=0; $i<$award_total; $i++) {
            for($j=0; $j<10000; $j++) {
                $rand_val = rand(0, $rest_total-1);
                if (!in_array($rand_val, $rand_ids)) {
                    array_push($rand_ids, $rand_val);
                    break;
                }
            }
        }

        if (count($rand_ids)!=$award_total) {
            return [];            
        }

        $res_product_ids = []; $count_product = 0;
        foreach($rand_ids as $rand_id) {
            $temp = 0;
            foreach($arr_select as $item) {
                if($item['from']<=$rand_id && $rand_id<=$item['to']) {
                    if($item['type']=='product') {
                        $temp = $item['id'];
                        $count_product = $count_product + 1;
                    } else {
                        $sql = Product::where('point', $item['point'])->where('status', 0)->where('is_lost_product', 1)->where('category_id', $this->category_id)->where('marks', '>', 0);
                        if ($this->lost_product_type) {
                            $sql = Product::where('lost_type', $this->lost_product_type);
                        }
                        $values = $sql->inRandomOrder()->get();
                        foreach($values as $value) {
                            $value_id = $value->id;
                            $value_count = 0;
                            foreach($res_product_ids as $value1) {
                                if ($value_id==$value1) {
                                    $value_count = $value_count + 1;
                                }
                            }
                            if ($value_count<$value->marks) {
                                $temp = $value_id;
                                break; 
                            }
                        }
                    }
                    if ($temp) { break; }
                }
                if ($temp) { break; }
            }
            if (!$temp) {
                $res_product_ids = [];
                break;
            } else {
                array_push($res_product_ids, $temp);
            }
        }

        $products_last = Product::where('gacha_id', $this->id)->where('status', 0)->where('is_last', '>', $this->count)->where('is_last', '<=', $this->count + $award_total)->where('is_lost_product', 0)->get();
        foreach ($products_last as $product) {
            array_push($res_product_ids, $product->id);
        }
        
        return $res_product_ids;
    }

    // public function getAwardOld($award_total) {
    //     $award_products = [];
    //     $products = Product::where('gacha_id', $this->id)->where('status', 0)->where('is_last', 0)->where('is_lost_product', 0)->get();
        
    //     $array_lost = [];
    //     if ($this->lost_product_type!='無') {
    //         $products_lost = Product::where('status', 0)->where('is_lost_product', 1)->where('category_id', $this->category_id)->where('marks', '>', 0);
    //         if ($this->lost_product_type) {
    //             $products_lost = Product::where('lost_type', $this->lost_product_type);
    //         }
    //         $products_lost = $products_lost->inRandomOrder()->limit(50)->get();
    //         foreach($products_lost as $product) {
    //             array_push($array_lost, $product->id);
    //         }
    //     }
        
    //     while(1) {
    //         $total_percentage = 0.0;
    //         $last_product = [];
    //         $array = [];
    //         foreach($products as $product) {
    //             if (in_array($product->id, $award_products )) {
    //                 continue;
    //             }
    //             if ($product->is_last==1) { 
    //                 array_push($last_product, $product->id);
    //                 continue; 
    //             }
    //             $pre = $total_percentage;
    //             $total_percentage = $total_percentage + $product->emission_percentage;
    //             $item = ['id'=>$product->id, 'low'=>$pre, 'high'=>$total_percentage, 'percentage'=>$product->emission_percentage];
    //             array_push($array, $item);
    //         }
            
    //         $pre_count = count($award_products);
    //         $max_int = 1000000;  
    //         $p100 = 100; 
    //         // $lost_rate = 10.0;  // percentage of real products in real + lost
    //         // if ( $lost_rate > $total_percentage && $total_percentage>0.0 ) {
    //         //     $p100 = $total_percentage * 100 / $lost_rate; 
    //         // }

    //         if (count($array_lost)>0 && $total_percentage<$p100) {
    //             $rand_value = $p100 * rand(0,$max_int)/$max_int;
    //         } else {
    //             $rand_value = $total_percentage * rand(0,$max_int)/$max_int;    
    //         }
            
    //         if ($rand_value<=$total_percentage){
    //             if (count($array)>=1) {
    //                 for($i=0; $i<count($array); $i++ ) {
    //                     $item = $array[$i];
    //                     if ($item['low']<=$rand_value && $rand_value<=$item['high']) {
    //                         array_push($award_products, $item['id']);
    //                         unset($array[$i]);
    //                         break;
    //                     }
    //                 }
    //                 if (count($award_products)==$pre_count) {
    //                     array_push($award_products, array_pop($array));
    //                 }
    //             } 
    //         }  

    //         if($pre_count == count($award_products)) {
    //             if(count($array)==0) {
    //                 if (count($last_product)>0) {
    //                     array_push($award_products, array_pop($last_product));
    //                 }     
    //             }
    //         }

    //         if($pre_count == count($award_products)) {
    //             if(count($array_lost)>0) {
    //                 array_push($award_products, array_pop($array_lost));
    //             } 
    //         }
    //         if(count($award_products)>=$award_total) {
    //             break;
    //         }

    //         if($pre_count == count($award_products)) {
    //             break;
    //         }
    //     }
        

    //     if($award_total !=count($award_products)) {
    //         return [];
    //     }

    //     $products = Product::where('gacha_id', $this->id)->where('status', 0)->where('is_last', 0)->where('is_lost_product', 0)->get();
    //     $count_item = 0;
    //     foreach($products as $product) {
    //         if (in_array($product->id, $award_products)) {
    //             $count_item = $count_item +1;
    //         }
    //     }
    //     if ($count_item) {
    //         if (count($products)==$count_item) {
    //             $products_last = Product::where('gacha_id', $this->id)->where('status', 0)->where('is_last', 1)->where('is_lost_product', 0)->get();
    //             if (count($products_last)) {
    //                 array_push($award_products, $products_last[0]->id);
    //             }
    //         }
    //     }

    //     return $award_products;
    // }

    // public function getAwardWithoutLost($award_total) {
    //     // $award_total = 10;
    //     $award_products = [];
    //     $products = Product::where('gacha_id', $this->id)->where('status', 0)->where('is_lost_product', 0)->get();
    //     $total_count = count($products);
    //     if ($total_count<$award_total) return $award_products;
        
    //     while(1) {
    //         $total_percentage = 0.0;
    //         $last_product = [];
    //         $array = [];
    //         foreach($products as $product) {
    //             if (in_array($product->id, $award_products )) {
    //                 continue;
    //             }
    //             if ($product->is_last==1) { 
    //                 $last_product = $product;
    //                 continue; 
    //             }
    //             $pre = $total_percentage;
    //             $total_percentage = $total_percentage + $product->emission_percentage;
    //             $item = ['id'=>$product->id, 'low'=>$pre, 'high'=>$total_percentage, 'percentage'=>$product->emission_percentage];
    //             array_push($array, $item);
    //         }

    //         $max_int = 1000000;
    //         $rand_value = $total_percentage * rand(0,$max_int)/$max_int;
    //         if (count($array)>=1) {
    //             $pre_count = count($array);
    //             for($i=0; $i<count($array); $i++ ) {
    //                 $item = $array[$i];
    //                 if ($item['low']<=$rand_value && $rand_value<=$item['high']) {
    //                     array_push($award_products, $item['id']);
    //                     unset($array[$i]);
    //                     break;
    //                 }
    //             }
    //             if (count($array)==$pre_count) {
    //                 array_push($award_products, $array[0]);
    //                 print_r('error');
    //                 print_r($array);
    //             }
    //         } else {
    //             if ($last_product) {
    //                 array_push($award_products, $last_product->id);
    //             }
    //             break;                
    //         }
    //         if(count($award_products)==$award_total) {
    //             break;
    //         }
    //     }
        

    //     return $award_products;
    // }

}

