#!/usr/bin/env bash
# perf: 重建首页专用精简 bundle (Cartoon 主题首页 Footer.html 引用)
# 目标:  把首页/商品交易弹窗实际依赖的 JS 合并 + minify, 比通用 _.js 砍掉 ~40% 体积
# 用法:  在网站根目录执行  bash scripts/build-index-lite.sh
# 依赖:  node + npx (会自动拉 terser)
# 注意:  修改了任意一个 SOURCES 内的源文件后, 都需要重新执行本脚本

set -euo pipefail

cd "$(dirname "$0")/.."

OUT_RAW="assets/common/js/_index_lite.tmp.js"
OUT_MIN="assets/common/js/_index_lite.min.js"

# 首页 + 商品交易弹窗实际依赖的源文件 (顺序敏感)
SOURCES=(
  "assets/common/js/jquery.min.js"
  "assets/common/js/util/dict.js"
  "assets/common/js/util.js"
  "assets/common/js/language.js"
  "assets/common/js/format.js"
  "assets/common/js/message.js"
  "assets/common/js/toastr.min.js"
  "assets/common/js/layer/layer.js"
  "assets/common/js/layui/layui.js"
  "assets/common/js/component/loading.js"
  "assets/common/js/component.js"
  "assets/common/js/jquery.qrcode.min.js"
  "assets/common/js/component/decimal.js"
  "assets/common/fonts/base/iconfont.js"
  "assets/user/js/trade.js"
  "assets/user/js/treasure.js"
)

echo "[build-index-lite] 1/3 合并 ${#SOURCES[@]} 个源文件..."
: > "$OUT_RAW"
for f in "${SOURCES[@]}"; do
  if [ ! -f "$f" ]; then
    echo "  缺失: $f" >&2
    exit 1
  fi
  cat "$f" >> "$OUT_RAW"
  printf '\n;\n' >> "$OUT_RAW"
done
RAW_SIZE=$(wc -c < "$OUT_RAW")
echo "  合并后: $((RAW_SIZE/1024)) KB"

echo "[build-index-lite] 2/3 terser 压缩..."
npx -y terser "$OUT_RAW" -c -m --ecma 2020 -o "$OUT_MIN"
MIN_SIZE=$(wc -c < "$OUT_MIN")
echo "  压缩后: $((MIN_SIZE/1024)) KB"

GZ_SIZE=$(gzip -9c "$OUT_MIN" | wc -c)
echo "  gzip后: $((GZ_SIZE/1024)) KB"

echo "[build-index-lite] 3/3 清理临时文件..."
rm -f "$OUT_RAW"

echo "[build-index-lite] 完成 -> $OUT_MIN"
echo "[build-index-lite] 部署后请清空 Twig 编译缓存:  rm -rf runtime/view/cache/* runtime/view/compile/*"
