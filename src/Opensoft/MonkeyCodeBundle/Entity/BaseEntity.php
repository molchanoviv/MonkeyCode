<?php
 /**
 * Copyright (c) 2013 Molchanov Ivan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Opensoft\MonkeyCodeBundle\Entity;

use Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * Opensoft\MonkeyCodeBundle\Entity\BaseEntity
 *
 * @ORM\MappedSuperclass()
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class BaseEntity 
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * Getters and Setters implementation
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments = [])
    {
        $type = substr($name, 0, 3);
        $name = strtolower(substr($name, 3, strlen($name)));
        if($type === 'get') {
            $reflection = new \ReflectionClass($this);
            if (!$reflection->hasProperty($name)) {
                throw new Exception('Unknown property');
            }
            return $reflection->getProperty($name)->getValue($this);
        } else if($type === 'set') {
            if (count($arguments) > 1) {
                throw new Exception('Only one argument is possible for this function');
            }
            $reflection = new \ReflectionClass($this);
            if (!$reflection->hasProperty($name)) {
                throw new Exception('Unknown property');
            }
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($this, $arguments[0]);
            $property->setAccessible(false);

            return $this;
        } else {
            throw new Exception('Unknown function');
        }
    }
} 
