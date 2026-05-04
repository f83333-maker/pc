<?php

declare(strict_types=1);

const SITE_URL    = 'https://pcccc.cc';   
const OUTPUT_FILE = __DIR__ . '/../sitemap.xml';
const DB_CONFIG   = __DIR__ . '/../config/database.php';

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

$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$xml .= build_url_node($baseUrl . '/',                   $today, 'daily',   '1.0');
// 订单查询页（订单可被搜索的入口）
$xml .= build_url_node($baseUrl . '/user/index/query',   $today, 'monthly', '0.4');

$itemCount = 0;
try {

    $stmt = $pdo->query(
        "SELECT id, create_time
         FROM `{$prefix}commodity`
         WHERE status = 1
           AND (hide IS NULL OR hide = 0)
           AND (draft_status IS NULL OR draft_status = 0)
         ORDER BY sort ASC, id ASC"
    );
    while ($row = $stmt->fetch()) {

        // 与 Header.html 中的 canonical 保持一致，使用短链 /item?mid=
        $loc     = $baseUrl . '/item?mid=' . (int)$row['id'];
        $lastmod = !empty($row['create_time'])
            ? date('Y-m-d', is_numeric($row['create_time']) ? (int)$row['create_time'] : strtotime((string)$row['create_time']))
            : $today;
        $xml .= build_url_node($loc, $lastmod, 'weekly', '0.8');
        $itemCount++;
    }
} catch (PDOException $e) {
    log_msg('警告：商品列表查询失败（忽略，继续生成）: ' . $e->getMessage());
}

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

}

$xml .= '</urlset>' . "\n";

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
