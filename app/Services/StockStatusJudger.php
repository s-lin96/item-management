<?php 
namespace App\Services;

class StockStatusJudger {
    /**
     * 在庫状況を判定するメソッド
     * 
     * @param int $stock        現在の在庫数
     * @param int $safeStock    安全在庫数
     * @return int              在庫状況（1="十分", 2="少なめ", 3="不足"）
     */
    public function judge(int $stock, int $safeStock): int
    {
        // 現在の在庫数が 安全在庫数以上 ならば･･･
        if($stock >= $safeStock){
            return 1;
        }
        // 現在の在庫数が 安全在庫数未満 かつ 安全在庫数の7割以上 ならば･･･
        elseif($stock >= $safeStock * 0.7 && $stock < $safeStock){
            return 2;
        }
        // 現在の在庫数が 安全在庫数の7割未満 ならば･･･
        else{
            return 3;
        }
    }
}