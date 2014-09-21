<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Geoffrey Bachelet geoffrey@stage1.io
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Docklet\Container;


class Port
{
    protected $port;
    protected $protocol = 'tcp';
    protected $hostPort;
    protected $hostIp;

    /**
     * @param string|integer $raw
     */
    public function __construct($raw)
    {
        $parsed = static::parse($raw);
        $filtered = array_filter($parsed);

        foreach ($filtered as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return integer
     */
    public function getHostPort()
    {
        return $this->hostPort;
    }

    /**
     * @return string
     */
    public function getHostIp()
    {
        return $this->hostIp;
    }

    /**
     * @return array
     */
    public function toSpec()
    {
        return [
            $this->port.'/'.$this->protocol => [
                [
                    'HostIp' => $this->hostIp,
                    'HostPort' => (string) $this->hostPort
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function toExposedPorts()
    {
        return [$this->port.'/'.$this->protocol => []];
    }

    /**
     * [[hostIp:][hostPort]:]port[/protocol]
     *
     * @param string $raw
     *
     * @return array
     * @throws \Exception
     */
    static public function parse($raw)
    {
        if (!preg_match('/(?:(?<hostIp>[0-9\.]{7,15}):)?(?:(?<hostPort>\d{1,5}|):)?(?<port>\d{1,5})(?:\/(?<protocol>\w+))?/', $raw, $matches)) {
            throw new \Exception('Invalid port specification "'.$raw.'"');
        }

        $parsed = [];

        foreach (['hostIp', 'hostPort', 'port', 'protocol'] as $key) {
            if (array_key_exists($key, $matches)) {
                $parsed[$key] = strlen($matches[$key]) > 0
                    ? (is_numeric($matches[$key])
                        ? (integer) $matches[$key]
                        : $matches[$key])
                    : null;
            } else {
                $parsed[$key] = null;
            }
        }

        return $parsed;
    }
}