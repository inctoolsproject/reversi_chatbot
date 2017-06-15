<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';
// 合成のベースとなるサイズを定義
define('GD_BASE_SIZE', 700);

// 合成のベースになる画像を生成
$destinationImage = imagecreatefrompng('imgs/reversi_board.png');

// パラメータから現在の石の配置を取得
$stones = json_decode(explode($_REQUEST['stones']);

//各列をループ
for($i = 0; $i < count($stones); $i++) {
    $row = $stones[$i];
//各要素をループ
for($j = 0; $j < count($row); $j++) {
  //現在の石を生成
  if($row[$j] == 1) {
      $stoneImage = imagecreatefrompng('imgs/reversi_stone_white.png');
    } elseif($row[$j] == 2) {
      $stoneImage = imagecreatefrompng('imgs/reversi_stone_black.png');
    }
  //合成
    if($row[$j] > 0) {
       imagecopy($destinationImage, $stoneImage, 9 + (int)($j * 87.5), 9 + (int)($i * 87.5), 0, 0, 70, 70);
  //破棄
       imagedestroy($stoneImage);
    }
  }
}

// リクエストされているサイズを取得
$size = $_REQUEST['size'];
// ベースサイズと同じなら何もしない
if($size == GD_BASE_SIZE) {
  $out = $destinationImage;
}
// 違うサイズの場合
else {
  // リクエストされたサイズの空の画像を生成
  $out = imagecreatetruecolor($size ,$size);
  // リサイズしながら合成
  imagecopyresampled($out, $destinationImage, 0, 0, 0, 0, $size, $size, GD_BASE_SIZE, GD_BASE_SIZE);
}

/ 出力のバッファリングを有効に
ob_start();
// バッファに出力
imagepng($out, null, 9);
// バッファから画像を取得
$content = ob_get_contents();
// バッファを消去し出力のバッファリングをオフ
ob_end_clean();

// 出力のタイプを指定
header('Content-type: image/png');
echo $content;

?>
