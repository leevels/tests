<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace queryyetsimple\throttler\middleware;

<<<queryphp
##########################################################
#   ____                          ______  _   _ ______   #
#  /     \       ___  _ __  _   _ | ___ \| | | || ___ \  #
# |   (  ||(_)| / _ \| '__|| | | || |_/ /| |_| || |_/ /  #
#  \____/ |___||  __/| |   | |_| ||  __/ |  _  ||  __/   #
#       \__   | \___ |_|    \__  || |    | | | || |      #
#     Query Yet Simple      __/  |\_|    |_| |_|\_|      #
#                          |___ /  Since 2010.10.03      #
##########################################################
queryphp;

use queryyetsimple\http\response;
use queryyetsimple\mvc\exception\too_many_requests_http;
use queryyetsimple\throttler\interfaces\throttler as interfaces_throttler;

/**
 * throttler 中间件
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.08.10
 * @version 1.0
 */
class throttler {
    
    /**
     * throttler
     *
     * @var \queryyetsimple\throttler\interfaces\throttler $objThrottler
     */
    protected $objThrottler;
    
    /**
     * HTTP Response
     *
     * @var \queryyetsimple\http\response $objResponse
     */
    protected $objResponse;
    
    /**
     * 构造函数
     *
     * @param \queryyetsimple\throttler\interfaces\throttler $objThrottler            
     * @param \queryyetsimple\http\response $objResponse            
     * @return void
     */
    public function __construct(interfaces_throttler $objThrottler, response $objResponse) {
        $this->objThrottler = $objThrottler;
        $this->objResponse = $objResponse;
    }
    
    /**
     * 请求
     *
     * @param mixed|\queryyetsimple\request $objRequest            
     * @param int $intLimit            
     * @param int $intLime            
     * @return mixed
     */
    public function handle($objRequest, $intLimit = 60, $intLime = 60) {
        $oRateLimiter = $this->objThrottler->create ( null, ( int ) $intLimit, ( int ) $intLime );
        
        if ($oRateLimiter->attempt ()) {
            $this->header ( $oRateLimiter );
            throw new too_many_requests_http ( 'Too many attempts' );
        } else {
            $this->header ( $oRateLimiter );
        }
        
        return $objRequest;
    }
    
    /**
     * 发送 HEADER
     *
     * @param \queryyetsimple\throttler\rate_limiter $oRateLimiter            
     * @return void
     */
    protected function header($oRateLimiter) {
        $this->objResponse->headers ( $oRateLimiter->toArray () );
    }
}