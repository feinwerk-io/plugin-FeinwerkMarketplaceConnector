<?php

namespace Piwik\Plugins\FeinwerkMarketplaceConnector\Core\Dto;

/** What the connector last saw on this instance: the Matomo major and the Feinwerk plugins (installed + active). */
final class UpgradeState
{
    /**
     * @param  array<string,string>  $plugins  slug => installed version
     * @param  list<string>  $active
     */
    public function __construct(
        public readonly int $matomoMajor,
        public readonly array $plugins,
        public readonly array $active,
        public readonly string $recordedAt,
    ) {}

    public static function fromArray(mixed $data): ?self
    {
        if (! is_array($data) || ! is_int($data['matomo_major'] ?? null)) {
            return null;
        }
        $plugins = array_filter((array) ($data['plugins'] ?? []), fn ($v, $k) => is_string($k) && is_string($v), ARRAY_FILTER_USE_BOTH);
        $active = array_values(array_filter((array) ($data['active'] ?? []), 'is_string'));

        return new self($data['matomo_major'], $plugins, $active, (string) ($data['recorded_at'] ?? ''));
    }

    /** @return array{matomo_major: int, plugins: array<string,string>, active: list<string>, recorded_at: string} */
    public function toArray(): array
    {
        return ['matomo_major' => $this->matomoMajor, 'plugins' => $this->plugins, 'active' => $this->active, 'recorded_at' => $this->recordedAt];
    }

    public function sameAs(self $other): bool
    {
        $plugins = $this->plugins;
        $otherPlugins = $other->plugins;
        ksort($plugins);
        ksort($otherPlugins);
        $active = $this->active;
        $otherActive = $other->active;
        sort($active);
        sort($otherActive);

        return $this->matomoMajor === $other->matomoMajor && $plugins === $otherPlugins && $active === $otherActive;
    }
}
