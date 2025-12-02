<?php

declare(strict_types=1);

namespace Otomaties\Core\Modules\HealthTests\Dtos;

final class HealthTestResponseDto
{
    public function __construct(
        public string $test,
        public string $status,
        public string $label,
        public string $description,
        public ?array $badge = null,
        public ?string $actions = null,
    ) {
        //
    }

    public function withStatus(string $status): self
    {
        return $this->with(status: $status);
    }

    public function withLabel(string $label): self
    {
        return $this->with(label: $label);
    }

    public function withDescription(string $description): self
    {
        return $this->with(description: $description);
    }

    public function withBadge(array $badge): self
    {
        return $this->with(badge: $badge);
    }

    public function withActions(string $actions): self
    {
        return $this->with(actions: $actions);
    }

    public function toArray(): array
    {
        return array_filter([
            'test' => $this->test,
            'status' => $this->status,
            'label' => $this->label,
            'description' => $this->description,
            'badge' => $this->badge,
            'actions' => $this->actions,
        ]);
    }

    private function with(
        ?string $test = null,
        ?string $status = null,
        ?string $label = null,
        ?string $description = null,
        ?array $badge = null,
        ?string $actions = null,
    ): self {
        return new self(
            test: $test ?? $this->test,
            status: $status ?? $this->status,
            label: $label ?? $this->label,
            description: $description ?? $this->description,
            badge: $badge ?? $this->badge,
            actions: $actions ?? $this->actions,
        );
    }
}
