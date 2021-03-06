<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drak\NativeSession;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler;

/**
 * NativeRedisSessionStorage.
 *
 * Driver for the redis session save handler provided by the redis PHP extension.
 *
 * @see https://github.com/nicolasff/phpredis
 *
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
class NativeRedisSessionHandler extends NativeSessionHandler
{
    /**
     * Constructor.
     *
     * @param string $savePath Path of redis server.
     */
    public function __construct($savePath = 'tcp://127.0.0.1:6379?persistent=0', array $options = [])
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException('PHP does not have "redis" session module registered');
        }

        if (null === $savePath) {
            $savePath = ini_get('session.save_path');
        }

        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', $savePath);

        $this->setOptions($options);
    }

    /**
     * Set any redis ini values.
     *
     */
    protected function setOptions(array $options)
    {
        $validOptions = array_flip([ 'cookie_lifetime' ]);

        foreach ($options as $key => $value) {
            if (isset($validOptions[$key])) {
                ini_set('session.' . $key, $value);
            }
        }
    }
}
