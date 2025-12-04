<?php

namespace Otomaties\Core\Modules\HealthTests\Abstracts;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;
use OtomatiesCoreVendor\Illuminate\Support\Str;

abstract class HealthTest
{
    protected ?string $name = null;

    protected string $type = 'direct';

    protected string $category;

    abstract public function passes(): bool;

    abstract public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto;

    abstract public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto;

    public function name(): string
    {
        return $this->name === null ?
            Str::snake(class_basename($this)) :
            Str::snake($this->name);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function category(): string
    {
        if (! isset($this->category)) {
            return HealthCheckCategory::default();
        }

        return $this->category;
    }

    /**
     * Generate response
     *
     * @return array<string, mixed>
     */
    public function respond(): array
    {
        $response = new HealthTestResponseDto(
            test: $this->name(),
            status: 'good',
            label: $this->name(),
            description: '',
            badge: [
                'label' => $this->category(),
                'color' => 'blue',
            ],
        );

        $response = $this->passes() ?
            $this->passedResponse($response) :
            $this->failedResponse($response);

        if ($response->status === 'critical') {
            $response = $this->setBadgeColor('red', $response);
        }

        return $response->toArray();
    }

    public function isActive(): bool
    {
        $constant = 'OTOMATIES_CORE_HEALTH_CHECK_' . mb_strtoupper(Str::snake($this->name())) . '_ACTIVE';
        $constantValue = otomatiesCore()->findVariable($constant);
        if ($constantValue !== null) {
            return filter_var($constantValue, FILTER_VALIDATE_BOOLEAN);
        }

        return true;
    }

    private function setBadgeColor(string $color, HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response->withBadge(
            array_merge($response->badge, ['color' => $color])
        );
    }
}
