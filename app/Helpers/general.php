<?php
define('PAGINATION_COUNT' , 15); 

 function getFolder(){
    return app()->getLocale() == 'ar' ?'css-rtl' : 'css';
}
function uploadImage($folder,$image){
    $image->store('/', $folder);
    $filename = $image->hashName();
    return  $filename;
}
 function subCatRecursion($categories, $counter, $char){
    foreach($categories as $cat){
        $space = "";
        $style= "";
        $temp=$counter;
        while($temp>0){
            $space.="&nbsp&nbsp&nbsp";
            $style.= $char;
            $temp--;
        }
 
        if(isset($cat->id)){
            echo '<option value=" ' . $cat->id . '"> ' . $space . $style .
             $cat->name . '</option>';
        }
        if(isset($cat->_childs)){
            subCatRecursion($cat->_childs, $counter+1, $char);
        }
    }
}