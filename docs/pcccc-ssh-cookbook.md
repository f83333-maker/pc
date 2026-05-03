# pcccc.cc 性能优化全流程 SSH 命令清单

> 整理说明：本清单按本次优化的真实执行顺序整理。每个步骤分为"主命令 → 备用/排错命令"两层。
> 所有命令都假设你以 root 身份操作，网站根目录是 /www/wwwroot/pcccc.cc。

---

## 阶段 1：服务器与 GitHub 建立 SSH 连接

### 1.1 进入网站目录

主命令：

    cd /www/wwwroot/pcccc.cc

用途：切换到网站根目录。后续大部分操作都要在这个目录下执行。
解释：cd 是 change directory 的缩写。/www/wwwroot/ 是宝塔面板默认存放网站文件的目录。

---

### 1.2 确认目录正确

主命令：

    ls -la

用途：列出当前目录所有文件（包含隐藏文件如 .htaccess、.git）。
解释：ls 是 list（列出），-l 是 long format（详细信息），-a 是 all（包括 . 开头的隐藏文件）。
看到 index.php、app/、assets/ 就证明在对的网站目录。

---

### 1.3 备份网站（保命操作）

主命令：

    cd /www/wwwroot
    tar -czf pcccc.cc.backup.$(date +%Y%m%d).tar.gz pcccc.cc

用途：把整个网站打包成 tar.gz 压缩文件，文件名带当天日期。
解释：tar 是 tape archive（归档工具），-c 创建归档、-z 用 gzip 压缩、-f 指定文件名。
$(date +%Y%m%d) 是命令替换，会自动替换成今天的日期（如 20260503）。

---

### 1.4 生成 SSH 密钥对

主命令：

    ssh-keygen -t ed25519 -C "pcccc-server" -f ~/.ssh/id_ed25519 -N ""

用途：在服务器上生成一对 SSH 密钥，用于跟 GitHub 认证。
解释：
- ssh-keygen 是密钥生成工具
- -t ed25519 用 ed25519 算法（比传统 RSA 更安全更快）
- -C "pcccc-server" 加注释方便识别
- -f ~/.ssh/id_ed25519 保存路径
- -N "" 不设密码（自动化拉代码用）

---

### 1.5 查看公钥内容

主命令：

    cat ~/.ssh/id_ed25519.pub

用途：显示公钥内容，复制后粘贴到 GitHub Settings → SSH Keys。
解释：cat 是 concatenate（连接显示），用来打印文本文件内容。
.pub 是公钥（public key），可以分享出去；不带 .pub 的是私钥，绝对不能给任何人。

---

### 1.6 测试 GitHub 连通性

主命令：

    ssh -T git@github.com

用途：测试 SSH 密钥是否成功关联到 GitHub。
解释：-T 表示不分配伪终端（GitHub 不提供 shell 访问，只提供 git 协议）。
第一次会问 yes/no 输入 yes。看到 "Hi 用户名! You've successfully authenticated" 就成功。

---

## 阶段 2：把现有目录变成 Git 仓库

### 2.1 初始化 Git 仓库

主命令：

    cd /www/wwwroot/pcccc.cc
    git init

用途：在当前目录创建 .git/ 隐藏目录，把这个目录变成 git 仓库。
解释：git init 是 git 初始化命令，执行后才能用 git 的所有功能。

---

### 2.2 关联到 GitHub 远程仓库

主命令：

    git remote add origin git@github.com:f83333-maker/pc.git
    git remote -v

用途：第一行：告诉本地 git 远程仓库地址（取个别名叫 origin）；第二行：验证关联成功。
解释：remote add 是添加远程关联，-v 是 verbose（详细模式）会同时显示 fetch 和 push 两条记录。

备用（如果关联失败/重复）：

    git remote remove origin 2>/dev/null
    git remote add origin git@github.com:f83333-maker/pc.git

用途：先删除可能已存在的旧关联（2>/dev/null 把"不存在"的报错丢弃），再重新关联。

---

### 2.3 解决 dubious ownership 报错

主命令：

    git config --global --add safe.directory /www/wwwroot/pcccc.cc

用途：把网站目录加进 git 的"安全目录白名单"，解决 root 操作 www 用户目录被拒绝的问题。
解释：现代 Git 出于安全考虑，发现仓库所有者和当前用户不一致时会拒绝执行，这条命令就是显式信任。

