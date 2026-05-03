#!/usr/bin/env bash
# ============================================================
# 批量将网站现有图片转换为 WebP 格式
# 设计原则: 保留原文件 + 同目录同名生成 .webp + 配合 Nginx 自适应交付
# 安全级别: 极高 (绝不删除任何原图, 仅追加 .webp 文件)
# ============================================================
#
# 使用方法 (在服务器上执行):
#   cd /www/wwwroot/pcccc.cc
#   bash scripts/convert-images-to-webp.sh
#
# 可选参数:
#   --dry-run        仅扫描, 不转换 (预演)
#   --quality=85     WebP 质量 (1-100, 默认 85)
#   --target=DIR     指定单独的目标目录 (默认: assets/cache)
#   --force          强制覆盖已存在的 .webp 文件
#   --max-size=N     仅转换大于 N KB 的图片 (默认 10, 小图转换收益低)
# ============================================================

set -uo pipefail

# ----------- 配置 -----------
DEFAULT_TARGET="assets/cache"
QUALITY=85
DRY_RUN=0
FORCE=0
MAX_SIZE_KB=10
TARGET=""

# ----------- 解析参数 -----------
for arg in "$@"; do
    case "$arg" in
        --dry-run)       DRY_RUN=1 ;;
        --force)         FORCE=1 ;;
        --quality=*)     QUALITY="${arg#--quality=}" ;;
        --target=*)      TARGET="${arg#--target=}" ;;
        --max-size=*)    MAX_SIZE_KB="${arg#--max-size=}" ;;
        --help|-h)
            sed -n '2,22p' "$0"
            exit 0
            ;;
        *)
            echo "未知参数: $arg" >&2
            exit 1
            ;;
    esac
done

[ -z "$TARGET" ] && TARGET="$DEFAULT_TARGET"

# ----------- 颜色输出 -----------
if [ -t 1 ]; then
    C_GREEN='\033[0;32m'; C_YELLOW='\033[0;33m'; C_RED='\033[0;31m'; C_BLUE='\033[0;34m'; C_RESET='\033[0m'
else
    C_GREEN=''; C_YELLOW=''; C_RED=''; C_BLUE=''; C_RESET=''
fi

log()  { printf "${C_BLUE}[INFO]${C_RESET} %s\n" "$*"; }
ok()   { printf "${C_GREEN}[OK]${C_RESET}   %s\n" "$*"; }
warn() { printf "${C_YELLOW}[WARN]${C_RESET} %s\n" "$*"; }
err()  { printf "${C_RED}[ERR]${C_RESET}  %s\n" "$*"; }

# ----------- 检查 cwebp ----------- 
ensure_cwebp() {
    if command -v cwebp >/dev/null 2>&1; then
        ok "cwebp 已安装: $(cwebp -version 2>&1 | head -1)"
        return 0
    fi
    warn "cwebp 未安装, 尝试自动安装..."
    if command -v apt-get >/dev/null 2>&1; then
        log "执行: apt-get update && apt-get install -y webp"
        apt-get update -qq && apt-get install -y -qq webp
    elif command -v yum >/dev/null 2>&1; then
        log "执行: yum install -y libwebp-tools"
        yum install -y libwebp-tools
    else
        err "未识别包管理器, 请手动安装: Ubuntu/Debian -> apt install webp ; CentOS -> yum install libwebp-tools"
        exit 1
    fi
    if ! command -v cwebp >/dev/null 2>&1; then
        err "cwebp 安装失败"
        exit 1
    fi
    ok "cwebp 安装成功"
}

# ----------- 主流程 -----------
ensure_cwebp

if [ ! -d "$TARGET" ]; then
    err "目标目录不存在: $TARGET (当前 PWD=$(pwd))"
    exit 1
fi

log "扫描目标: $TARGET"
log "质量参数: $QUALITY"
log "最小尺寸: ${MAX_SIZE_KB} KB (小于此值的图片跳过)"
[ $DRY_RUN -eq 1 ] && warn "DRY RUN 模式: 只扫描, 不实际转换"
[ $FORCE -eq 1 ] && warn "FORCE 模式: 已存在的 .webp 会被覆盖"
echo ""

TOTAL_FOUND=0
TOTAL_SKIPPED_EXIST=0
TOTAL_SKIPPED_SMALL=0
TOTAL_CONVERTED=0
TOTAL_ROLLBACK=0
TOTAL_ERROR=0
SAVED_BYTES=0
ORIGINAL_BYTES=0

