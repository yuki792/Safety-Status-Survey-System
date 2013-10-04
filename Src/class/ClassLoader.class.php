<?php 
ini_set('mbstring.internal_encoding', 'UTF-8'); 
  
/** 
 * 
 * クラスローダー 
 * @author noto 
 * 
 */
class ClassLoader{ 
    /** 
     * 
     * ディレクトリ格納 
     * @var unknown_type 
     */
    private $dirs = array(); 
      
      
    /** 
     * 
     * コンストラクタ  
     */
    public function __construct() { 
        spl_autoload_register( array( $this, 'loader' ) ); 
    } 
      
      
    /** 
     * 
     * ディレクトリを登録 
     * @param string $dir :インクルード対象にするディレクトリ
     */
    public function registerDir($dir){ 
        $this->dirs[] = $dir; 
    } 
   
   
    /** 
     * 
     * コールバック 
     * @param string $classname :インクルードを試みるクラス名
     */
    public function loader( $classname ){ 
        foreach ($this->dirs as $dir) { 
            $file = $dir . $classname . '.class.php'; 
            if ( is_readable( $file ) ){ 
                require $file; 
                return; 
            } 
        } 
    } 
} 
  
// end of ClassLoader.class.php