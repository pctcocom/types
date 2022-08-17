<?php
namespace Pctco\Types\String;
/**
 * 数组
 */
class Username{
   /** 
    ** 生产数据
    *? @date 22/08/17 15:09
    */
   public static function production(){
		$randomWordLength = self::randomWordLength();
		$word_length = $randomWordLength['length'];
		$chance = $randomWordLength['chance'];
		$word = '';
		$char_map = [];
		for ($i = $j = 1, $ref = $word_length; 1 <= $ref ? $j <= $ref : $j >= $ref; $i = 1 <= $ref ? ++$j
				: --$j) {
			$character = self::randomAtoZ();
			$word .= $character['char'];
			$char_map[] = $character;
		}
		return [
         'word_length' => $word_length,
			'chance' =>   $chance,
			'word' =>  $word,
			'char_map' => $char_map
      ];
   }
   /** 
    ** 随机A-Z
    *? @date 22/08/17 14:28
    *  @param myParam1 Explain the meaning of the parameter...
    *  @param myParam2 Explain the meaning of the parameter...
    *! @return 
    */
   public static function randomAtoZ(){
      $random = self::randomNumber();
      $lookup = self::letterFrequency();
      $prev = 0;
      foreach ($lookup as $key => $value) {
         $chance = ($value - $prev) / 1000 . '%';
         if ($random < $value) {
            return [
               'char' =>   $key,
               'charfreq' =>  $value,
               'chance' => $chance
            ];
         }
         $prev = $value;
      }

      return false;
   }
   /** 
    ** 用户名随机长度
    *? @date 22/08/17 14:50
    *  @param myParam1 Explain the meaning of the parameter...
    *  @param myParam2 Explain the meaning of the parameter...
    *! @return Array
    */
   public static function randomWordLength(){
      $total = 0;
      // 单词长度在英语语言中的频率从1-19个字符
      $percentages = [ 0.1, 0.6, 2.6, 5.2, 8.5, 12.2, 14.0, 14.0, 12.6, 10.1, 7.5, 5.2, 3.2, 2.0, 1.0, 0.6, 0.3, 0.2, 0.1 ];

      $wordfrequency = [];
      foreach ($percentages as $v) {
         $amount = $total + (($v / 100) * 100000);
         $wordfrequency[] = $amount;
         $total = $amount;
      }

      $random = self::randomNumber();
		$length = 0;
		$lookup = [
         1  => $wordfrequency[0],
         2  => $wordfrequency[1],
         3  => $wordfrequency[2],
         4  => $wordfrequency[3],
         5  => $wordfrequency[4],
         6  => $wordfrequency[5],
         7  => $wordfrequency[6],
         8  => $wordfrequency[7],
         9  => $wordfrequency[8],
         10 => $wordfrequency[9],
         11 => $wordfrequency[10],
         12 => $wordfrequency[11],
         13 => $wordfrequency[12],
         14 => $wordfrequency[13],
         15 => $wordfrequency[14],
         16 => $wordfrequency[15],
         17 => $wordfrequency[16],
         18 => $wordfrequency[17],
         19 => $wordfrequency[18]
      ];
		$prev = 0;

      foreach ($lookup as $key => $value) {
         $chance = round($value - $prev) / 1000 . '%';
         if ($random < $value) {
            return [
               'length' => $key,
               'chance' => $chance
            ];
         }
         $prev = $value;
      }
   }
   /** 
    ** 随机数
    *? @date 22/08/17 14:55
    *  @param int $min 开始值
    *  @param int $max 结束值
    *! @return Number
    */
   public static function randomNumber(int $min = 0,int $max = 1){
      return ($min + mt_rand()/mt_getrandmax() * ($max-$min)) * 100000;
   }
   /** 
    ** Letter_frequency
    *? @date 22/08/17 14:25
    *  @url http://en.wikipedia.org/wiki/Letter_frequency
    *! @return Array
    */
   public static function letterFrequency(){
      return [
         'a' => 8167,
         'b' => 9659,
         'c' => 12441,
         'd' => 16694,
         'e' => 29396,
         'f' => 31624,
         'g' => 33639,
         'h' => 39733,
         'i' => 46699,
         'j' => 46852,
         'k' => 47624,
         'l' => 51649,
         'm' => 54055,
         'n' => 60804,
         'o' => 68311,
         'p' => 70240,
         'q' => 70335,
         'r' => 76322,
         's' => 82649,
         't' => 91705,
         'u' => 94463,
         'v' => 95441,
         'w' => 97801,
         'x' => 97951,
         'y' => 99925,
         'z' => 100000
      ];
   }
}
