# Types
php data type package processing!

## Install
```
composer require pctco/types dev-master

use Pctco\Types\Arrays
```

* https://github.com/spatie/array-to-xml
* https://github.com/nullivex/lib-array2xml
* https://github.com/spatie/array-functions
* https://github.com/voku/Arrayy

## Boolean

## Array
```
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
Arrays::tree($arr);
```

```
/**
* @name upset
* @describe 将数组顺序打乱
* @param mixed $arr
* @return Array
**/
Arrays::upset($arr);
```

```
/**
* @name Value added
* @describe 将数组值合并 并且相加
* @param mixed $arr
* @return Array
**/
Arrays::value_added([12=>5,13=>5],[12=>5,13=>6,14=>2]);
```

## String
```

```

## Resource