备用（如果还有别的目录也报错）：

    git config --global --add safe.directory '*'

用途：信任所有目录（图省事）。
注意：单引号必须有，否则 shell 会把 * 展开成当前目录所有文件名。

---

### 2.4 抓取远程 main 分支

主命令：

    git fetch origin main
    git reset --mixed origin/main

用途：第一行：从远程拉取 main 分支元数据但不动工作区文件；
第二行：把本地的 git 索引指针对齐到 main，但保留你服务器上的所有现有文件。
解释：--mixed 是 reset 的默认模式，"重置 index 但不动 working tree"。
这一步是反向关联的核心——既不丢失服务器现有文件，又能让 git 知道哪些文件和远程一致。

---

### 2.5 处理服务器本地特有文件

主命令：

    cat > .git/info/exclude << 'EOF'
    /runtime/
    /data/
    /storage/
    /temp/
    /logs/
    /cache/
    /uploads/
    /assets/cache/
    *.log
    .user.ini
    EOF

用途：创建本地排除清单，把运行时产生的文件不纳入 git 跟踪。
解释：
- cat > 文件 << 'EOF' 是 heredoc 语法，把后面到 EOF 之间的内容写进文件
- 'EOF' 加单引号表示不展开变量（避免 $xxx 被替换）
- .git/info/exclude 只在当前服务器生效，不会被推送到 GitHub
- 比 .gitignore 更适合放服务器特定的排除项

---

## 阶段 3：日常拉取代码（最常用）

### 3.1 标准拉取流程

主命令：

    cd /www/wwwroot/pcccc.cc
    git pull origin main

用途：把 GitHub 上 main 分支的最新代码同步到服务器。
解释：git pull 等价于 git fetch + git merge，一步到位。

---

### 3.2 报 "Your local changes would be overwritten"

备用方案 A - 暂存本地修改：

    git stash push -m "auto-server-changes"
    git pull origin main
    git stash drop

用途：
- 第一行：把本地未提交的修改临时收起来（叫 auto-server-changes 方便识别）
- 第二行：拉远程代码
- 第三行：丢弃刚才暂存的修改（适合本地差异是垃圾、远程版才是正确的情况）

解释：stash 是 Git 的"暂存柜"，可以保存多份。
pop 是取出并删除，drop 是直接删除不取出，apply 是取出但保留。

备用方案 B - 强制覆盖指定文件：

    git checkout HEAD -- 文件1 文件2 文件3
    git pull origin main

用途：只针对特定文件强制采用远程版本，其它文件不动。比 stash 更精准。
解释：git checkout HEAD -- 文件 是"用最后一次 commit 的版本覆盖当前文件"的快捷写法。

---

### 3.3 报 fatal: 'origin' does not appear to be a git repository

备用方案：

    git remote -v
    git remote remove origin 2>/dev/null
    git remote add origin git@github.com:f83333-maker/pc.git
    git fetch origin main

用途：重新建立远程关联。

---

### 3.4 强制重置工作区文件（git pull 说"already up to date"但文件没更新时）

备用方案：

    git checkout origin/main -- 文件路径1 文件路径2

用途：强制把工作区文件重置为远程版本（常用于 git reset --mixed 后工作区没真正更新的场景）。
解释：这个不会动其它文件，只针对你点名的几个文件。

---

## 阶段 4：诊断网站状态

### 4.1 查看网站文件状态

主命令：

    head -5 .htaccess

用途：看文件前 5 行，验证某个配置文件是否真的更新了。
解释：head 是看头部，-5 是行数。同类还有 tail -5 看尾部 5 行。

---

### 4.2 判断 Web 服务器类型

主命令：

    ps aux | grep -E "nginx|apache|httpd" | grep -v grep | head -5

用途：看服务器跑的是 Nginx 还是 Apache。
解释：
- ps aux 列出所有进程
- | 是管道，把前面输出当作后面的输入
- grep -E "nginx|apache|httpd" 用扩展正则筛选包含这些关键词的行
- grep -v grep 反向过滤：去掉 grep 进程自己（-v 是 invert）
- head -5 只看前 5 行

---

### 4.3 看实时错误日志

主命令：

    tail -50 /www/wwwlogs/pcccc.cc.error.log

