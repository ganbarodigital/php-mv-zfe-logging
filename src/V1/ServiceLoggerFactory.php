<?php

/**
 * Copyright (c) 2016-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   ZfeLogging
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/zfe-mv-logging
 */

namespace GanbaroDigital\ZfeLogging\V1;

use GanbaroDigital\ServiceLogger\V1\BuildServiceLogger;
use GanbaroDigital\ServiceLogger\V1\ServiceLogger;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;

/**
 * ZFE factory for creating our logger
 */
class ServiceLoggerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        // here's the default config we're going to use, if none is
        // provided
        $defaultConfig = [
            'min_log_level' => 'INFO',
            'log_file' => 'app.log',
            'request_reply' => [
                'log_level' => 'INFO'
            ]
        ];

        // go and get the logger config
        $config = $container->get('config');
        if (isset($config['logger'])) {
            $defaultConfig = array_merge($defaultConfig, $config['logger']);
        }

        $handler = new StreamHandler($defaultConfig['log_file'], $defaultConfig['min_log_level']);
        $handler->setFormatter(new ServiceFormatter);

        $logger = new ServiceLogger(
            "ServiceLogger",
            [ $handler ],
            [ new IntrospectionProcessor ]
        );

        // all done
        return $logger;
    }
}
