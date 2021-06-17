<?php
namespace Pctco\Types;
/**
 * 字符串
 */
class Helper{
   private $str;
   function __construct($str){
      $this->str = $str;
   }
   /**
   * @name 判断是否为 纯数字
   **/
   public function isNumber(){
      return is_numeric($this->str);
   }
   /**
   * @name 判断是否为 邮箱格式
   **/
   public function isEmail(){
      return strlen(filter_var($this->str,FILTER_VALIDATE_EMAIL)) === 0?false:true;
   }
}
