<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\Symfony\Component\Translation;

use OtomatiesCoreVendor\Symfony\Contracts\Translation\TranslatableInterface;
use OtomatiesCoreVendor\Symfony\Contracts\Translation\TranslatorInterface;
/** @internal */
final class StaticMessage implements TranslatableInterface
{
    public function __construct(private string $message)
    {
    }
    public function getMessage() : string
    {
        return $this->message;
    }
    public function trans(TranslatorInterface $translator, ?string $locale = null) : string
    {
        return $this->getMessage();
    }
}
