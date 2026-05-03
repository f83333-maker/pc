<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Commodity;
use App\Model\Config as ConfigModel;
use Kernel\Annotation\Inject;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;

/**
 * Sitemap 与 Robots 控制器
 *
 * 提供 SEO 用 sitemap.xml（动态拉取上架商品）。
 * robots.txt 已作为静态文件由 Apache 直接返回（位于站点根目录）。
 */
class Sitemap
{
    #[Inject]
    protected Request $request;

    #[Inject]
    protected Response $response;

    /**
     * GET /sitemap.xml
     *
     * 输出最多 5 万条 URL（Sitemap 协议上限），按"首页 + 列表页 + 商品详情页"顺序。
     *
     * @return Response
     */
    public function index(): Response
    {
        $base = $this->getBaseUrl();
        $now  = date('Y-m-d');

        $urls = [];

        // 1. 首页（最高优先级）
        $urls[] = [
            'loc'        => $base . '/',
            'lastmod'    => $now,
            'changefreq' => 'daily',
            'priority'   => '1.0',
        ];

        // 2. 法律条款
        $urls[] = [
            'loc'        => $base . '/register/terms?type=0',
            'lastmod'    => $now,
            'changefreq' => 'monthly',
            'priority'   => '0.3',
        ];
        $urls[] = [
            'loc'        => $base . '/register/terms?type=1',
            'lastmod'    => $now,
            'changefreq' => 'monthly',
            'priority'   => '0.3',
        ];

        // 3. 上架商品详情页（按更新时间倒序，最多 49997 条）
        try {
            $items = Commodity::query()
                ->where('status', 1)
                ->orderByDesc('id')
                ->limit(49997)
                ->get(['id', 'name', 'updated_at']);

            foreach ($items as $item) {
                $lastmod = $item->updated_at
                    ? date('Y-m-d', is_numeric($item->updated_at) ? (int) $item->updated_at : strtotime((string) $item->updated_at))
                    : $now;

                $urls[] = [
                    'loc'        => $base . '/item?mid=' . (int) $item->id,
                    'lastmod'    => $lastmod,
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                ];
            }
        } catch (\Throwable $e) {
            // 数据库异常时仍然输出基础 sitemap，避免 500
        }

        $xml = $this->buildXml($urls);

        return $this->response
            ->withHeader('Content-Type', 'application/xml; charset=utf-8')
            ->withHeader('Cache-Control', 'public, max-age=3600')
            ->raw($xml);
    }

    /**
     * 构造 Sitemap XML
     *
     * @param array<int, array{loc:string,lastmod:string,changefreq:string,priority:string}> $urls
     * @return string
     */
    private function buildXml(array $urls): string
    {
        $lines   = [];
        $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $u) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc>';
            $lines[] = '    <lastmod>' . $u['lastmod'] . '</lastmod>';
            $lines[] = '    <changefreq>' . $u['changefreq'] . '</changefreq>';
            $lines[] = '    <priority>' . $u['priority'] . '</priority>';
            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * 获取规范化的站点根 URL
     *
     * 站点对外只使用 https://pcccc.cc，硬编码以避免：
     *   - sitemap 写入内网 IP（10.x.x.x / 127.0.0.1）
     *   - 反向代理转发的 Host 头被搜索引擎抓到
     *   - 后台未填写 site_url 时使用错误域名
     *
     * 如未来换域名，仅需修改下方常量；同时仍保留 ConfigModel
     * 作为可选覆盖（方便多域名部署场景）。
     *
     * @return string
     */
    private function getBaseUrl(): string
    {
        // 允许后台显式覆盖（仅当配置以 http:// 或 https:// 开头）
        try {
            $url = (string) ConfigModel::get('site_url');
            if (preg_match('/^https?:\/\//i', $url)) {
                return rtrim($url, '/');
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return 'https://pcccc.cc';
    }
}
