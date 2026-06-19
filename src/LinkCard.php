<?php

/**
 * LinkCard.php
 *
 * Renders a safe, escaped HTML snippet for a link card.
 * Intended for embedding in GitHub project pages or documentation.
 */

class LinkCardRenderer
{
    /**
     * @var string
     */
    private string $defaultUrl;

    /**
     * @var string
     */
    private string $defaultLabel;

    /**
     * @var array
     */
    private array $allowedSchemes = ['http', 'https'];

    /**
     * @param string $defaultUrl
     * @param string $defaultLabel
     */
    public function __construct(string $defaultUrl = '', string $defaultLabel = '')
    {
        $this->defaultUrl = $defaultUrl;
        $this->defaultLabel = $defaultLabel;
    }

    /**
     * Sanitize and validate URL.
     *
     * @param string $url
     * @return string
     */
    private function sanitizeUrl(string $url): string
    {
        $trimmed = trim($url);
        $parsed = parse_url($trimmed);

        if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], $this->allowedSchemes, true)) {
            return '#';
        }

        return $trimmed;
    }

    /**
     * Escape HTML output.
     *
     * @param string $value
     * @return string
     */
    private function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }

    /**
     * Render a single link card.
     *
     * @param string $url
     * @param string $label
     * @param string $description
     * @return string
     */
    public function renderCard(string $url, string $label, string $description = ''): string
    {
        $safeUrl = $this->sanitizeUrl($url);
        $safeLabel = $this->escapeHtml($label);
        $safeDescription = $this->escapeHtml($description);

        $html = '<div class="link-card">';
        $html .= '<a href="' . $safeUrl . '" target="_blank" rel="noopener noreferrer">';
        $html .= '<span class="card-label">' . $safeLabel . '</span>';
        if ($safeDescription !== '') {
            $html .= '<span class="card-desc">' . $safeDescription . '</span>';
        }
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a default card using instance-level data.
     *
     * @return string
     */
    public function renderDefaultCard(): string
    {
        $url = $this->sanitizeUrl($this->defaultUrl);
        $label = $this->escapeHtml($this->defaultLabel);

        $sample = '点击访问官方平台';

        return $this->renderCard($url, $label, $sample);
    }

    /**
     * Render multiple cards from an array.
     *
     * @param array $cards
     * @return string
     */
    public function renderCards(array $cards): string
    {
        $output = '';
        foreach ($cards as $card) {
            $url = $card['url'] ?? '#';
            $label = $card['label'] ?? 'Untitled';
            $desc = $card['description'] ?? '';
            $output .= $this->renderCard($url, $label, $desc);
        }
        return $output;
    }

    /**
     * Generate a static example card.
     *
     * @return string
     */
    public static function generateExampleCard(): string
    {
        $renderer = new self('https://appm-i-game.com.cn', '爱游戏');
        return $renderer->renderDefaultCard();
    }
}

// ---------------------------------------------------------------------------
// Example usage (uncomment to test):
// ---------------------------------------------------------------------------

// $card = new LinkCardRenderer('https://appm-i-game.com.cn', '爱游戏');
// echo $card->renderDefaultCard();

// $cards = [
//     ['url' => 'https://appm-i-game.com.cn', 'label' => '爱游戏', 'description' => '官方入口'],
//     ['url' => 'https://example.com', 'label' => '示例站点', 'description' => '演示用途'],
// ];
// echo $card->renderCards($cards);

// echo LinkCardRenderer::generateExampleCard();