function getNovLink() {
    $settingCatalog = CIBlockElement::GetByID(CATALOG_PARAM)->GetNextElement()->GetProperties();
    if($settingCatalog["NOVELTY_WORK"]["VALUE_XML_ID"] == "NOVELTY_WORK_M") {
        return "/novelties/";
    } else if($settingCatalog["NOVELTY_WORK"]["VALUE_XML_ID"] == "NOVELTY_WORK_AUTO") {
        return "anovelties";
    }
    return false;
}

/* *** */

function saleProductUpdate() {
    global $DB;
    $propCatalog = CIBlockElement::GetByID(CATALOG_ID)->GetNextElement()->GetProperties();
    $dayCount;
    $sellCount;
    if($propCatalog["BESTSELLER_PRODUCT"]["VALUE_XML_ID"] == "BESTSELLER_PRODUCT_A") {
        if($propCatalog["BESTSELLER_PRODUCT_DAY"]["VALUE"]): $dayCount = $propCatalog["BESTSELLER_PRODUCT_DAY"]["VALUE"]; else: $dayCount = 30; endif;
        if($propCatalog["BESTSELLER_PRODUCT_SELL"]["VALUE"]): $sellCount = $propCatalog["BESTSELLER_PRODUCT_SELL"]["VALUE"]; else: $sellCount = 1; endif;

        CModule::IncludeModule("sale");
        $month = time() - 48400 * $dayCount;
        $arFilter = [
            "CANCELED" => "N",
            "PAYED" => "Y",
            ">=DATE_INSERT" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), $month)
        ];
        $db_sales = CSaleOrder::GetList(
            ["select" => "ID"],
            $arFilter,
            false,
            false,
            ["ID", "DATE_INSERT_FORMAT"]
        );
        $arOrders = []; // Массив заказов
        while ($ar_sales = $db_sales->Fetch()) {
            $arOrders[] = $ar_sales["ID"];
        }
        $arOrderProduct = []; // Массив товаров из заказов
        foreach ($arOrders as $key => $order_id) {
            $dbBasketItems = CSaleBasket::GetList([], ["ORDER_ID" => $order_id], false, false, []);
            $product = [];
            while ($arItems = $dbBasketItems->Fetch()) {
                $arOrderProduct[$arItems["PRODUCT_ID"]][] = "Y";
            }
            unset($dbBasketItems);
        }
        $arBestseller = []; // ID товаров которые были проданы больше N раз. По умолчанию - 1.
        foreach ($arOrderProduct as $productID => $arProduct) {
            if (count($arProduct) > $sellCount) {
                $arBestseller[] = $productID;
            }
        }
        $propAllBestsellerSelect = ["ID", "PROPERTY_BESTSELLER_VAl"];
        $propAllBestsellerFilter = ["IBLOCK_ID"=>IBLOCK_ID_CATALOG, "ACTIVE"=>"Y", "PROPERTY_BESTSELLER_VAl"];
        $propAllBestseller = CIBlockElement::GetList([], $propAllBestsellerFilter, false, false, $propAllBestsellerSelect);
        $activeItemBestseller = []; // Массив товаров которые будут обнулены ниже и после перезаписаны
        while($propItemBestseller = $propAllBestseller -> GetNext()) {
            CIBlockElement::SetPropertyValuesEx($propItemBestseller["ID"], IBLOCK_ID_CATALOG, ["BESTSELLER_VAl" => ""]);
            $activeItemBestseller[] = $propItemBestseller["ID"];
        }
        foreach ($arBestseller as $key => $productID) {
            if(in_array($productID, $activeItemBestseller)) {
                CIBlockElement::SetPropertyValuesEx($productID, IBLOCK_ID_CATALOG, ["BESTSELLER_VAl" => count($arProduct)]);
            }
        }
    }
}
