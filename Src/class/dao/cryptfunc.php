<?php
	function encrypt($plain_text,$key){
		//暗号化モジュール使用開始
		$td  = mcrypt_module_open('rijndael-128', '', 'ecb', '');
		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
		$iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		
		//暗号化モジュール初期化
		if (mcrypt_generic_init($td, $key, $iv) < 0) {
		  exit('error.');
		}
		
		//データを暗号化
		$crypt_text = base64_encode(mcrypt_generic($td, base64_encode($plain_text)));

		//暗号化モジュール使用終了
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $crypt_text;
	}
	
	function decrypt($crypt_text,$key){
	    // 文字列の中身がなかったらそのまま終了
        if (empty($crypt_text)) {
            return '';
        }
        
		//暗号化モジュール使用開始        
		$td  = mcrypt_module_open('rijndael-128', '', 'ecb', '');
		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
		$iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		
		//暗号化モジュール初期化
		if (mcrypt_generic_init($td, $key, $iv) < 0) {
		  exit('error.');
		}
        
		//データを復号
		$plain_text = base64_decode((mdecrypt_generic($td, base64_decode($crypt_text))));
        
		//暗号化モジュール使用終了
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $plain_text;
	}
?>