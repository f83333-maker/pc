<?php
/**
 * 静态 sitemap.xml 生成器（CLI 脚本）
 * ---------------------------------------------------------
 * 为什么是静态文件而不是 PHP 路由？
 *   本项目使用基于 URL 路径段的"约定式路由"（kernel/Kernel.php），
 *   `Route::add()` 不会被触发；同时 .htaccess 在请求文件存在时
 *   会跳过 PHP（!-f 条件），直接由 Apache 返回静态 sitemap.xml，
 *   性能最佳、对搜索引擎最友好。
 *
 * 使用方法（在项目根目录执行）：
 *   php tools/generate-sitemap.php
 *
 * 推荐配置每天定时执行（crontab -e）：
 *   0 4 * * * cd /www/wwwroot/pcccc.cc && /usr/bin/php tools/generate-sitemap.php >/dev/null 2>&1
 *
 * 输出文件：项目根目录下的 sitemap.xml
 */

declare(strict_types=1);

// --------------------------------------------------------------
// 配置区：可按需调整
// --------------------------------------------------------------
const SITE_URL    = 'https://pcccc.cc';   // 站点根 URL（不带末尾斜杠）
const OUTPUT_FILE = __DIR__ . '/../sitemap.xml';
const DB_CONFIG   = __DIR__ . '/../config/database.php';

// --------------------------------------------------------------
// 辅助函数
// --------------------------------------------------------------
function log_msg(string $msg): void
{
    fwrite(STDOUT, '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL);
}

function escape_xml(string $s): string
{
    return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

function build_url_node(string $loc, string $lastmod, string $changefreq, string $priority): string
{
    return "  <url>\n"
        . "    <loc>" . escape_xml($loc) . "</loc>\n"
        . "    <lastmod>{$lastmod}</lastmod>\n"
        . "    <changefreq>{$changefreq}</changefreq>\n"
        . "    <priority>{$priority}</priority>\n"
        . "  </url>\n";
}

// --------------------------------------------------------------
// 主流程
// --------------------------------------------------------------
log_msg('开始生成 sitemap.xml ...');

if (!file_exists(DB_CONFIG)) {
    fwrite(STDERR, "错误：数据库配置文件不存在: " . DB_CONFIG . PHP_EOL);
    exit(1);
}

$db = require DB_CONFIG;
$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $db['host'] ?? '127.0.0.1',
    (int)($db['port'] ?? 3306),
    $db['database'] ?? '',
    $db['charset'] ?? 'utf8mb4'
);

try {
    $pdo = new PDO($dsn, $db['username'] ?? '', $db['password'] ?? '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, '错误：数据库连接失败: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$prefix    = $db['prefix'] ?? '';
$today     = date('Y-m-d');
$baseUrl   = rtrim(SITE_URL, '/');

// 1) 静态高优先级页面
//    本项目控制器在 App\Controller\User\* 命名空间下，
//    框架约定式路由要求带 /user/ 前缀。已通过实测确认：
//    - 首页:        /                          (200, 路由特例)
//    - 订单查询:    /user/index/query          (200)
//    - 商品详情:    /user/index/item?mid={id}  (200)
//    - 分类筛选:    /?cid={id}                  (200, 在首页通过参数过滤)
$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$xml .= build_url_node($baseUrl . '/',                   $today, 'daily',   '1.0');
$xml .= build_url_node($baseUrl . '/user/index/query',   $today, 'monthly', '0.4');

// 2) 上架商品（status = 1 表示启用）
//    mcy-shop 的 commodity 表只有 create_time 字段，没有 update_time
//    （参见 app/Model/Commodity.php 的属性声明）
$itemCount = 0;
try {
    $stmt = $pdo->query(
        "SELECT id, create_time
         FROM `{$prefix}commodity`
         WHERE status = 1
         ORDER BY id ASC"
    );
    while ($row = $stmt->fetch()) {
        // 框架真实路由：/user/index/item?mid=<id>
        $loc     = $baseUrl . '/user/index/item?mid=' . (int)$row['id'];
        $lastmod = !empty($row['create_time'])
            ? date('Y-m-d', is_numeric($row['create_time']) ? (int)$row['create_time'] : strtotime((string)$row['create_time']))
            : $today;
        $xml .= build_url_node($loc, $lastmod, 'weekly', '0.8');
        $itemCount++;
    }
} catch (PDOException $e) {
    log_msg('警告：商品列表查询失败（忽略，继续生成）: ' . $e->getMessage());
}

// 3) 商品分类（首页参数形式 /?cid=<id>）
$categoryCount = 0;
try {
    $stmt = $pdo->query(
        "SELECT id FROM `{$prefix}category` WHERE status = 1 ORDER BY id ASC"
    );
    while ($row = $stmt->fetch()) {
        $loc = $baseUrl . '/?cid=' . (int)$row['id'];
        $xml .= build_url_node($loc, $today, 'weekly', '0.7');
        $categoryCount++;
    }
} catch (PDOException $e) {
    // category 表可能不存在或字段名不同，安静跳过
}

$xml .= '</urlset>' . "\n";

// 写入文件
if (false === file_put_contents(OUTPUT_FILE, $xml)) {
    fwrite(STDERR, '错误：无法写入文件 ' . OUTPUT_FILE . '（请检查目录权限）' . PHP_EOL);
    exit(1);
}

log_msg(sprintf(
    '成功！包含 %d 条 URL（首页 1 + 订单查询 1 + 商品 %d + 分类 %d），输出 %s',
    2 + $itemCount + $categoryCount,
    $itemCount,
    $categoryCount,
    OUTPUT_FILE
));