用途：查看 Nginx/Apache 错误日志最后 50 行，定位 500、404 等问题。
解释：宝塔默认日志路径是 /www/wwwlogs/<域名>.error.log。

备用 - 看 PHP 应用层日志：

    tail -50 /www/wwwroot/pcccc.cc/runtime/log/$(date +%Y%m%d).log

用途：看 PHP 框架的当天日志（很多框架按日期切分文件）。

备用 - 看 PHP-FPM 错误：

    tail -30 /www/server/php/74/var/log/php-fpm.log

用途：看 PHP-FPM 自身的错误。
注意：74 替换成你自己的 PHP 版本号（比如 PHP 8.0 就是 80）。

---

### 4.4 实时跟踪日志（出问题时挂着看）

备用：

    tail -f /www/wwwlogs/pcccc.cc.error.log

用途：实时刷出新的日志行（操作页面时同步看错误）。按 Ctrl+C 停止。
解释：-f 是 follow（跟随），文件有新内容会自动显示。

---

### 4.5 修复 PHP 框架运行时缓存损坏（首页 404 案例）

主命令：

    cd /www/wwwroot/pcccc.cc
    ls -la runtime/
    rm -rf runtime/cache runtime/temp runtime/log runtime/session
    mkdir -p runtime
    chown -R www:www runtime
    chmod -R 755 runtime

用途：
- 第一行：先看一下 runtime 里有什么子目录（防止误删）
- 第二行：删除可能损坏的缓存子目录（不要用 runtime/*，会留下空目录干扰判断）
- 第三、四、五行：重建目录、把所有权改回 www 用户、设置正确权限

解释：
- chown -R www:www 把目录所有者改成 www 用户和 www 用户组（宝塔默认 PHP 运行用户）
- chmod -R 755 目录 给目录设置权限：所有者 7（读/写/执行），组用户 5（读/执行），其他用户 5（读/执行）
- -R 都是 recursive（递归处理子目录）

---

### 4.6 重启 PHP

备用：

    ls /etc/init.d/ | grep php
    /etc/init.d/php-fpm-74 restart

用途：
- 第一行：先看 PHP 服务的实际名字
- 第二行：根据看到的名字重启（数字替换成你的 PHP 版本）

---

## 阶段 5：测试与验证

### 5.1 测试 HTTP 响应头

主命令：

    curl -sI https://pcccc.cc/

用途：只获取响应头不要响应体，看状态码和缓存策略。
解释：
- -s 是 silent（不显示进度条）
- -I 只发 HEAD 请求

带 User-Agent 模拟浏览器：

    curl -sI https://pcccc.cc/ -A "Mozilla/5.0"

用途：-A 设置 User-Agent，避免某些站对 curl 默认 UA 特殊处理。

---

### 5.2 测试响应头并筛选关键字段

主命令：

    curl -sI https://pcccc.cc/assets/common/css/_.css | grep -iE "cache-control|content-encoding|expires"

用途：只看缓存控制、压缩方式、过期时间这几个关键头。
解释：grep -i 是 ignore case（忽略大小写），-E 是扩展正则。

---

### 5.3 测试 Gzip 是否启用

主命令：

    curl -sI -H "Accept-Encoding: gzip, br" https://pcccc.cc/assets/common/css/_.css | grep -i "content-encoding"

用途：主动声明浏览器支持 gzip 和 brotli 压缩，看服务器返回了什么编码。
解释：-H 是添加自定义请求头。看到 content-encoding: gzip 或 br 就证明启用了。

---

### 5.4 测试加载耗时

主命令：

    curl -sL https://pcccc.cc/ -o /dev/null -w "DNS=%{time_namelookup}s 连接=%{time_connect}s TLS=%{time_appconnect}s 首字节=%{time_starttransfer}s 总耗时=%{time_total}s\n"

用途：量化每个阶段的耗时。
解释：
- -L 跟随重定向
- -o /dev/null 把响应体丢弃（只关心耗时）
- -w 是 write-out 格式化输出，%{xxx} 是 curl 内置变量

---

### 5.5 测试 WebP 自适应交付

主命令：

    curl -sI -H "Accept: image/webp,image/*" "https://pcccc.cc/某图.png" | grep -iE "content-type|content-length|vary"

用途：模拟支持 WebP 的现代浏览器，验证服务器是否自动吐 .webp。
解释：Accept 头告诉服务器"我能解码哪些格式"。

对比测试老浏览器：

    curl -sI -H "Accept: image/png,image/jpeg,*/*" "https://pcccc.cc/某图.png" | grep -iE "content-type|content-length"

