<?php

namespace Otomaties\Core\Modules\HealthTests\Abstracts;

use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;
use OtomatiesCoreVendor\Illuminate\Support\Str;

abstract class HealthTest
{
    protected ?string $name = null;

    protected string $type = 'direct';

    protected array $defaultResponse = [];

    protected string $category;

    abstract public function passes(): bool;

    abstract public function passedResponse(): array;

    abstract public function failedResponse(): array;

    public function __construct()
    {
        $this->defaultResponse = [
            'status' => 'good',
            'badge' => [
                'label' => $this->category(),
                'color' => 'blue',
            ],
            'test' => $this->name(),
        ];
    }

    public function name(): string
    {
        if ($this->name === null) {
            return Str::snake(\OtomatiesCoreVendor\class_basename($this));
        }

        return Str::snake($this->name);
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

    public function respond(): array
    {
        $passes = $this->passes();
        if (! $passes) {
            $response = $this->failedResponse();
            if ($response['status'] === 'good') {
                if ($this->category() === 'Security') {
                    $response['status'] = 'critical';
                } else {
                    $response['status'] = 'recommended';
                }
            }
            if ($response['status'] === 'critical') {
                $response['badge']['color'] = 'red';
            }
        } else {
            $response = $this->passedResponse();
        }

        return $response;
    }

    public function active(): bool
    {
        $constant = 'OTOMATIES_HEALTH_CHECK_' . strtoupper(Str::snake($this->name())) . '_ACTIVE';
        $constantValue = $this->findVariable($constant);
        if ($constantValue !== null) {
            return filter_var($constantValue, FILTER_VALIDATE_BOOLEAN);
        }

        return true;
    }

    protected function findVariable(string $variableName): ?string
    {
        if (defined($variableName)) {
            return constant($variableName);
        }
        if (isset($_SERVER[$variableName])) {
            return $_SERVER[$variableName];
        }
        if (isset($_ENV[$variableName])) {
            return $_ENV[$variableName];
        }

        return null;
    }
}
