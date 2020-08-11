# Types
php data type package processing!


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

## String
```

```

## Resource
