<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview;

use Twilio\Domain;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\ListResource;
use Twilio\Rest\Preview\Wireless\CommandContext;
use Twilio\Rest\Preview\Wireless\CommandList;
use Twilio\Rest\Preview\Wireless\RatePlanContext;
use Twilio\Rest\Preview\Wireless\RatePlanList;
use Twilio\Rest\Preview\Wireless\SimContext;
use Twilio\Rest\Preview\Wireless\SimList;
use Twilio\Version;

/**
 * @property CommandList commands
 * @property RatePlanList ratePlans
 * @property SimList sims
 * @method CommandContext commands(string $sid)
 * @method RatePlanContext ratePlans(string $sid)
 * @method SimContext sims(string $sid)
 */
class Wireless extends Version {
    protected $_commands = null;
    protected $_ratePlans = null;
    protected $_sims = null;

    /**
     * Construct the Wireless version of Preview
     * 
     * @param Domain $domain Domain that contains the version
     * @return Wireless Wireless version of Preview
     */
    public function __construct(Domain $domain) {
        parent::__construct($domain);
        $this->version = 'wireless';
    }

    /**
     * Magic getter to lazy load root resources
     *
     * @param string $name Resource to return
     *
     * @return ListResource The requested resource
     * @throws TwilioException For unknown resource
     */
    public function __get($name) {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new TwilioException('Unknown resource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     *
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Preview.Wireless]';
    }

    /**
     * @return CommandList
     */
    protected function getCommands() {
        if (!$this->_commands) {
            $this->_commands = new CommandList($this);
        }
        return $this->_commands;
    }

    /**
     * @return RatePlanList
     */
    protected function getRatePlans() {
        if (!$this->_ratePlans) {
            $this->_ratePlans = new RatePlanList($this);
        }
        return $this->_ratePlans;
    }

    /**
     * @return SimList
     */
    protected function getSims() {
        if (!$this->_sims) {
            $this->_sims = new SimList($this);
        }
        return $this->_sims;
    }
}