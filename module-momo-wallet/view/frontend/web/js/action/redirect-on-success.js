/**********************************************************************
 * redirect-on-success
 *
 * @copyright Copyright © ManhThuong99. All rights reserved.
 * @author   thuongnm(mthuong03@gmail.com)
 */
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'mage/url',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (url, fullScreenLoader) {
        'use strict';

        return {
            redirectUrl: window.checkoutConfig.payment.momoWallet.redirectUrl,

            /**
             * Provide redirect to page
             */
            execute: function () {
                fullScreenLoader.startLoader();
                window.location.replace(this.redirectUrl);
            }
        };
    }
);
