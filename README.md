# 課題8 -パーソナルトレーナーとメンバーのアプリ-

## ①課題内容（どんな作品か）
- パーソナルトレーナーが管理者で、トレーニーの状況把握ができるアプリ
- 
- トレーニーは自分の情報しか見られない
- ユーザー登録時にkanri_flgは見えなくして全員０になるように設定し、adminで入る人のみ全データー把握できるようにした
- トレーニーは目標体重と体脂肪率をユーザー登録で設定し、現在の体重や体脂肪率と比較でき、BMIの測定が計算できるようにした
- トレーナーとトレーニー双方でコミュニケーションできるチャットを完備した（うまく時系列表示できていないので未完成）
- adminで入るとトレーナー画面で、現在のトレーニーの状況が一覧把握できる
- 名前を押すと、それぞれの画面に飛び、詳細を確認できる
- 状況を見てコメントを残す機能もつけた

## ②工夫した点・こだわった点
- 使う人（トレーナーとトレーニー）それぞれが欲しい情報がわかるようにしたこと

## ③難しかった点・次回トライしたいこと(又は機能)
- トレーナーからトレーニー画面に飛ぶ時のlidの切り替え。セッションlidだとトレーナーのデータが出てしまうので、管理フラグ１の場合はgetでとり、それ以外はセッションidでlidを認識させる関数を作った
- 

## ④質問・疑問・感想、シェアしたいこと等なんでも
- [感想]
- phpが楽しくなってしまいpython中断しています…次回はpythonで課題提出しようかな…