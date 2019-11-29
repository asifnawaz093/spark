<?php
$fc = FC::getInstance();

if($fc->error){
    if(is_array($fc->error)){
        foreach($fc->error as $error){
            echo ObjectHtml::error($error);
        }
    }
    else{
        echo ObjectHtml::error($fc->error);
    }
}

if($fc->success){
    if(is_array($fc->success)){
        foreach($fc->success as $success){
            echo ObjectHtml::success($success);
        }
    }
    else{
        echo ObjectHtml::success($fc->success);
    }
}

if($fc->msg){
    if(is_array($fc->msg)){
        foreach($fc->msg as $msg){
            echo $msg;
        }
    }
    else{
        echo $fc->msg;
    }
}

?>