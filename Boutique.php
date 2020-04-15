<?php


namespace ........; //убрал


class Boutique {

    public $Boutique;
    private $boutique_id;
    private $boutiques_iblock_ID = .....; //убрал
    public $defaultNoImageBoutique = "............................";//убрал

    /**
     * @param $boutique
     *
     * @return Boutique
     */
    static function init($boutique)
    {
        return new self($boutique);
    }


    /**
     * Boutique constructor.
     *
     * @param $boutique
     */
    public function __construct($boutique)
    {
        if (is_numeric($boutique)) {
            $this->boutique_id = $boutique;
            $this->loadBoutiqueData($boutique);
        } elseif (is_array($boutique)) {
            $this->boutique_id = $boutique['ID'];
            $this->Boutique = $boutique;
        }

    }

    /**
     * Первая фото из слайдера
     * @return mixed
     */
    public function SliderFirstPicture()
    {
        if ($this->Boutique) {
            $firstPicture = $this->Boutique['PROPERTIES']['SLIDER']['VALUE'][0];
            if ($firstPicture)
                return \CFile::GetPath($firstPicture);

            return $this->defaultNoImageBoutique;
        }
    }
    /**
     * Первая фото из слайдера
     * @return mixed
     */
    public function SliderPictures()
    {
        if ($this->Boutique) {
            $Pictures = $this->Boutique['PROPERTIES']['SLIDER']['VALUE'];
            if ($Pictures){
                $arSrc=[];
                foreach ($Pictures as $idPict){
                    $arSrc[]=\CFile::GetPath($idPict);
                }

                return $arSrc;
            }
            $defaultArIMG[]=$this->defaultNoImageBoutique;
            return $defaultArIMG;
        }
    }
    /**
     * @param $boutique_id
     */
    protected function loadBoutiqueData($boutique_id)
    {
        $result = \CIBlockElement::GetList([], [
            'IBLOCK_ID' => $this->boutiques_iblock_ID,
            'ID'        => $boutique_id
        ])->GetNextElment();

        $this->Boutique = $result->getFields();
        $this->Boutique['PROPERTIES'] = $result->getProperties();
    }

    /**
     * Разносим номера телефонов на новые строки по разделителям
     * @return mixed|string
     */
    public function FormatPhoneNumber()
    {

            if ($number = $this->Boutique['PROPERTIES']['PHONE']['VALUE']) {
                $number1="";
                if($this->Boutique['PROPERTIES']['MAIN_PHONE']['VALUE']!="") {
                    $number1=$this->Boutique['PROPERTIES']['MAIN_PHONE']['VALUE']."<br>";
                }
                $number1 .= str_replace([";", ','], "<br>", $number);
                return $number1;
            }
            return '';

    }
    /**
     * Разделяем телефоны по разделителям
     * @return mixed|string
     */
    public function ArrayPhoneNumber()
    {
        if ($number = $this->Boutique['PROPERTIES']['PHONE']['VALUE']) {
            $number = str_replace([","], ";", $number);
            $arNumber=explode(";", $number);
            return $arNumber;
        }
        return false;
    }
    public function getMainPhone(){
        if($this->Boutique['PROPERTIES']['MAIN_PHONE']['VALUE']==""){
            $arPhone=$this->ArrayPhoneNumber();
            return $arPhone[0];
        }
        return $this->Boutique['PROPERTIES']['MAIN_PHONE']['VALUE'];
    }
    /**
     * Выделяем дополниельные телефоны
     * @return bool|string
     */
    public function getDopPhone(){
        $phones=$this->ArrayPhoneNumber();
        if(count($phones)>1){
            if(count($phones)>2):
                $dop_phone=implode(", ",array_shift($phones));
            else:
                $dop_phone=$phones[1];
            endif;
            return $dop_phone;
        }
        return false;

    }

}