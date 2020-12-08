<?php

namespace App\Services\Channels\Discord;

use Carbon\Carbon;

class DiscordMessage
{
    const COLOR_SUCCESS = '0B6623';
    const COLOR_WARNING = 'FD6A02';
    const COLOR_ERROR = 'ED2939';

    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var string */
    protected $timestamp;

    /** @var string */
    protected $footer;

    /** @var string */
    protected $color;

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param array|string $descriptionLines
     *
     * @return \App\Services\Channels\Discord\DiscordMessage
     */
    public function description($descriptionLines): self
    {
        if (! is_array($descriptionLines)) {
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
