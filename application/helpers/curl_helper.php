<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('getTokenRate')) {
  		function getTokenRate() {
            $CI = get_instance();
    		$pairAddress = '0xd793764dc7968715661c9682fff67edb6de1fdac'; // Replace this
            $url = "https://api.dexscreener.com/latest/dex/pairs/bsc/{$pairAddress}";

            $response = file_get_contents($url);
            if ($response === false) {
                die("Failed to fetch data");
            }

            $data = json_decode($response, true);
            
            $price=0;
            if (isset($data['pair']['priceUsd'])) {
                $price = $data['pair']['priceUsd'];
            } 
            if(empty($price)){
                $settings=$CI->setting->getsettings(['name'=>'coin_rate'],'single');
                $price=$settings['value'];
            }
            else{
                $settings=$CI->setting->getsettings(['name'=>'coin_rate'],'single');
                $prevrate=$settings['value'];
                $rate=$price;
                
                // Compare up to 7 decimal places
                if (bccomp($prevrate, $rate, 7) !== 0) {
                    // Save $newRate to DB
                    $data=['id'=>$settings['id'],'value'=>$rate];
                    $result=$CI->setting->updatesetting($data);
                } 
                else{
                    $price=$prevrate;
                }
            }
            return $price;
		}  
	}