用途：模拟老浏览器，应该拿到原 PNG。

带 cache-buster 避免拿到旧缓存：

    curl -sI "https://pcccc.cc/某图.png?_=$(date +%s%N)" | grep -i content-type

用途：?_=纳秒时间戳 让 URL 唯一化，绕过 Cloudflare 缓存。

---

### 5.6 批量找出已转换的 WebP 文件

主命令：

    ls -R /www/wwwroot/pcccc.cc/assets 2>/dev/null | grep "\.webp$" | head -5

用途：在 assets 目录递归查找所有 .webp 文件，只看前 5 条。
解释：
- ls -R 是 recursive 递归列出
- 2>/dev/null 把错误输出（如权限拒绝）丢弃
- \.webp$ 正则：以 .webp 结尾，\. 是转义点号，$ 是行尾

---

### 5.7 看图片在仓库中的分布

主命令：

    find . -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) 2>/dev/null \
      | grep -v "\.git/" | grep -v "vendor/" \
      | sed 's|/[^/]*$||' | sort | uniq -c | sort -rn | head -20

用途：统计每个目录下图片数量，按多到少排序。
解释：
- find 找文件
- -type f 只要文件不要目录
- \(...\) 用括号组合多条件（\ 是为了让 shell 别解析括号）
- -iname 不区分大小写匹配名字
- -o 是 or
- sed 's|/[^/]*$||' 用 sed 把每行最后的"/文件名"删掉，只留目录路径
- sort | uniq -c 排序后统计每行重复次数
- sort -rn 按数字逆序排列（多的在前）

---

## 阶段 6：dpkg 损坏修复（apt 安装失败时）

### 6.1 修复 dpkg 中断状态

主命令：

    dpkg --configure -a
    apt-get install -f -y
    apt-get update

用途：
- 第一行：完成所有未完成的包配置
- 第二行：自动修复损坏的依赖
- 第三行：刷新软件源列表

解释：apt-get install -f -y 中 -f 是 fix-broken，-y 是自动 yes 不询问。

---

### 6.2 彻底清理 apt 缓存（修复后还失败时）

备用：

    apt-get clean
    apt-get autoclean
    rm -rf /var/lib/apt/lists/*
    apt-get update

用途：把 apt 下载的旧包全部清掉重新拉。

---

### 6.3 切换到国内镜像源（apt 下载慢时）

备用：

    sed -i 's|//.*archive.ubuntu.com|//mirrors.aliyun.com|g' /etc/apt/sources.list
    sed -i 's|//.*security.ubuntu.com|//mirrors.aliyun.com|g' /etc/apt/sources.list
    apt-get update

用途：把官方源批量替换成阿里云镜像。
解释：sed -i 's|A|B|g' 文件 是"原地替换文件中的 A 为 B"，
g 是 global（一行内所有匹配都换），| 是分隔符（避免和 URL 里的 / 冲突）。

---

## 阶段 7：图片批量转 WebP

### 7.1 安装 cwebp 工具

主命令：

    apt-get install -y webp
    cwebp -version

用途：第一行装 webp 工具包；第二行验证安装成功。
解释：Ubuntu/Debian 系软件包名就叫 webp，包含 cwebp、dwebp、gif2webp 等命令。

---

### 7.2 dry-run 预览（不实际转）

主命令：

    cd /www/wwwroot/pcccc.cc
    bash scripts/convert-images-to-webp.sh --target=assets --dry-run

用途：只扫描不转换，看会处理多少张图。
解释：
- --target=assets 指定扫描 assets/ 目录（脚本自定义参数）
- --dry-run 是行业惯例参数名，意思是"演练但不真做"

---

### 7.3 实际执行批量转换

主命令：

    bash scripts/convert-images-to-webp.sh --target=assets

用途：真正执行转换。脚本会同目录生成 .webp，原文件 100% 保留。

---

### 7.4 校验脚本语法（写脚本后必做）

备用：

    bash -n scripts/convert-images-to-webp.sh

用途：只做语法检查，不真执行（适合检查 if/for/case 是否闭合）。
解释：-n 是 noexec（不执行）。

---

### 7.5 给脚本加可执行权限

备用：

    chmod +x scripts/convert-images-to-webp.sh

用途：给脚本加 execute 权限，可以直接 ./scripts/xxx.sh 跑（不用 bash 前缀）。

---

## 阶段 8：Nginx 操作

### 8.1 测试配置语法（改配置后必做）

主命令：

    nginx -t

用途：检查所有 Nginx 配置文件语法，不会真正应用。
解释：-t 是 test。看到 syntax is ok 和 test is successful 就安全。

---

### 8.2 应用配置（不重启）

主命令：

    nginx -s reload

用途：让 Nginx 重新加载配置文件，不中断现有连接。
解释：-s 是 send signal（发信号），reload 是重载信号。
生产环境改配置首选这条，比 restart 优雅得多。

---

### 8.3 查看进程状态

备用：

    ps aux | grep nginx | grep -v grep

用途：看 Nginx 主进程和 worker 进程是否都在跑。

---

## 阶段 9：Git 实用工具

### 9.1 看最近 N 次提交

主命令：

    git log --oneline -10

用途：单行格式显示最近 10 次提交。
解释：--oneline 让每个 commit 只显示一行（hash + 简短说明）。

---

### 9.2 看当前分支

主命令：

    git branch --show-current

用途：只显示当前所在分支名。

---

### 9.3 看工作区状态

主命令：

    git status

用途：列出修改、暂存、未跟踪的所有文件。
解释：这是 git 最重要的诊断命令，遇到问题先跑这个。

---

### 9.4 临时切换到旧版本（紧急回滚）

备用：

    git log --oneline -10
    git checkout <hash>
    git checkout main

用途：紧急情况下快速验证某个旧版本能否解决问题。
解释：这种"游离 HEAD" 状态不会污染分支，安全。

---

## 阶段 10：定时调度（自动化部署）

### 10.1 设置定时拉取（可选）

备用：

    crontab -e

用途：编辑当前用户的定时任务。在弹出的编辑器里加：

    */30 * * * * cd /www/wwwroot/pcccc.cc && git pull origin main >/dev/null 2>&1

