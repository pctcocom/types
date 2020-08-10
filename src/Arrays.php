<?php
namespace Pctco\Types;
/**
 * 数组
 */
class Arrays{
   /**
   * @name tree
   * @describe 遍历 无限子类
   * @param mixed $arr		查询数据库信息,返回过来的数组
   * @param mixed $pid		要查询的PID,一般从0开始
   * @param mixed $idName		id的字段名称
   * @param mixed $pidName	pid的字段名称
   * @param mixed $jsonName	生成的Json文件名 如果是 false 则不生成
   * @param mixed $sort		排序 需要排序的字段名称
   * @param mixed $multi_sort		排序 按照某个字段的值重新排序
   * @return Array
   **/
   public static function tree(
      &$arr,
      $pid = 0,
      $idName = 'id',
      $pidName = 'pid',
      $jsonName = false,
      $sort = false,
      $multi_sort = SORT_ASC
   ){
      $childs = array();
      foreach($arr as $key=>$val){
         if($val[$pidName] == $pid){
            if(!empty($val[$idName])){
               $val['sub'] = self::tree($arr,$val[$idName],$idName,$pidName);
               $subclass = $val['sub'];
               // 获取最大的array
               $val['max'] = Array_pop($subclass);
            }else{
               $val['sub'] = [];
               $val['max'] = [];
            }
            $childs[] = $val;
         }
      }

      // 按照某个字段的值重新排序
      if($sort){
         $arr = array_map(create_function('$n', 'return $n["'.$sort.'"];'),$childs);
         array_multisort($arr,$multi_sort,$childs);
      }
      // 生成json文件
      if($jsonName){
         $json = json_encode($childs);
         file_put_contents(ROOT_PATH.'static/json/'.$jsonName.'.json',$json);
      }
      return $childs;
   }
   /**
   * @name upset
   * @describe 将数组顺序打乱
   * @param mixed $arr
   * @return Array
   **/
   public static function upset($arr){
      if (!is_array($arr)) return $arr;

      $keys = array_keys($arr);
      shuffle($keys);
      $random = array();
      foreach ($keys as $key)
      $random[$key] = self::upset($arr[$key]);
      return $random;
   }
}
