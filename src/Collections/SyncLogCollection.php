<?php

namespace Betta\Settings\Collections;

class SyncLogCollection
{
    public array $lost = [];

    public array $synced = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public function addLost(string|array $fqn): static
    {
        if (is_array($fqn)) {
            $this->lost = array_merge($this->lost, $fqn);
        }
        if (is_string($fqn)) {
            $this->lost[] = $fqn;
        }

        return $this;
    }

    public function getLostCount(): int
    {
        return count($this->lost);
    }

    public function addSynced(string|array $fqn): static
    {
        if (is_array($fqn)) {
            $this->synced = array_merge($this->synced, $fqn);
        }
        if (is_string($fqn)) {
            $this->synced[] = $fqn;
        }

        return $this;
    }

    public function getSyncedCount(): int
    {
        return count($this->synced);
    }

    public function getMessage(): string
    {
        return implode(', ', array_filter([
            $this->getSynchronizedMessage(),
            $this->getLostMessage(),
        ]));
    }

    public function getLostMessage(): ?string
    {
        $lost = $this->getLostCount();

        return match ($lost) {
            0 => null,
            1 => __('fqn-settings::message.OneSettingSynchronized'),
            default => __('fqn-settings::message.SettingsLost', ['count' => $lost]),
        };
    }

    public function getSynchronizedMessage(): string
    {
        $synced = $this->getSyncedCount();

        return match ($synced) {
            0 => __('fqn-settings::message.NoSettingSynchronized'),
            1 => __('fqn-settings::message.OneSettingSynchronized'),
            default => __('fqn-settings::message.SettingSynchronized', ['count' => $synced]),
        };
    }
}
