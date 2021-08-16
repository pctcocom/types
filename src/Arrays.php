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
         file_put_contents(app()->getRootPath().'entrance/static/library/json/tree/'.$jsonName.'.json',$json);
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
   /**
   * @name Value added
   * @describe 将数组值合并 并且相加
   * @param mixed $arr
   * @return Array Arrays::value_added([12=>5,13=>5],[12=>5,13=>6,14=>2]);
   **/
   public static function value_added($arr){
      $all = [];
      foreach ($arr as $va) {
         $all = $va + $all;
      }
      $new = [];
      foreach (array_keys($all) as $value) {
         foreach ($arr as $k => $v) {
            if (!empty($v[$value])) {
               $new[$value] = empty($new[$value])?$v[$value]:$new[$value]+$v[$value];
            }
         }
      }
      return $new;
   }

   /**
   * @name Object To Array
   * @describe 将对象数组转成普通数组
   * @param mixed $oa  Object Array
   * @return Array
   **/
   public static function ObjectToArray($oa){
      $_array = is_object($oa) ? get_object_vars($oa) : $oa;
      $array = [];
      foreach ($_array as $key => $value) {
         $value = (is_array($value) || is_object($value)) ? self::ObjectToArray($value) : $value;
         $array[$key] = $value;
      }
      return $array;
   }
   /**
   * @name Object To Array
   * @describe 增删改查
   * $options
   * @param mixed $arr  Array
   * @param mixed $tval  value 需要查找的值
   * @param mixed $rval  value 需要替换的值
   * @param mixed $op  add|delete|update|check (return false(没有),number(有))
   * @param mixed $unique  true = 唯一不能重复, false = 不限制
   * @return Array
   **/
   public static function ADUC($options){
      $o = array_merge([
         'arr'   =>   [],
         'tval'   =>   'tval',
         'rval'   =>   'rval',
         'op'   =>   false,
         'unique'   =>   true
      ],$options);

      $TvalKey = array_search($o['tval'],$o['arr']);

      if ($o['op'] === 'add') {
         if ($o['unique']) {
            if ($TvalKey === false) $o['arr'] = array_merge($o['arr'],[$o['tval']]);
         }else{
            $o['arr'] = array_merge($o['arr'],[$o['tval']]);
         }
      }
      if ($o['op'] === 'add::unique') {
         if ($TvalKey === false) $o['arr'] = array_merge($o['arr'],[$o['tval']]);
      }


      if ($o['op'] === 'delete') {
         if ($TvalKey !== false) unset($o['arr'][$TvalKey]);
      }

      if ($o['op'] === 'update') {
         if ($o['unique']) {
            $RvalKey = array_search($o['rval'],$o['arr']);
            if ($RvalKey === false) $o['arr'][$TvalKey] = $o['rval'];
         }else{
            if ($TvalKey !== false) $o['arr'][$TvalKey] = $o['rval'];
         }

      }

      if ($o['op'] === 'check') {
         $o['arr'] = $key;
      }


      return $o['arr'];
   }


}
