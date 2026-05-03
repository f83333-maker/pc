#!/usr/bin/env bash
# perf: 重建首页专用精简 bundle (Cartoon 主题首页 Footer.html 引用)
# 目标:  二段分包, 浏览器并行下载, 比通用 _.js 砍掉 ~60% 体积
#         vendor (~252KB / gz 86KB):  jquery + util + layer + toastr + qrcode + ...
#         layui  (~346KB / gz 113KB): layui 框架 (独立分包, 与 vendor 并行下载)
# 用法:  在网站根目录执行  bash scripts/build-index-lite.sh
# 依赖:  node + npx (会自动拉 terser)
# 注意:  修改了任意一个 SOURCES 内的源文件后, 都需要重新执行本脚本

set -euo pipefail

cd "$(dirname "$0")/.."

# ---------- Bundle A: vendor (jQuery + 工具/UI 库) ----------
VENDOR_RAW="assets/common/js/_vendor_lite.tmp.js"
VENDOR_MIN="assets/common/js/_vendor_lite.min.js"

VENDOR_SOURCES=(
  "assets/common/js/jquery.min.js"
  "assets/common/js/util/dict.js"
  "assets/common/js/util.js"
  "assets/common/js/language.js"
  "assets/common/js/format.js"
  "assets/common/js/message.js"
  "assets/common/js/toastr.min.js"
  "assets/common/js/layer/layer.js"
  "assets/common/js/component/loading.js"
  "assets/common/js/component.js"
  "assets/common/js/jquery.qrcode.min.js"
  "assets/common/js/component/decimal.js"
  "assets/user/js/trade.js"
  "assets/user/js/treasure.js"
)

echo "[build-index-lite] 1/4 合并 vendor 包 (${#VENDOR_SOURCES[@]} 个源文件)..."
: > "$VENDOR_RAW"
for f in "${VENDOR_SOURCES[@]}"; do
  if [ ! -f "$f" ]; then
    echo "  缺失: $f" >&2
    exit 1
  fi
  cat "$f" >> "$VENDOR_RAW"
  printf '\n;\n' >> "$VENDOR_RAW"
done
echo "  合并后: $(($(wc -c < "$VENDOR_RAW")/1024)) KB"

echo "[build-index-lite] 2/4 terser 压缩 vendor..."
npx -y terser "$VENDOR_RAW" -c -m --ecma 2020 -o "$VENDOR_MIN"
echo "  压缩后: $(($(wc -c < "$VENDOR_MIN")/1024)) KB"
echo "  gzip后: $(($(gzip -9c "$VENDOR_MIN" | wc -c)/1024)) KB"
rm -f "$VENDOR_RAW"

# ---------- Bundle B: layui (独立分包, 与 vendor 并行下载) ----------
LAYUI_SRC="assets/common/js/layui/layui.js"
LAYUI_MIN="assets/common/js/_layui_lite.min.js"

echo "[build-index-lite] 3/4 terser 压缩 layui..."
if [ ! -f "$LAYUI_SRC" ]; then
  echo "  缺失: $LAYUI_SRC" >&2
  exit 1
fi
npx -y terser "$LAYUI_SRC" -c -m --ecma 2020 -o "$LAYUI_MIN"
echo "  压缩后: $(($(wc -c < "$LAYUI_MIN")/1024)) KB"
echo "  gzip后: $(($(gzip -9c "$LAYUI_MIN" | wc -c)/1024)) KB"

echo "[build-index-lite] 4/4 完成"
echo "  vendor -> $VENDOR_MIN"
echo "  layui  -> $LAYUI_MIN"
echo "[build-index-lite] 部署后请清空 Smarty 编译缓存:"
echo "    rm -rf runtime/view/cache/* runtime/view/compile/*"
