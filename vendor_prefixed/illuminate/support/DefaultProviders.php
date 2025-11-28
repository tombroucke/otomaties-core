<?php

namespace OtomatiesCoreVendor\Illuminate\Support;

/** @internal */
class DefaultProviders
{
    /**
     * The current providers.
     *
     * @var array
     */
    protected $providers;
    /**
     * Create a new default provider collection.
     */
    public function __construct(?array $providers = null)
    {
        $this->providers = $providers ?: [\OtomatiesCoreVendor\Illuminate\Auth\AuthServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Broadcasting\BroadcastServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Bus\BusServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Cache\CacheServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Concurrency\ConcurrencyServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Cookie\CookieServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Database\DatabaseServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Encryption\EncryptionServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Filesystem\FilesystemServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Foundation\Providers\FoundationServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Hashing\HashServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Mail\MailServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Notifications\NotificationServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Pagination\PaginationServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Auth\Passwords\PasswordResetServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Pipeline\PipelineServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Queue\QueueServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Redis\RedisServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Session\SessionServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Translation\TranslationServiceProvider::class, \OtomatiesCoreVendor\Illuminate\Validation\ValidationServiceProvider::class, \OtomatiesCoreVendor\Illuminate\View\ViewServiceProvider::class];
    }
    /**
     * Merge the given providers into the provider collection.
     *
     * @param  array  $providers
     * @return static
     */
    public function merge(array $providers)
    {
        $this->providers = \array_merge($this->providers, $providers);
        return new static($this->providers);
    }
    /**
     * Replace the given providers with other providers.
     *
     * @param  array  $replacements
     * @return static
     */
    public function replace(array $replacements)
    {
        $current = new Collection($this->providers);
        foreach ($replacements as $from => $to) {
            $key = $current->search($from);
            $current = \is_int($key) ? $current->replace([$key => $to]) : $current;
        }
        return new static($current->values()->toArray());
    }
    /**
     * Disable the given providers.
     *
     * @param  array  $providers
     * @return static
     */
    public function except(array $providers)
    {
        return new static((new Collection($this->providers))->reject(fn($p) => \in_array($p, $providers))->values()->toArray());
    }
    /**
     * Convert the provider collection to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->providers;
    }
}
