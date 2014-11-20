<?php

class PatcherParser {
   protected  $patcherPath;
   
   public function setPatcherPath($path){
       $this->patcherPath = $path;
   }
   
   public function getPatcherPath(){
       return $this->patcherPath;
       
   }
    
   
}

