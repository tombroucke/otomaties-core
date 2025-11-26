<?php

namespace Otomaties\Core\Illuminate\Support;

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
        $this->providers = $providers ?: [\Otomaties\Core\Illuminate\Auth\AuthServiceProvider::class, \Otomaties\Core\Illuminate\Broadcasting\BroadcastServiceProvider::class, \Otomaties\Core\Illuminate\Bus\BusServiceProvider::class, \Otomaties\Core\Illuminate\Cache\CacheServiceProvider::class, \Otomaties\Core\Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class, \Otomaties\Core\Illuminate\Concurrency\ConcurrencyServiceProvider::class, \Otomaties\Core\Illuminate\Cookie\CookieServiceProvider::class, \Otomaties\Core\Illuminate\Database\DatabaseServiceProvider::class, \Otomaties\Core\Illuminate\Encryption\EncryptionServiceProvider::class, \Otomaties\Core\Illuminate\Filesystem\FilesystemServiceProvider::class, \Otomaties\Core\Illuminate\Foundation\Providers\FoundationServiceProvider::class, \Otomaties\Core\Illuminate\Hashing\HashServiceProvider::class, \Otomaties\Core\Illuminate\Mail\MailServiceProvider::class, \Otomaties\Core\Illuminate\Notifications\NotificationServiceProvider::class, \Otomaties\Core\Illuminate\Pagination\PaginationServiceProvider::class, \Otomaties\Core\Illuminate\Auth\Passwords\PasswordResetServiceProvider::class, \Otomaties\Core\Illuminate\Pipeline\PipelineServiceProvider::class, \Otomaties\Core\Illuminate\Queue\QueueServiceProvider::class, \Otomaties\Core\Illuminate\Redis\RedisServiceProvider::class, \Otomaties\Core\Illuminate\Session\SessionServiceProvider::class, \Otomaties\Core\Illuminate\Translation\TranslationServiceProvider::class, \Otomaties\Core\Illuminate\Validation\ValidationServiceProvider::class, \Otomaties\Core\Illuminate\View\ViewServiceProvider::class];
    }
    /**
     * Merge the given providers into the provider collection.
     *
     * @param  array  $providers
     * @return static
     */
    public function merge(array $providers)
    {
        $this->providers = array_merge($this->providers, $providers);
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
            $current = is_int($key) ? $current->replace([$key => $to]) : $current;
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
        return new static((new Collection($this->providers))->reject(fn($p) => in_array($p, $providers))->values()->toArray());
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