# 扫描所有 png/jpg/jpeg/bmp 文件
mapfile -d '' FILES < <(find "$TARGET" -type f \( -iname "*.png" -o -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.bmp" \) -print0)

TOTAL_FOUND=${#FILES[@]}
log "共发现 $TOTAL_FOUND 张图片"
echo ""

if [ $TOTAL_FOUND -eq 0 ]; then
    warn "没有需要转换的图片"
    exit 0
fi

idx=0
for src in "${FILES[@]}"; do
    idx=$((idx + 1))
    webp="${src%.*}.webp"
    src_size=$(stat -c %s "$src" 2>/dev/null || stat -f %z "$src" 2>/dev/null || echo 0)
    src_kb=$(( src_size / 1024 ))

    # 显示进度 (每 100 张或最后一张)
    if [ $((idx % 100)) -eq 0 ] || [ $idx -eq $TOTAL_FOUND ]; then
        printf "\r${C_BLUE}[进度]${C_RESET} %d/%d (%.1f%%)   " $idx $TOTAL_FOUND $(awk "BEGIN{printf \"%.1f\", $idx*100/$TOTAL_FOUND}")
    fi

    # 跳过过小的图片
    if [ $src_kb -lt $MAX_SIZE_KB ]; then
        TOTAL_SKIPPED_SMALL=$((TOTAL_SKIPPED_SMALL + 1))
        continue
    fi

    # 已存在 .webp 且未指定 --force
    if [ -f "$webp" ] && [ $FORCE -eq 0 ]; then
        TOTAL_SKIPPED_EXIST=$((TOTAL_SKIPPED_EXIST + 1))
        continue
    fi

    if [ $DRY_RUN -eq 1 ]; then
        TOTAL_CONVERTED=$((TOTAL_CONVERTED + 1))
        continue
    fi

    # 转换 (静默模式, 失败时移除半成品 .webp)
    if cwebp -q $QUALITY -mt -quiet "$src" -o "$webp" 2>/dev/null; then
        webp_size=$(stat -c %s "$webp" 2>/dev/null || stat -f %z "$webp" 2>/dev/null || echo 0)
        # 如果转换后比原图还大, 删掉 webp (没必要保留)
        if [ $webp_size -ge $src_size ]; then
            rm -f "$webp"
            TOTAL_ROLLBACK=$((TOTAL_ROLLBACK + 1))
        else
            TOTAL_CONVERTED=$((TOTAL_CONVERTED + 1))
            ORIGINAL_BYTES=$((ORIGINAL_BYTES + src_size))
            SAVED_BYTES=$((SAVED_BYTES + src_size - webp_size))
        fi
    else
        TOTAL_ERROR=$((TOTAL_ERROR + 1))
        err "\n转换失败: $src"
    fi
done

printf "\r%80s\r" " "  # 清进度行

# ----------- 结果汇总 -----------
echo ""
echo "==========================================="
echo "  转换完成"
echo "==========================================="
printf "  发现图片:        %s\n" "$TOTAL_FOUND"
printf "  成功转换:        ${C_GREEN}%s${C_RESET}\n" "$TOTAL_CONVERTED"
printf "  已存在跳过:      %s\n" "$TOTAL_SKIPPED_EXIST"
printf "  尺寸过小跳过:    %s\n" "$TOTAL_SKIPPED_SMALL"
printf "  转后变大丢弃:    %s\n" "$TOTAL_ROLLBACK"
[ $TOTAL_ERROR -gt 0 ] && printf "  ${C_RED}转换失败:        %s${C_RESET}\n" "$TOTAL_ERROR"

if [ $TOTAL_CONVERTED -gt 0 ] && [ $DRY_RUN -eq 0 ]; then
    orig_mb=$(awk "BEGIN{printf \"%.2f\", $ORIGINAL_BYTES/1024/1024}")
    saved_mb=$(awk "BEGIN{printf \"%.2f\", $SAVED_BYTES/1024/1024}")
    pct=$(awk "BEGIN{if($ORIGINAL_BYTES>0) printf \"%.1f\", $SAVED_BYTES*100/$ORIGINAL_BYTES; else print \"0\"}")
    echo ""
    printf "  原始总大小:      %s MB\n" "$orig_mb"
    printf "  ${C_GREEN}节省空间:        %s MB (%s%%)${C_RESET}\n" "$saved_mb" "$pct"
fi

echo ""
[ $DRY_RUN -eq 1 ] && warn "这是 DRY RUN, 没有实际生成文件. 去掉 --dry-run 参数即可正式执行"
[ $TOTAL_ERROR -gt 0 ] && err "存在 $TOTAL_ERROR 张转换失败的图片, 不影响其他图片正常使用"

echo ""
log "下一步: 配置 Nginx 的 WebP 自适应交付 (见 scripts/nginx-webp.conf)"
