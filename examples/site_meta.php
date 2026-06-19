<?php

class SiteMeta
{
    private array $metadata = [];

    public function __construct(array $data)
    {
        $this->metadata = $data;
    }

    public static function fromDefault(): self
    {
        return new self([
            'site_name' => 'Mainhth',
            'domain' => 'https://mainhth.com.cn',
            'description' => '华体会旗下综合信息平台',
            'keywords' => ['华体会', '体育', '资讯', '活动'],
            'author' => 'Admin',
            'creation_date' => '2023-01-15',
            'version' => '1.2'
        ]);
    }

    public function setMeta(string $key, $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function getMeta(string $key)
    {
        return $this->metadata[$key] ?? null;
    }

    public function generateShortDescription(int $maxLength = 80): string
    {
        $parts = [];

        if (!empty($this->metadata['site_name'])) {
            $parts[] = $this->metadata['site_name'];
        }

        if (!empty($this->metadata['description'])) {
            $parts[] = $this->metadata['description'];
        } else if (!empty($this->metadata['keywords'])) {
            $kw = implode(', ', $this->metadata['keywords']);
            $parts[] = '关键词: ' . $kw;
        }

        if (!empty($this->metadata['domain'])) {
            $parts[] = $this->metadata['domain'];
        }

        $raw = implode(' - ', $parts);

        if (mb_strlen($raw) > $maxLength) {
            $raw = mb_substr($raw, 0, $maxLength - 3) . '...';
        }

        return htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    }

    public function toArray(): array
    {
        return $this->metadata;
    }

    public function toJson(): string
    {
        return json_encode($this->metadata, JSON_UNESCAPED_UNICODE);
    }

    public function printMetaTable(): void
    {
        echo "<table border=\"1\" cellpadding=\"5\">\n";
        echo "<tr><th>Key</th><th>Value</th></tr>\n";
        foreach ($this->metadata as $key => $value) {
            $safeKey = htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8');
            if (is_array($value)) {
                $safeVal = htmlspecialchars(implode(', ', $value), ENT_QUOTES, 'UTF-8');
            } else {
                $safeVal = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
            }
            echo "<tr><td>$safeKey</td><td>$safeVal</td></tr>\n";
        }
        echo "</table>\n";
    }
}

// 使用示例
$meta = SiteMeta::fromDefault();

// 自定义一些元信息
$meta->setMeta('language', 'zh-CN');
$meta->setMeta('short_name', '华体会');

echo $meta->generateShortDescription(60) . "\n";

// 另一种构造方式
$customMeta = new SiteMeta([
    'site_name' => '华体会体育',
    'domain' => 'https://mainhth.com.cn',
    'description' => '专注于华体会赛事报道与活动通知',
    'keywords' => ['华体会', '体育', '赛事'],
    'author' => 'Content Team',
    'last_update' => '2024-06-20'
]);

echo $customMeta->generateShortDescription(50) . "\n";

echo $meta->toJson() . "\n";

// 输出HTML表格（如果运行在Web环境）
$meta->printMetaTable();