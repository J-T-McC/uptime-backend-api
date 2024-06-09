<?php

namespace App\Services\Channels\Discord;

use Carbon\Carbon;

class DiscordMessage
{
    const COLOR_SUCCESS = '0B6623';
    const COLOR_WARNING = 'FD6A02';
    const COLOR_ERROR = 'ED2939';

    protected ?string $title = null;
    protected ?string $description = null;
    protected ?string $timestamp = null;
    protected ?string $footer = null;
    protected ?string $color = null;

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param array<int, string>|string $descriptionLines
     * @return $this
     */
    public function description(array|string $descriptionLines): self
    {
        if (!is_array($descriptionLines)) {
            $descriptionLines = [$descriptionLines];
        }

        $this->description = implode(PHP_EOL, $descriptionLines);

        return $this;
    }

    public function timestamp(Carbon $carbon): self
    {
        $this->timestamp = $carbon->toIso8601String();

        return $this;
    }

    public function footer(string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function success(): self
    {
        $this->color = static::COLOR_SUCCESS;

        return $this;
    }

    public function warning(): self
    {
        $this->color = static::COLOR_WARNING;

        return $this;
    }

    public function error(): self
    {
        $this->color = static::COLOR_ERROR;

        return $this;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function toArray(): array
    {
        return [
            'embeds' => [
                [
                    'title' => $this->title,
                    'type' => 'rich',
                    'description' => $this->description,
                    'color' => hexdec($this->color),
                    'footer' => [
                        'text' => $this->footer ?? '',
                    ],
                    'timestamp' => $this->timestamp,
                ],
            ],
        ];
    }
}
