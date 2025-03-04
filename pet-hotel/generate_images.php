<?php
// 定義儲存圖片的資料夾
$uploadDir = 'uploads/';

// 確保 uploads 資料夾存在並有寫入權限
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    echo "已創建 uploads 資料夾\n";
}

// 圖片名稱清單（從您提供的 50 筆資料提取）
$imageNames = [
    // 前 30 筆
    'mao_hai_tian_tang.jpg',
    'wang_xing_ren_le_yuan.jpg',
    'gui_zu_quan_she.jpg',
    'mini_quan_zhi_jia.jpg',
    'kuai_le_wang_wang.jpg',
    'quan_mao_gong_rong.jpg',
    'hao_hua_quan_gong.jpg',
    'xiao_quan_le_yuan.jpg',
    'wang_wang_zhi_jia.jpg',
    'quan_she_tian_tang.jpg',
    'ju_quan_zhi_jia.jpg',
    'mini_quan_le_yuan.jpg',
    'wang_xing_ren_tian_tang.jpg',
    'quan_mao_zhi_jia.jpg',
    'hao_hua_quan_she.jpg',
    'xiao_quan_zhi_jia.jpg',
    'kuai_le_quan_she.jpg',
    'quan_mao_le_yuan.jpg',
    'ju_quan_le_yuan.jpg',
    'mini_quan_tian_tang.jpg',
    'wang_xing_ren_zhi_jia.jpg',
    'quan_mao_tian_tang.jpg',
    'hao_hua_quan_le_yuan.jpg',
    'xiao_quan_tian_tang.jpg',
    'kuai_le_wang_xing_ren.jpg',
    'quan_mao_zhi_jia_2.jpg',
    'ju_quan_tian_tang.jpg',
    'mini_quan_le_yuan_2.jpg',
    'wang_xing_ren_le_yuan_2.jpg',
    'quan_mao_tian_tang_2.jpg',
    // 後 20 筆
    'chong_wu_meng_gong_chang.jpg',
    'quan_le_tian_di.jpg',
    'huang_jia_quan_she.jpg',
    'mini_quan_xing_kong.jpg',
    'wang_wang_le_yuan.jpg',
    'quan_mao_xing_qiu.jpg',
    'hao_hua_quan_yuan.jpg',
    'xiao_quan_xing_ji.jpg',
    'wang_xing_ren_zhi_jia_2.jpg',
    'quan_le_sen_lin.jpg',
    'ju_quan_xing_kong.jpg',
    'mini_quan_hua_yuan.jpg',
    'wang_wang_xing_qiu.jpg',
    'quan_mao_sen_lin.jpg',
    'hao_hua_quan_xing_ji.jpg',
    'xiao_quan_hua_yuan.jpg',
    'kuai_le_wang_zhi_jia.jpg',
    'quan_le_xing_ji.jpg',
    'ju_quan_hua_yuan.jpg',
    'mini_quan_zhi_jia_2.jpg'
];

// 從 Lorem Picsum 下載圖片
foreach ($imageNames as $index => $filename) {
    // 使用不同的 seed 確保每張圖片不同
    $url = "https://picsum.photos/seed/" . ($index + 1) . "/800/600";
    $path = $uploadDir . $filename;

    // 下載並儲存圖片
    $imageContent = file_get_contents($url);
    if ($imageContent !== false) {
        file_put_contents($path, $imageContent);
        echo "已生成圖片: $path\n";
    } else {
        echo "下載失敗: $filename\n";
    }
}

echo "所有圖片生成完畢！";
?>