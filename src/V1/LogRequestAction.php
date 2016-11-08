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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * log the incoming HTTP request to our app's logfile
 */
class LogRequestAction
{
    /**
     * how we tell our devs what we're up to
     * @var ServiceLogger
     */
    protected $logger;

    /**
     * the log level we should use
     *
     * @var string
     */
    protected $logLevel;

    /**
     * create a new instance of this middleware
     *
     * @param ServiceLogger $logger
     *        the logger that we're going to use
     */
    public function __construct(ServiceLogger $logger, $logLevel = "INFO")
    {
        $this->logger = $logger;
        $this->logLevel = $logLevel;
    }

    /**
     * run this middleware
     *
     * @param  ServerRequestInterface $request
     *         the HTTP request that we want to log
     * @param  ResponseInterface      $response
     *         the response that the middleware is going to build
     * @param  callable|null          $next
     *         the next piece of middleware in the chain
     * @return ResponseInterface
     *         the response that the middleware chain builds
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $this->logger->log($logLevel, "REQUEST", [
            'method' => $request->getMethod(),
            'queryPath' => $request->getRequestTarget(),
            'payload' => (string)$request->getBody()
        ]);

        // is the pipeline empty?
        // it would be worrying if this was the case :)
        if ($next === null) {
            return $response;
        }

        // move on to the next item in the middleware pipeline
        return $next($request, $response);
    }
}