解释：每 30 分钟自动 git pull 一次。
- */30 * * * * 是 cron 时间表达式：每 30 分钟、每小时、每天、每月、每周几
- >/dev/null 2>&1 把标准输出和标准错误都丢弃（不发邮件）

---

## 通用排错心法

### 报错时按这个顺序查

    # 1. 看 Nginx 错误日志（最常见）
    tail -50 /www/wwwlogs/<域名>.error.log

    # 2. 看 PHP 应用日志
    tail -50 /www/wwwroot/<网站>/runtime/log/$(date +%Y%m%d).log

    # 3. 看 Nginx 配置语法
    nginx -t

    # 4. 看进程是否还活着
    ps aux | grep -E "nginx|php" | grep -v grep

    # 5. 看磁盘是否满了（写不进文件会引发奇怪错误）
    df -h

    # 6. 看权限是否正确
    ls -la /www/wwwroot/<网站>/runtime/

---

### 命令的 5 个万能小工具（每条命令都用得上）

| 工具         | 含义                                          | 例子                              |
|--------------|-----------------------------------------------|-----------------------------------|
| \|           | 管道，把前面输出给后面命令处理                | ls \| grep .php                   |
| > >>         | 重定向输出，> 覆盖、>> 追加                   | ls > files.txt                    |
| 2>/dev/null  | 把错误信息丢弃                                | find / -name "*.php" 2>/dev/null  |
| $(命令)      | 命令替换，把命令的输出嵌入                    | echo "今天 $(date)"               |
| && \|\|      | 条件执行，&& 前面成功才跑后面，\|\| 反之       | cd 目录 && ls                     |

---

## 写给你的话

这份清单按"主使用 → 备用排错"的顺序整理，覆盖了我们这次优化的全过程。
建议存成 Markdown 文件 pcccc-ssh-cookbook.md，以后遇到问题先翻这份。

实战中你不需要记所有命令，只要记住三件事：

1. 改任何东西之前先备份（tar -czf 备份.tar.gz 目录）
2. 改 Nginx 配置后先 nginx -t 验证再应用
3. 出问题第一时间看错误日志（tail -50 /www/wwwlogs/xxx.error.log），日志会告诉你 90% 的答案

下次遇到类似的工程，对照这份清单走一遍，你自己也能搞定。
