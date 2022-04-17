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
               $val['children'] = self::tree($arr,$val[$idName],$idName,$pidName);
               $subclass = $val['children'];
               // 获取最大的array
               $val['max'] = Array_pop($subclass);
            }else{
               $val['children'] = [];
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
    ** 筛选
    *? @date 22/03/20 20:27
    *  @param array $data 待过滤数组
    *  @param string $field 要查找的字段
    *  @param $value 要查找的字段值
    *! @return Array
    */
   public static function filter(array $data, string $field, $value){
      $data = 
      array_filter($data, function ($row) use ($field, $value) {
         if (isset($row[$field])) {
            return $row[$field] == $value;
         }
      });
      return $data;
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
    ** 多维数组合并
    *? @date 22/04/17 15:38
    *  @author https://github.com/jwadhams/merge-a-trois
    *  @param $original 原数组
    *  @param $a 数组一
    *  @param $b 数组二
    *! @return Array
    */
   public static function merge(array $original, array $a, array $b){
       $result = [];

       $a_json = json_encode($a);
       $b_json = json_encode($b);
       $o_json = json_encode($original);

       if ($a_json === $o_json) {
           return $b;
       } // No changes in A, return B
       if ($b_json === $o_json) {
           return $a;
       } // No changes in B, return A

       //When merging numeric-indexed arrays, ignore the indexes and just merge the contents.
       if (self::is_numeric_array($a_json) and self::is_numeric_array($b_json)) {
           //In the case of confusion, $b wins, including numeric array order
           //Everything in $b, unless it was known in $original and deleted in $a
           foreach ($b as $item) {
               if (! (in_array($item, $original) && !in_array($item, $a))) {
                   array_push($result, $item);
               }
           }

           //Everything in $a, that's new to BOTH $b and original
           foreach ($a as $item) {
               if (!in_array($item, $original) && !in_array($item, $b)) {
                   array_push($result, $item);
               }
           }
       } else {
           /*
           For associative arrays:
           For every thing on A:
           Exists on B, is complex : recursion
           Exists on B, B differs from Original : B
           Exists on B, B is Original : A
           Doesn't exist on B, doesn't exist on Original : A
           Doesn't exist on B, does exist on Original : skip
           For every thing on B:
           Doesn't exist on A or Original : B
           */


           foreach ($a as $key => $value) {

               // We've had problems in recursion where there's an object in the middle of the tree
               if (isset($original[$key]) && gettype($original[$key]) === 'object') {
                   $original[$key] = json_decode(json_encode($original[$key]), true);
               }
               if (isset($a[$key]) && gettype($a[$key]) === 'object') {
                   $a[$key] = json_decode(json_encode($a[$key]), true);
               }
               if (isset($b[$key]) && gettype($b[$key]) === 'object') {
                   $b[$key] = json_decode(json_encode($b[$key]), true);
               }

               //Does it exist on B?
               if (array_key_exists($key, $b)) {

                   //and is an array (numeric or associative), use recursion
                   if (is_array($a[$key]) and is_array($b[$key])) {
                       //It could be new on both, or a primitive on original:
                       $recur_orig = (array_key_exists($key, $original) and is_array($original[$key])) ? $original[$key] : [];

                       $result[$key] = self::merge($recur_orig, $a[$key], $b[$key]);

                   //Exists on A and B, B the same as origin : use A
                   } elseif (array_key_exists($key, $original) and $original[$key] === $b[$key]) {
                       $result[$key] = $a[$key];

                   //Exists on A and B, B differs from Origin (or is new) : B always wins
                   } else {
                       $result[$key] = $b[$key];
                   }


                   //Does not exist on B, does not exist on origin, use A
               } elseif (! array_key_exists($key, $original)) {
                   $result[$key] = $a[$key];

               //Does not exist on B, did exist on original (deleted) skip
               } else {
               }
           }

           //Now find data inserted on $b that $a doesn't know about
           foreach ($b as $key => $value) {
               if (! array_key_exists($key, $original) and !array_key_exists($key, $a)) {
                   $result[$key] = $value;
               }
           }
       }
       return $result;
   }
   public static function is_numeric_array($json){
      if (!is_string($json)) {
         $json = json_encode($json);
      }
      return substr($json, 0, 1) == '[';
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
