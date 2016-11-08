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

use GanbaroDigital\ServiceLogger\V1\ServiceLogger;
use Interop\Container\ContainerInterface;

/**
 * build a `LogRequestAction` middleware component
 *
 * this factory assumes that we're part of Zend Expressive:
 * - $container->get('config') contains the app's config
 *
 * as long as these assumptions are satisfied by your framework of choice,
 * you can reuse this factory in other frameworks :)
 */
class LogRequestFactory
{
    /**
     * build a `LogRequestAction` middleware component
     *
     * @param  ContainerInterface $container
     *         the DI container that holds our config
     * @return LogRequestAction
     *         the middle component, ready to use
     */
    public function __invoke(ContainerInterface $container)
    {
        // we need a logger
        $logger = $container->get(ServiceLogger::class);

        // what about config?
        $logLevel = GetRequestReplyLogLevel::from($container);

        // at this point, we have everything we need
        // so let's get it built!
        $retval = new LogRequestAction($logger, $logLevel);

        // all done
        return $retval;
    }
}