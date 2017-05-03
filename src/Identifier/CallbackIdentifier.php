<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Authentication\Identifier;

use ArrayAccess;
use InvalidArgumentException;
use RuntimeException;

/**
 * Callback Identifier
 */
class CallbackIdentifier extends AbstractIdentifier
{
    /**
     * Default configuration
     *
     * @var array
     */
    protected $_defaultConfig = [
        'callback' => null
    ];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->checkCallable();
    }

    /**
     * Check the callable option
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function checkCallable()
    {
        $callback = $this->getConfig('callback');

        if (!is_callable($callback)) {
            throw new InvalidArgumentException(sprintf(
                'The `callback` option is not a callable. Got `%s` instead.',
                gettype($callback)
            ));
        }
    }

    /**
     * Identify
     *
     * @param array $data Authentication credentials
     * @return \ArrayAccess|null
     */
    public function identify(array $data)
    {
        $callback = $this->getConfig('callback');

        $result = $callback($data);
        if ($result === null || $result instanceof ArrayAccess) {
            return $result;
        }

        throw new RuntimeException(sprintf(
            'Invalid return type of `%s`. Expecting `%s` or `null`.',
            gettype($result),
            ArrayAccess::class
        ));
    }
}
