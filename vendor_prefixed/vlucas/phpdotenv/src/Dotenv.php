<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Dotenv;

use OtomatiesCoreVendor\Dotenv\Exception\InvalidPathException;
use OtomatiesCoreVendor\Dotenv\Loader\Loader;
use OtomatiesCoreVendor\Dotenv\Loader\LoaderInterface;
use OtomatiesCoreVendor\Dotenv\Parser\Parser;
use OtomatiesCoreVendor\Dotenv\Parser\ParserInterface;
use OtomatiesCoreVendor\Dotenv\Repository\Adapter\ArrayAdapter;
use OtomatiesCoreVendor\Dotenv\Repository\Adapter\PutenvAdapter;
use OtomatiesCoreVendor\Dotenv\Repository\RepositoryBuilder;
use OtomatiesCoreVendor\Dotenv\Repository\RepositoryInterface;
use OtomatiesCoreVendor\Dotenv\Store\StoreBuilder;
use OtomatiesCoreVendor\Dotenv\Store\StoreInterface;
use OtomatiesCoreVendor\Dotenv\Store\StringStore;
/** @internal */
class Dotenv
{
    /**
     * The store instance.
     *
     * @var \Dotenv\Store\StoreInterface
     */
    private $store;
    /**
     * The parser instance.
     *
     * @var \Dotenv\Parser\ParserInterface
     */
    private $parser;
    /**
     * The loader instance.
     *
     * @var \Dotenv\Loader\LoaderInterface
     */
    private $loader;
    /**
     * The repository instance.
     *
     * @var \Dotenv\Repository\RepositoryInterface
     */
    private $repository;
    /**
     * Create a new dotenv instance.
     *
     * @param \Dotenv\Store\StoreInterface           $store
     * @param \Dotenv\Parser\ParserInterface         $parser
     * @param \Dotenv\Loader\LoaderInterface         $loader
     * @param \Dotenv\Repository\RepositoryInterface $repository
     *
     * @return void
     */
    public function __construct(StoreInterface $store, ParserInterface $parser, LoaderInterface $loader, RepositoryInterface $repository)
    {
        $this->store = $store;
        $this->parser = $parser;
        $this->loader = $loader;
        $this->repository = $repository;
    }
    /**
     * Create a new dotenv instance.
     *
     * @param \Dotenv\Repository\RepositoryInterface $repository
     * @param string|string[]                        $paths
     * @param string|string[]|null                   $names
     * @param bool                                   $shortCircuit
     * @param string|null                            $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function create(RepositoryInterface $repository, $paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $builder = $names === null ? StoreBuilder::createWithDefaultName() : StoreBuilder::createWithNoNames();
        foreach ((array) $paths as $path) {
            $builder = $builder->addPath($path);
        }
        foreach ((array) $names as $name) {
            $builder = $builder->addName($name);
        }
        if ($shortCircuit) {
            $builder = $builder->shortCircuit();
        }
        return new self($builder->fileEncoding($fileEncoding)->make(), new Parser(), new Loader(), $repository);
    }
    /**
     * Create a new mutable dotenv instance with default repository.
     *
     * @param string|string[]      $paths
     * @param string|string[]|null $names
     * @param bool                 $shortCircuit
     * @param string|null          $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function createMutable($paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $repository = RepositoryBuilder::createWithDefaultAdapters()->make();
        return self::create($repository, $paths, $names, $shortCircuit, $fileEncoding);
    }
    /**
     * Create a new mutable dotenv instance with default repository with the putenv adapter.
     *
     * @param string|string[]      $paths
     * @param string|string[]|null $names
     * @param bool                 $shortCircuit
     * @param string|null          $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function createUnsafeMutable($paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $repository = RepositoryBuilder::createWithDefaultAdapters()->addAdapter(PutenvAdapter::class)->make();
        return self::create($repository, $paths, $names, $shortCircuit, $fileEncoding);
    }
    /**
     * Create a new immutable dotenv instance with default repository.
     *
     * @param string|string[]      $paths
     * @param string|string[]|null $names
     * @param bool                 $shortCircuit
     * @param string|null          $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function createImmutable($paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $repository = RepositoryBuilder::createWithDefaultAdapters()->immutable()->make();
        return self::create($repository, $paths, $names, $shortCircuit, $fileEncoding);
    }
    /**
     * Create a new immutable dotenv instance with default repository with the putenv adapter.
     *
     * @param string|string[]      $paths
     * @param string|string[]|null $names
     * @param bool                 $shortCircuit
     * @param string|null          $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function createUnsafeImmutable($paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $repository = RepositoryBuilder::createWithDefaultAdapters()->addAdapter(PutenvAdapter::class)->immutable()->make();
        return self::create($repository, $paths, $names, $shortCircuit, $fileEncoding);
    }
    /**
     * Create a new dotenv instance with an array backed repository.
     *
     * @param string|string[]      $paths
     * @param string|string[]|null $names
     * @param bool                 $shortCircuit
     * @param string|null          $fileEncoding
     *
     * @return \Dotenv\Dotenv
     */
    public static function createArrayBacked($paths, $names = null, bool $shortCircuit = \true, ?string $fileEncoding = null)
    {
        $repository = RepositoryBuilder::createWithNoAdapters()->addAdapter(ArrayAdapter::class)->make();
        return self::create($repository, $paths, $names, $shortCircuit, $fileEncoding);
    }
    /**
     * Parse the given content and resolve nested variables.
     *
     * This method behaves just like load(), only without mutating your actual
     * environment. We do this by using an array backed repository.
     *
     * @param string $content
     *
     * @throws \Dotenv\Exception\InvalidFileException
     *
     * @return array<string, string|null>
     */
    public static function parse(string $content)
    {
        $repository = RepositoryBuilder::createWithNoAdapters()->addAdapter(ArrayAdapter::class)->make();
        $phpdotenv = new self(new StringStore($content), new Parser(), new Loader(), $repository);
        return $phpdotenv->load();
    }
    /**
     * Read and load environment file(s).
     *
     * @throws \Dotenv\Exception\InvalidPathException|\Dotenv\Exception\InvalidEncodingException|\Dotenv\Exception\InvalidFileException
     *
     * @return array<string, string|null>
     */
    public function load()
    {
        $entries = $this->parser->parse($this->store->read());
        return $this->loader->load($this->repository, $entries);
    }
    /**
     * Read and load environment file(s), silently failing if no files can be read.
     *
     * @throws \Dotenv\Exception\InvalidEncodingException|\Dotenv\Exception\InvalidFileException
     *
     * @return array<string, string|null>
     */
    public function safeLoad()
    {
        try {
            return $this->load();
        } catch (InvalidPathException $e) {
            // suppressing exception
            return [];
        }
    }
    /**
     * Required ensures that the specified variables exist, and returns a new validator object.
     *
     * @param string|string[] $variables
     *
     * @return \Dotenv\Validator
     */
    public function required($variables)
    {
        return (new Validator($this->repository, (array) $variables))->required();
    }
    /**
     * Returns a new validator object that won't check if the specified variables exist.
     *
     * @param string|string[] $variables
     *
     * @return \Dotenv\Validator
     */
    public function ifPresent($variables)
    {
        return new Validator($this->repository, (array) $variables);
    }
}
