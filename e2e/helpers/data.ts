/**
 * 测试数据 / 凭据 / 攻击 payload 集中管理
 *
 * 安全约定：仓库不放真实凭据，环境变量 ADMIN_USERNAME / ADMIN_PASSWORD /
 * USER_USERNAME / USER_PASSWORD 优先生效；缺省值为题目中给的沙盒账号。
 */
export const ADMIN = {
  username: process.env.ADMIN_USERNAME || "9@9.9",
  password: process.env.ADMIN_PASSWORD || "Aa199793",
};

export const USER = {
  username: process.env.USER_USERNAME || "222222",
  password: process.env.USER_PASSWORD || "222222",
};

/** 当前测试运行的时间戳，用于生成唯一标识，避免污染 */
export const TS = Date.now();

/** 生成本次运行的随机假用户 */
export const fakeUser = () => {
  const tag = `${TS}_${Math.random().toString(36).slice(2, 6)}`;
  return {
    username: `e2e_${tag}`,
    email: `e2e_${tag}@example.invalid`,
    password: "Aa@" + TS,
  };
};

/** 经典攻击 payload —— 探测 WAF / 注入 / XSS / 越权 */
export const PAYLOADS = {
  xss: `"><script>window.__pwned__=1;</script><img src=x onerror="window.__pwned__=2">`,
  xssEvent: `javascript:alert(1)`,
  sqli: `' OR 1=1 -- -`,
  sqliUnion: `1' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15-- -`,
  sqliBlind: `1' AND SLEEP(5)-- -`,
  longText: "A".repeat(10_000),
  nullByte: "foo\u0000bar",
  cnUtf8: "测试🚀<>&\"'",
  zeroWidth: "ab\u200b\u200ccd",
  pathTraversal: "../../../../etc/passwd",
  rce: `; cat /etc/passwd`,
  ssrf: `http://169.254.169.254/latest/meta-data/`,
};
