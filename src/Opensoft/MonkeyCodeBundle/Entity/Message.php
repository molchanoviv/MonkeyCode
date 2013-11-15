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

use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Opensoft\MonkeyCodeBundle\Service\BBCodeRenderer;
use Symfony\Component\DependencyInjection\Container;

/**
 * Opensoft\MonkeyCodeBundle\Entity\Message
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Service("opensoft_monkey_code.entity.message")
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class Message extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Opensoft\MonkeyCodeBundle\Entity\User", inversedBy="messages")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $postingTime;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $text;

    /**
     * @ORM\Column(type="text", name="raw_text")
     *
     * @var string
     */
    protected $rawText;

    /**
     * @var BBCodeRenderer
     */
    protected $bbCodeRenderer;

    /**
     * Constructor
     *
     * @InjectParams({
     * "container" = @Inject("service_container"),
     * })
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->bbCodeRenderer = $container->get('opensoft_monkey_code.bbcode_renderer');
    }


    public function setText($text)
    {
        $this->rawText = $text;
        $this->text = $this->bbCodeRenderer->render($text);
    }
} 
