<?php
//==============================================================================
//			Shaarm	50-324        		
//==============================================================================
use Parser\Brand\AbstractBrand;


class Shaarm extends AbstractBrand {
    
    public static $brandName = 'Shaarm';
    
    public function __construct($saw) {
        parent::__construct($saw);
        try{
             // $this->getJsonFile(50); // if exist new Exel file / param == idBrand
             $this->setExcelJson('brands_parsers/Shaarm/data.json');
             $this->setName();
             $this->setFromJson();
             $this->delDuplicateFromString($this->sizesProd);
             $this->delDuplicateFromString($this->colorsProd);
             $this->setPriceOpt(-15); // $_SESSION['per']
             $this->changeDesc();
        }catch(BrandException $ex){
            return null;
        }
    }

    /**
     * Set exelJson, read json file
     * @param string $jsonPath
     */
    protected function setExcelJson($jsonPath){
        parent::setExcelJson($jsonPath);
        // del header desc / (excelJson[0])
        array_shift($this->excelJson);//unset не сдвигает массив   
    }
    
    /**
    * Set string nameProd
    */
    private function setName(){
        //$selector = $_SESSION["h1"]
        $this->nameProd = $this->getName('section #center_column h1');
    }
       
    /**
    * заполнение данными из json по имени товара
    * Set from json:
    * string $codProd
    * int $price
    * string $descProd
    * string $sizesProd
    * string $colorsProd
    */
    private function setFromJson(){
        $x = 0;
        foreach($this->excelJson as $key => $value){  
            if($value[1] == $this->nameProd && $value[11] == 'В наличии'){      
                if($x++ == 0){
                    $this->codProd  = $value[2];
                    $this->price    = ceil($value[3]);
                    $this->descProd = strip_tags($value[9] . '; Описание: ' . $value[5]);
                }                              
                    $value[6] ? $this->sizesProd  .= $value[6] . ';' : '';
                    $value[7] ? $this->colorsProd .= $value[7] . ';' : '';                         
            }
        }
    }    
   
    /**
    * Change Description
    */ 
    protected function changeDesc(){
        $searchArray = array("стиль:", "сезон:", "размеры:", "коллекция:", "размер упаковки:", 
            "состав:", "тип ткани:","обхват груди", "обхват талии", "обхват бедер",
            "длина изделия:", "вес изделия:", "цвета:", "описание:");
        parent::changeDescription($searchArray, []);       
    }
/*
    protected static function setBrandName() {
        $this->brandName = 'ShaArm';
    }
*/
}
//new Shaarm($saw);
////////////$_SESSION['shaarmNameProd'] = []; // создать переменную в сессии
/*
// проверяет проверялся ли уже такой товар
if(isset($_SESSION['shaarmNameProd']) && $_SESSION['shaarmNameProd'] == $nameProd){ // + foreach
    $existProd = FALSE;
    return;
}

$_SESSION['shaarmNameProd'][] = $nameProd;
*/