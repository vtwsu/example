<?
namespace Local\Catalog;

class CatPrice
{
    /**
     * @param $perOne
     * @param $perTwo
     * @param $perThree
     * @param $perFoure
     * @param $perFive
     * @return mixed
     */

    // id товара, группа пользователя, тип каталога (необязательно), зависимость 1, зависимость 2
    public static function getPrice($perOne, $perTwo, $perThree, $perFoure, $perFive) {
я
        $allProductPrices = \Bitrix\Catalog\PriceTable::getList([
            "select" => ["*"],
            "filter" => [
                "=PRODUCT_ID" => $perOne,
            ],
        ])->fetchAll();

        foreach ($allProductPrices as $arPriceItem) {
            if($perThree) {
                switch($perTwo) {
                    case "quest":
                    case USER_B2C:
                        if ($perThree == "retail" && $arPriceItem["PRICE"] && $perFoure != 635 && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        }
                        break;
                    case USER_B2P:
                        if($perThree == "retail") {
                            if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        } else {
                            if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_PROF) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        }
                        break;
                    case USER_B2B:
                        if($perThree == "retail") {
                            if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        } else {
                            if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        }
                        break;
                    case USER_B2E:
                        if($perThree == "retail") {
                            if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        } else {
                            if($arPriceItem["PRICE"] && $perFive == 745 && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                                $resultGetPrice =  $arPriceItem["PRICE"];
                            }
                        }
                        break;
                }
            } else {
                switch($perTwo) {
                    case "quest":
                    case USER_B2C:
                        if ($arPriceItem["PRICE"] && $perFoure != 635 && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        }
                        break;
                    case USER_B2P:
                        if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_PROF) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        }
                        break;
                    case USER_B2B:
                        if ($arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        }
                        break;
                    case USER_B2E:
                        if ($arPriceItem["PRICE"] && $perFive == 745 && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_OPT) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        } elseif(!$resultGetPrice && $arPriceItem["PRICE"] && $arPriceItem["CATALOG_GROUP_ID"] == ID_PRICE_RETAIL) {
                            $resultGetPrice =  $arPriceItem["PRICE"];
                        }
                        break;
                }
            }
        }
        return $resultGetPrice;
    }
}
